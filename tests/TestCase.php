<?php

namespace Tests;

use App\Models\Content;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Refuse to run against anything but a test database: RefreshDatabase wipes it.
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        $database = (string) $app['config']->get('database.connections.'.$app['config']->get('database.default').'.database');

        if (! str_ends_with($database, '_test') && $database !== ':memory:') {
            throw new RuntimeException("Tests must run against a *_test database, not [{$database}]. Run them with phpunit.xml.");
        }

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Content values are memoised in a static property, which outlives a single test.
        Content::flush();
    }
}
