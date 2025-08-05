<?php

namespace Tests\Unit\Services\Image;

use App\Services\Image\Image;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function test_should_extract_exif(): void
    {
        $image = Image::fromPath(self::PROPER_IMAGE);

        $this->assertMatchesObjectSnapshot($image->exif()->toArray());
    }

    public function test_should_extract_exif_from_storage(): void
    {
        $storage = $this->buildTestStorage();
        $image = Image::fromDisk(basename(self::PROPER_IMAGE), $storage);

        $this->assertMatchesObjectSnapshot($image->exif()->toArray());
    }

    public function test_should_extract_iptc(): void
    {
        $image = Image::fromPath(self::PROPER_IMAGE);

        $this->assertMatchesObjectSnapshot($image->iptc()->toArray());
    }
}
