<?php

namespace Tests\Unit\Services\Image;

use App\Services\Image\Image;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function test_should_extract_exif()
    {
        $image = Image::fromPath(self::PROPER_IMAGE);

        $this->assertMatchesObjectSnapshot($image->exif()->toArray());
    }

    public function test_should_extract_exif_from_storage()
    {
        $image = Image::fromDisk('files/2XpCBbU3qJU0PLZ5gHbclyeepX2uEb5A5mQuB9XL.jpg', 'public');

        $this->assertMatchesObjectSnapshot($image->exif()->toArray());
    }

    public function test_should_extract_iptc()
    {
        $image = Image::fromPath(self::PROPER_IMAGE);

        $this->assertMatchesObjectSnapshot($image->iptc()->toArray());
    }
}
