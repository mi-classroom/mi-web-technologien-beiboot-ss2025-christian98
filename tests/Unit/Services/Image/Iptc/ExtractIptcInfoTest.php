<?php

namespace Tests\Unit\Services\Image\Iptc;

use App\Services\Image\IPTC\IptcReader;
use Tests\TestCase;

class ExtractIptcInfoTest extends TestCase
{
    public function test_should_extract(): void
    {
        $data = IptcReader::fromFilename(self::PROPER_IMAGE)->read();

        $this->assertMatchesObjectSnapshot($data->toArray());
    }
}
