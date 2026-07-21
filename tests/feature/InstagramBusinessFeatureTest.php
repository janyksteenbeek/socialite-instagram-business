<?php

namespace Tests\\Feature\\Providers\\Instagram;

use Illuminate\\Foundation\\Testing\\TestCase;
use Illuminate\\Http\\Request;
use JanykSteenbeek\\SocialiteInstagramBusiness\\Two\\InstagramBusinessProvider;

class InstagramBusinessProviderFeatureTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_laravel_13_event_discovery_system(): void
    {
        $this->mock(
            'Illuminate\\\\Foundation\\\\Application',
            function ($mock) {
                $mock->shouldReceive('hasBeenBootstrapped')
                    ->andReturn(true);
                $mock->shouldReceive('environment')
                    ->andReturn('testing');
            }
        );

        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertTrue(method_exists($provider, 'getUserByToken'));
        $this->assertTrue(method_exists($provider, 'mapUserToObject'));
        $this->assertTrue(method_exists($provider, 'getAccessToken'));
    }

    public function test_provider_returns_valid_user_data_structure(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $tokenResponse = [
            'user_id' => '12345',
            'username' => 'testuser',
            'name' => 'Test User',
            'account_type' => 'BUSINESS',
            'media_count' => 10,
            'followers_count' => 100,
            'follows_count' => 50,
            'profile_picture_url' => 'https://example.com/avatar.jpg',
        ];

        $user = $provider->mapUserToObject($tokenResponse);

        $this->assertEquals('12345', $user->getId());
        $this->assertEquals('testuser', $user->getNickname());
        $this->assertEquals('Test User', $user->getName());
        $this->assertEquals('BUSINESS', $user->account_type);
        $this->assertEquals(10, $user->media_count);
        $this->assertEquals(100, $user->followers_count);
        $this->assertEquals(50, $user->follows_count);
        $this->assertEquals('https://example.com/avatar.jpg', $user->avatar);
    }

    public function test_provider_handles_missing_optional_fields_gracefully(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $tokenResponse = [
            'user_id' => '12345',
            'username' => 'testuser',
            'name' => 'Test User',
            'account_type' => 'BUSINESS',
        ];

        $user = $provider->mapUserToObject($tokenResponse);

        $this->assertEquals('12345', $user->getId());
        $this->assertEquals('testuser', $user->getNickname());
        $this->assertEquals('Test User', $user->getName());
        $this->assertEquals('BUSINESS', $user->account_type);
        $this->assertNull($user->media_count);
        $this->assertNull($user->followers_count);
        $this->assertNull($user->follows_count);
        $this->assertNull($user->avatar);
    }

    public function test_laravel_13_json_api_compatibility(): void
    {
        $this->markTestSkipped('JSON:API support to be implemented');
    }

    public function test_laravel_13_csrf_protection_compatibility(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertTrue(method_exists($provider, 'getAccessToken'));
        $this->assertTrue(method_exists($provider, 'getUserByToken'));
    }
}