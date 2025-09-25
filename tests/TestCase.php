<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Spatie\Snapshots\MatchesSnapshots;

abstract class TestCase extends BaseTestCase
{
    use MatchesSnapshots;

    const string TEST_IMAGE = __DIR__.'/test.jpg';

    const string PROPER_IMAGE = __DIR__.'/proper-file.jpg';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::ensureFileTimestamp(self::TEST_IMAGE);
        self::ensureFileTimestamp(self::PROPER_IMAGE);
    }

    private static function ensureFileTimestamp(string $filename): void
    {
        touch($filename, Carbon::create(1970, 01, 01, 1, 1, 1)->getTimestamp());
    }

    protected function buildTestStorage(): Filesystem
    {
        return Storage::build([
            'driver' => 'local',
            'root' => base_path('tests'),
        ]);
    }
}
