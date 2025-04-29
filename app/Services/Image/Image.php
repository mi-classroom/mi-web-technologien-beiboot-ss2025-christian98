<?php

namespace App\Services\Image;

use App\Services\Image\Exif\ExifReader;
use App\Services\Image\ImageType\ImageTypeDetector;
use App\Services\Image\IPTC\IptcData;
use App\Services\Image\IPTC\IptcReader;
use App\Services\Image\IPTC\IptcWriter;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Interfaces\ImageInterface;
use RuntimeException;

class Image
{
    /**
     * @var resource
     */
    protected readonly mixed $tempFile;

    public function __construct(
        protected string $filename,
        protected ?string $disk = null,
    ) {
        // Copy image to temporary location
        $tmpFile = tmpfile();
        if ($tmpFile === false) {
            throw new RuntimeException('Failed to create temporary file');
        }
        fwrite($this->tempFile = $tmpFile, $this->contents());
    }

    protected function tempFileName()
    {
        return stream_get_meta_data($this->tempFile)['uri'];
    }

    public function exif(): Exif\ExifData
    {
        return ExifReader::fromFilename($this->tempFileName())->read();
    }

    public function iptc(): IPTC\IptcData
    {
        return IptcReader::fromFilename($this->tempFileName())->read();
    }

    public function type(): ImageType\ImageType
    {
        return (new ImageTypeDetector)->detect($this->tempFileName());
    }

    public function intervention(): ImageInterface
    {
        return \Intervention\Image\Laravel\Facades\Image::read($this->filename);
    }

    public function size(): int
    {
        return $this->disk()->size($this->filename);
    }

    public function mimeType(): string
    {
        return $this->disk()->mimeType($this->filename);
    }

    public function lastModified(): int
    {
        return $this->disk()->lastModified($this->filename);
    }

    public function url(): string
    {
        return $this->disk()->url($this->filename);
    }

    public function contents(): ?string
    {
        return $this->disk()->get($this->filename);
    }

    protected function disk(): Filesystem
    {
        return Storage::disk($this->disk);
    }

    public function __destruct()
    {
        fclose($this->tempFile); // Also delete the temporary file
    }

    public function writeIptc(IptcData $data): static
    {
        (new IptcWriter($this->tempFileName()))->write($data);

        return $this;
    }

    public function save(): void
    {
        $this->disk()->put($this->filename, file_get_contents($this->tempFileName()));
    }
}
