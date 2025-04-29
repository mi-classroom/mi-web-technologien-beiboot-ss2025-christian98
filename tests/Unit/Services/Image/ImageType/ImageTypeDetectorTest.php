<?php

namespace Tests\Unit\Services\Image\ImageType;

use App\Services\Image\ImageType\ImageType;
use App\Services\Image\ImageType\ImageTypeDetector;
use Tests\TestCase;

class ImageTypeDetectorTest extends TestCase
{
    public function test_should_detect_jpg(): void
    {
        $data = (new ImageTypeDetector)->detect(self::PROPER_IMAGE);

        $this->assertSame(ImageType::JPEG, $data);
        $this->assertSame('image/jpeg', $data->mimeType());
    }
}
