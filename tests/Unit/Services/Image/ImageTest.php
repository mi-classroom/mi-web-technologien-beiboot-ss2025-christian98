<?php

namespace Tests\Unit\Services\Image;

use App\Services\Image\Image;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function test_should_extract_exif()
    {
        $image = new Image(self::PROPER_IMAGE);

        $this->assertMatchesObjectSnapshot($image->exif()->toArray());
    }

    public function test_should_extract_iptc()
    {
        $image = new Image(self::PROPER_IMAGE);

        $this->assertMatchesObjectSnapshot($image->iptc()->toArray());
    }
}
