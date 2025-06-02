<?php

namespace App\Services\Image;

use App\Services\Image\Exif\ExifData;
use App\Services\Image\Exif\ExifReader;
use App\Services\Image\ImageType\ImageType;
use App\Services\Image\ImageType\ImageTypeDetector;
use App\Services\Image\IPTC\IptcData;
use App\Services\Image\IPTC\IptcReader;
use App\Services\Image\IPTC\IptcWriter;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class Image
{
    public function __construct(
        protected readonly TempFile $tempFile,
    ) {}

    public static function fromDisk(string $filename, null|string|Filesystem $disk = null): static
    {
        /** @var Filesystem $disk */
        $disk = $disk instanceof Filesystem ? $disk : Storage::disk($disk);
        $content = $disk->get($filename);

        // Debug the values to see what's actually happening
        $originalTime = $disk->lastModified($filename);
        ray("Original timestamp: $originalTime (".date('Y-m-d H:i:s', $originalTime).")\n")->label('Original Timestamp');

        return new self(
            TempFile::withFilename($filename)
                ->write($content)
                ->setLastModified($disk->lastModified($filename))
        );
    }

    public static function fromContent(string $content): static
    {
        return new self(TempFile::withRandomName()->write($content));
    }

    /**
     * @param  resource|null  $streamContext
     */
    public static function fromPath(string $path, mixed $streamContext = null): static
    {
        return new self(TempFile::withFilename($path)->copyFrom($path, $streamContext));
    }

    public function exifReader(): ExifReader
    {
        return ExifReader::fromFilename($this->tempFile->filename());
    }

    public function exif(): ?ExifData
    {
        return $this->exifReader()->read();
    }

    public function iptcReader(): IptcReader
    {
        return IptcReader::fromFilename($this->tempFile->filename());
    }

    public function iptc(): ?IptcData
    {
        return $this->iptcReader()->read();
    }

    public function type(): ImageType
    {
        return (new ImageTypeDetector)->detect($this->tempFile->filename());
    }

    public function contents(): ?string
    {
        return file_get_contents($this->tempFile->filename());
    }

    public function iptcWriter(): IptcWriter
    {
        return new IptcWriter($this->tempFile->filename());
    }

    public function writeIptc(IptcData $data): static
    {
        $this->iptcWriter()->write($data);

        return $this;
    }
}
