<?php

namespace tests\Unit\Services\Image\ImageType;

use App\Services\Image\ImageType\ImageType;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ImageTypeTest extends TestCase
{
    public static function allImageTypes(): array
    {
        return collect(ImageType::cases())
            ->mapWithKeys(fn (ImageType $imageType) => [$imageType->name => ['imageType' => $imageType]])
            ->toArray();
    }

    #[DataProvider('allImageTypes')]
    public function test_should_provide_mime_type(ImageType $imageType): void
    {
        $this->assertMatchesTextSnapshot($imageType->mimeType());
    }
}
