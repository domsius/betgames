<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class RouteServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Register the service provider
        $this->app->register(RouteServiceProvider::class);
    }

    public function testBootMethodRegistersApiRateLimiter()
    {
        $this->assertTrue(RateLimiter::has('api'));

        // Simulate a request and check the rate limiting
        $request = Request::create('/api/test', 'GET');
        $request->setUserResolver(function () {
            return (object) ['id' => 1];
        });

        $limit = RateLimiter::for('api', function (Request $request) {
            return $request->user() ? $request->user()->id : $request->ip();
        });

        $this->assertEquals('1', $limit($request)->key);
    }

    public function testBootMethodRegistersRoutes()
    {
        // Assert that the routes are registered
        $this->assertTrue(Route::has('api.test'));
        $this->assertTrue(Route::has('web.home'));

        // Define the routes in the test
        Route::middleware('api')->prefix('api')->group(base_path('routes/api.php'));
        Route::middleware('web')->group(base_path('routes/web.php'));

        // Check if the routes are callable
        $this->get('/api/test')->assertStatus(200);
        $this->get('/home')->assertStatus(200);
    }
}