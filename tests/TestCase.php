<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public function setUp(): void
    {
        parent::setUp();

        // Rest of your setup code...

        // Set Passport client IDs and secrets for testing
        Artisan::call('passport:install');
    }
}
