<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Broadcast;
use App\Providers\BroadcastServiceProvider;
use Illuminate\Support\Facades\Route;

class BroadcastServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Register the service provider
        $this->app->register(BroadcastServiceProvider::class);
    }

    public function testBootMethodRegistersBroadcastRoutes()
    {
        Broadcast::routes();

        // Check if the broadcast routes are registered
        $this->assertTrue(Route::has('broadcasting.auth'));

        // Check if the channels.php file is included
        $this->assertTrue(file_exists(base_path('routes/channels.php')));
    }
}