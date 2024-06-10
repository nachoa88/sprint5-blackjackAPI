<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // This execute migrations before testing???
    use RefreshDatabase;

    // This is to seed the database before running each test.
    protected $seed = true;
    protected $accessToken = true;

    // VER JUNTO CON CLIENT HOW THIS WORK.
    public $mockConsoleOutput = false;

    public function setUp(): void
    {
        parent::setUp();
        
        // Pass and create the Personal Access Client 
        $this->artisan('passport:client --personal --no-interaction');
    }
}
