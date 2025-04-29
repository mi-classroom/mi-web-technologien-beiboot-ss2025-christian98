<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

abstract class TestCase extends BaseTestCase
{
    use MatchesSnapshots;

    const string TEST_IMAGE = __DIR__.'/test.jpg';

    const string PROPER_IMAGE = __DIR__.'/proper-file.jpg';
}
