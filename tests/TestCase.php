<?php

namespace Tests;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Spatie\Snapshots\MatchesSnapshots;

abstract class TestCase extends BaseTestCase
{
    use MatchesSnapshots;

    const string TEST_IMAGE = __DIR__.'/test.jpg';

    const string PROPER_IMAGE = __DIR__.'/proper-file.jpg';

    protected function buildTestStorage(): Filesystem
    {
        return Storage::build([
            'driver' => 'local',
            'root' => base_path('tests'),
        ]);
    }
}
