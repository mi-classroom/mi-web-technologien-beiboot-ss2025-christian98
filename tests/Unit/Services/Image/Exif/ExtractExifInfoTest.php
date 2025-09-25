<?php

namespace Tests\Unit\Services\Image\Exif;

use App\Services\Image\Exif\ExifReader;
use Tests\TestCase;

class ExtractExifInfoTest extends TestCase
{
    public function test_should_extract(): void
    {
        $data = ExifReader::fromFilename(self::PROPER_IMAGE)->read();

        $this->assertMatchesObjectSnapshot($data->toArray());

        $this->markTestIncompleteIfSnapshotsHaveChanged();
    }
}
