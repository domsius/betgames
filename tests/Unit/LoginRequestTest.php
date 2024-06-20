<?php

namespace Tests\Unit\Http\Requests\Auth;

use Tests\TestCase;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Make sure to clear any existing rate limiter state
        RateLimiter::clear('test@example.com|127.0.0.1');
    }

    public function testAuthorize()
    {
        $request = new LoginRequest();

        $this->assertTrue($request->authorize());
    }

    public function testRules()
    {
        $request = new LoginRequest();

        $this->assertEquals([
            'email' => 'required|email',
            'password' => 'required',
        ], $request->rules());
    }

    public function testAuthenticate()
    {
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $request = new LoginRequest();
        $request->merge([
            'email' => $user->email,
            'password' => $password,
            'remember' => false,
        ]);

        Auth::shouldReceive('attempt')->once()->with([
            'email' => $user->email,
            'password' => $password,
        ], false)->andReturn(true);

        $request->authenticate();

        $this->assertTrue(Auth::check());
    }

    public function testAuthenticateFails()
    {
        $this->expectException(ValidationException::class);

        $user = \App\Models\User::factory()->create();

        $request = new LoginRequest();
        $request->merge([
            'email' => $user->email,
            'password' => 'wrong-password',
            'remember' => false,
        ]);

        Auth::shouldReceive('attempt')->once()->with([
            'email' => $user->email,
            'password' => 'wrong-password',
        ], false)->andReturn(false);

        $request->authenticate();
    }

    public function testEnsureIsNotRateLimited()
    {
        $request = new LoginRequest();
        $request->merge([
            'email' => 'test@example.com',
        ]);

        RateLimiter::hit($request->throttleKey(), 1);
        RateLimiter::hit($request->throttleKey(), 1);
        RateLimiter::hit($request->throttleKey(), 1);
        RateLimiter::hit($request->throttleKey(), 1);
        RateLimiter::hit($request->throttleKey(), 1);

        $this->expectException(ValidationException::class);

        $request->ensureIsNotRateLimited();
    }

    public function testThrottleKey()
    {
        $request = new LoginRequest();
        $request->merge([
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('test@example.com|127.0.0.1', $request->throttleKey());
    }

    public function testEnsureIsNotRateLimitedPasses()
    {
        $request = new LoginRequest();
        $request->merge([
            'email' => 'test@example.com',
        ]);

        RateLimiter::clear($request->throttleKey());

        $request->ensureIsNotRateLimited();

        $this->assertTrue(true); // If no exception is thrown, the test passes
    }
}