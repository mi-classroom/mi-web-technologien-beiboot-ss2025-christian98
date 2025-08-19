<?php

namespace App\Services\Storage\Provider\Webdav;

use App\Models\StorageConfig;
use App\Services\Storage\Provider\Directory;
use App\Services\Storage\Provider\File;
use App\Services\Storage\Provider\Provider;
use League\Flysystem\PathPrefixer;
use League\Flysystem\UnableToWriteFile;
use RuntimeException;
use Sabre\DAV\Client;
use Throwable;

class WebdavProvider extends Provider
{
    public readonly Client $client;
    public readonly PathPrefixer $prefixer;

    public function __construct(StorageConfig $config)
    {
        parent::__construct($config);

        $pathPrefix = $this->config->provider_options['path_prefix'] ?? parse_url($this->config->provider_options['base_uri'], PHP_URL_PATH) ?? '';
        $this->prefixer = new PathPrefixer($pathPrefix);
        $this->client = new Client([
            'baseUri' => $this->config->provider_options['base_uri'] ?? '',
            'userName' => $this->config->provider_options['username'] ?? '',
            'password' => $this->config->provider_options['password'] ?? '',
            'pathPrefix' => $pathPrefix,
        ]);
    }

    public function directory(string $path): ?Directory
    {
        return new WebdavDirectory($path, $this);
    }

    public function file(string $path): ?File
    {
        return new WebdavFile($path, $this);
    }

    public function upload(string $path, string $content): ?File
    {
        $this->createParentDirFor($path);
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        try {
            $response = $this->client->request('PUT', $location, $content);
            $statusCode = $response['statusCode'];

            if ($statusCode < 200 || $statusCode >= 300) {
                throw new RuntimeException('Unexpected status code received: ' . $statusCode);
            }
        } catch (Throwable $exception) {
            throw UnableToWriteFile::atLocation($path, $exception->getMessage(), $exception);
        }
    }

    /**
     * @param string $path
     * @param array<int, string> $property
     * @param int $depth
     * @return array
     */
    public function propFind(string $path, array $property, int $depth = 0): array
    {
        $location = $this->encodePath($this->prefixer->prefixPath($path));

        return $this->client->propFind($location, $property, $depth);
    }

    protected function encodePath(string $path): string
    {
        $parts = explode('/', $path);

        foreach ($parts as $i => $part) {
            $parts[$i] = rawurlencode($part);
        }

        return implode('/', $parts);
    }
}
