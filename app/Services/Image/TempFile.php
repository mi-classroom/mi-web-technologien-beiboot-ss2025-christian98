<?php

namespace App\Services\Image;

use RuntimeException;

class TempFile
{
    /**
     * @param  resource  $tempFile
     */
    protected function __construct(
        protected readonly mixed $tempFile,
    ) {}

    public static function withFilename(string $path, string $mode = 'w+b'): TempFile
    {
        $tmpFilename = tempnam(sys_get_temp_dir(), 'image_');
        if ($tmpFilename === false) {
            throw new RuntimeException('Failed to create temporary file');
        }

        $tmpDir = dirname($tmpFilename);
        $tmpFilename = $tmpDir.DIRECTORY_SEPARATOR.basename($path);

        self::registerShutdownFunction($tmpFilename);

        return new self(fopen($tmpFilename, $mode));
    }

    public static function withRandomName(string $mode = 'w+b'): TempFile
    {
        $tmpFilename = tempnam(sys_get_temp_dir(), 'image_');
        if ($tmpFilename === false) {
            throw new RuntimeException('Failed to create temporary file');
        }

        self::registerShutdownFunction($tmpFilename);

        return new self(fopen($tmpFilename, $mode));
    }

    public function filename(): string
    {
        return stream_get_meta_data($this->tempFile)['uri'];
    }

    public function write(string $content): TempFile
    {
        if (! fwrite($this->tempFile, $content)) {
            throw new RuntimeException('Failed to write to temporary file');
        }

        return $this;
    }

    /**
     * @param  resource|null  $streamContext
     */
    public function copyFrom(string $path, mixed $streamContext = null): TempFile
    {
        if (! copy($path, $this->filename(), $streamContext)) {
            throw new RuntimeException('Failed to copy file');
        }

        // Set the file access time to match the original file
        $modificationTime = filemtime($path);
        $this->setLastModified($modificationTime);

        return $this;
    }

    public function setLastModified(int $timestamp): TempFile
    {
        touch($this->filename(), $timestamp);

        return $this;
    }

    public function __destruct()
    {
        $filename = $this->filename();
        if (is_resource($this->tempFile)) {
            fclose($this->tempFile);
        }

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    private static function registerShutdownFunction(string $tmpFilename): void
    {
        register_shutdown_function(function () use ($tmpFilename) {
            // Delete the temporary file
            if (file_exists($tmpFilename)) {
                unlink($tmpFilename);
            }
        });
    }
}
