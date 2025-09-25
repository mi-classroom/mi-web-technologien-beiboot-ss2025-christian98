<?php

namespace App\Services\Storage\Provider\SabreWebdav;

use App\Services\Storage\Provider\Directory;
use Illuminate\Support\Collection;
use League\Flysystem\UnableToDeleteDirectory;
use RuntimeException;
use Sabre\HTTP\ClientHttpException;
use Throwable;

class WebdavDirectory extends Directory
{
    public const FIND_PROPERTIES = [
        '{DAV:}displayname',
        '{DAV:}getcontentlength',
        '{DAV:}getcontenttype',
        '{DAV:}getlastmodified',
        '{DAV:}iscollection',
        '{DAV:}resourcetype',
    ];

    public function __construct(string $fullPath, public readonly WebdavProvider $provider)
    {
        parent::__construct($fullPath);
    }

    /**
     * {@inheritDoc}
     */
    public function children(): Collection
    {
        $response = $this->provider->propFind($this->fullPath, self::FIND_PROPERTIES, 1);

        // This is the directory itself, the files are subsequent entries.
        array_shift($response);

        return collect($response)->mapWithKeys(function (mixed $object, string $path) {
            $path = (string) parse_url(rawurldecode($path), PHP_URL_PATH);
            $path = $this->provider->prefixer->stripPrefix($path);
            $object = $this->normalizeObject($object);

            if (WebdavProvider::propsIsDirectory($object)) {
                return [$path => new WebdavDirectory($path, $this->provider)];
            }

            return [$path => new WebdavFile($path, $this->provider)];
        });
    }

    private function normalizeObject(array $object): array
    {
        $mapping = [
            '{DAV:}getcontentlength' => 'file_size',
            '{DAV:}getcontenttype' => 'mime_type',
            'content-length' => 'file_size',
            'content-type' => 'mime_type',
        ];

        foreach ($mapping as $from => $to) {
            if (array_key_exists($from, $object)) {
                $object[$to] = $object[$from];
            }
        }

        array_key_exists('file_size', $object) && $object['file_size'] = (int) $object['file_size'];

        if (array_key_exists('{DAV:}getlastmodified', $object)) {
            $object['last_modified'] = strtotime($object['{DAV:}getlastmodified']);
        }

        return $object;
    }

    public function delete(): void
    {
        $location = $this->provider->encodePath($this->provider->prefixer->prefixDirectoryPath($this->fullPath));

        try {
            $statusCode = $this->provider->client->request('DELETE', $location)['statusCode'];

            if ($statusCode !== 404 && ($statusCode < 200 || $statusCode >= 300)) {
                throw new RuntimeException('Unexpected status code received while deleting file: '.$statusCode);
            }
        } catch (Throwable $exception) {
            if (! ($exception instanceof ClientHttpException && $exception->getCode() === 404)) {
                throw UnableToDeleteDirectory::atLocation($this->fullPath, $exception->getMessage(), $exception);
            }
        }
    }
}
