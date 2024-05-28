<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    // This execute migrations before testing???
    use RefreshDatabase;

    // What is this???
    protected $seed = true;
    protected $accessToken = true;

    // VER JUNTO CON CLIENT HOW THIS WORK.
    public $mockConsoleOutput = false;

    public function setUp(): void
    {
        parent::setUp();

        // Create a test user with role player for testing purposes.
        $testUser = User::factory()->create([
            'nickname' => 'TestUser',
            'email' => 'test@mail.com',
            'password' => '123456789',
        ]);
        $testUser->assignRole('player');
        
        // Pass and create the Personal Access Client 
        $this->artisan('passport:client --personal --no-interaction');
    }
}
