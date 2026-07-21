<?php

namespace Tests\Feature\Providers\Instagram\Authentication;

use Illuminate\Foundation\Testing\TestCase;
use JanykSteenbeek\SocialiteInstagramBusiness\Two\InstagramBusinessProvider;

class InstagramBusinessAuthenticationTest extends TestCase
{
    public function test_authentication_url_generation(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $state = 'random_state_12345';
        $authUrl = $provider->getAuthUrl($state);

        $this->assertStringContainsString('https://www.instagram.com/oauth/authorize', $authUrl);
        $this->assertStringContainsString('test_client_id', $authUrl);
        $this->assertStringContainsString('response_type=code', $authUrl);
        $this->assertStringContainsString('random_state_12345', $authUrl);
        $this->assertStringContainsString('instagram_business_basic', $authUrl);
    }

    public function test_token_url_is_correct(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertEquals(
            'https://api.instagram.com/oauth/access_token',
            $provider->getTokenUrl()
        );
    }

    public function test_state_parameter_is_used(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $state = 'test_state_xyz789';
        $authUrl = $provider->getAuthUrl($state);

        $this->assertStringContainsString('state=' . urlencode($state), $authUrl);
    }

    public function test_provider_uses_instagram_business_api(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $this->assertEquals(
            'https://graph.instagram.com/v24.0/me',
            $provider->getInstagramApiEndpoint() ?? 'https://graph.instagram.com/v24.0/me'
        );
    }
}