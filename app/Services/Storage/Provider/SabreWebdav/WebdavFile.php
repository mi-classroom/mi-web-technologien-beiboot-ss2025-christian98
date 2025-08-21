<?php

namespace App\Services\Storage\Provider\SabreWebdav;

use App\Services\Storage\Provider\File;
use League\Flysystem\UnableToRetrieveMetadata;
use RuntimeException;
use Sabre\DAV\Client;
use Sabre\HTTP\Request;
use Sabre\HTTP\ResponseInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WebdavFile extends File
{
    protected ?array $metaData = null;

    public function __construct(string $fullPath, public readonly WebdavProvider $provider)
    {
        parent::__construct($fullPath);
    }

    public function content(): string
    {
        return $this->requestFile()->getBodyAsString();
    }

    public function size(): ?int
    {
        return (int) $this->getMetaData(FileFindProperty::Size);
    }

    public function lastModified(): ?int
    {
        return (int) $this->getMetaData(FileFindProperty::LastModified);
    }

    public function mimeType(): ?string
    {
        return $this->getMetaData(FileFindProperty::MimeType);
    }

    public function download(?string $name = null): StreamedResponse
    {
        $response = new StreamedResponse;

        $response->headers->replace([
            'Content-Type' => $this->mimeType(),
            'Content-Length' => $this->size(),
            'Content-Disposition' => $response->headers->makeDisposition('attachment', $name ?? $this->name()),
        ]);

        $response->setCallback(function () {
            $stream = $this->requestFile()->getBodyAsStream();
            fpassthru($stream);
            fclose($stream);
        });

        return $response;
    }

    public function write(string $content): ?File
    {
        // TODO: Implement write() method.
    }

    public function client(): Client
    {
        return $this->provider->client;
    }

    protected function requestFile(): ResponseInterface
    {
        $url = $this->client()->getAbsoluteUrl(
            $this->provider->prefixer->prefixPath(
                $this->fullPath
            )
        );
        $response = $this->client()->send(new Request('GET', $url));

        if ($response->getStatus() !== 200) {
            throw new RuntimeException('Failed to retrieve file content: ' . $response->getBodyAsString());
        }

        return $response;
    }

    protected function getMetaData(FileFindProperty $property): mixed
    {
        if ($this->metaData === null) {
            $this->metaData = $this->provider->propFind(
                $this->fullPath,
                collect(FileFindProperty::cases())
                    ->map(fn (FileFindProperty $property) => $property->value)
                    ->all()
            );
        }

        return $this->metaData[$property->value];
    }
}
