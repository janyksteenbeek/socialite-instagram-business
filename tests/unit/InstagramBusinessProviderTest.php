<?php

namespace Tests\Unit\Providers;

use Illuminate\Foundation\Testing\TestCase;
use JanykSteenbeek\SocialiteInstagramBusiness\Two\InstagramBusinessProvider;

class InstagramBusinessProviderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_provider_can_be_instantiated_with_valid_credentials(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertInstanceOf(InstagramBusinessProvider::class, $provider);
        $this->assertEquals('test_client_id', $provider->clientId);
        $this->assertEquals('test_client_secret', $provider->clientSecret);
    }

    public function test_provider_has_correct_identifier(): void
    {
        $this->assertEquals('INSTAGRAM_BUSINESS', InstagramBusinessProvider::IDENTIFIER);
    }

    public function test_provider_scopes_are_correctly_set(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertIsArray($provider->scopes);
        $this->assertContains('instagram_business_basic', $provider->scopes);
    }

    public function test_provider_fields_are_defined(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertIsArray($provider->fields);
        $this->assertNotEmpty($provider->fields);
    }

    public function test_provider_scope_separator_is_space(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertEquals(' ', $provider->scopeSeparator);
    }

    public function test_provider_can_build_auth_url(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $state = 'random_state';
        $url = $provider->getAuthUrl($state);

        $this->assertIsString($url);
        $this->assertStringContainsString('instagram.com/oauth/authorize', $url);
        $this->assertStringContainsString('test_client_id', $url);
    }

    public function test_provider_has_correct_token_url(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertEquals('https://api.instagram.com/oauth/access_token', $provider->getTokenUrl());
    }
}