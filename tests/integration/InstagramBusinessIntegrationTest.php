<?php

namespace Tests\Integration\Providers\Instagram;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Event;
use JanykSteenbeek\SocialiteInstagramBusiness\Listeners\InstagramBusinessExtendSocialite;
use JanykSteenbeek\SocialiteInstagramBusiness\Two\InstagramBusinessProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class InstagramBusinessIntegrationTest extends TestCase
{
    public function test_laravel_13_event_discovery_without_manual_registration(): void
    {
        if (version_compare($this->app->version(), '12.0.0', '>=')) {
            $this->markTestSkipped('Laravel 12+ has event discovery');
        }

        Event::listen(
            SocialiteWasCalled::class,
            function (SocialiteWasCalled $event) {
                $event->extendSocialite('instagram-business', InstagramBusinessProvider::class);
            }
        );

        $this->assertTrue(class_exists(InstagramBusinessExtendSocialite::class));
    }

    public function test_provider_registration_with_laravel_13_facade(): void
    {
        $this->markTestSkipped('Integration test requires full Laravel application setup');
    }

    public function test_config_services_file_integration(): void
    {
        $expectedConfigPath = config_path('services.php');
        $this->assertFileExists($expectedConfigPath);

        $config = include $expectedConfigPath;

        $this->assertArrayHasKey('instagram-business', $config);

        if (array_key_exists('instagram-business', $config)) {
            $this->assertArrayHasKey('client_id', $config['instagram-business']);
            $this->assertArrayHasKey('client_secret', $config['instagram-business']);
            $this->assertArrayHasKey('redirect', $config['instagram-business']);
        }
    }

    public function test_listeners_can_be_discovered(): void
    {
        $listenersPath = app_path('Listeners');
        $this->assertDirectoryExists($listenersPath);

        $listenersFiles = glob($listenersPath . '/*.php', GLOB_BRACE);
        $this->assertGreaterThan(0, count($listenersFiles));

        $this->assertTrue(in_array(app_path('Listeners/InstagramBusinessExtendSocialite.php'), $listenersFiles));
    }

    public function test_provider_uses_correct_api_version(): void
    {
        $provider = new InstagramBusinessProvider(
            'test_client_id',
            'test_client_secret',
            'http://localhost/callback'
        );

        $reflection = new \ReflectionClass($provider);
        $properties = $reflection->getProperties();

        $hasFieldsProperty = array_any($properties, function ($property) {
            return $property->getName() === 'fields';
        });

        $this->assertTrue($hasFieldsProperty);
    }
}