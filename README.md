# Instagram Business provider for Laravel Socialite

https://developers.facebook.com/docs/instagram-platform/instagram-api-with-instagram-login/business-login

```bash
composer require janyksteenbeek/socialite-instagram-business
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'instagram-business' => [    
  'client_id' => env('INSTAGRAM_CLIENT_ID'),  
  'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),  
  'redirect' => env('INSTAGRAM_REDIRECT_URI') 
],
```

### Add provider event listener

#### Laravel 13+

In Laravel 13, event discovery allows you to create event listeners without manual registration. In your `Listeners` directory, create a listener that extends `SocialiteWasCalled` events, and Laravel will automatically detect and use it.

* Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.

```php
namespace JanykSteenbeek\\SocialiteInstagramBusiness\\Listeners;

use SocialiteProviders\\Manager\\SocialiteWasCalled;
use JanykSteenbeek\\SocialiteInstagramBusiness\\Two\\InstagramBusinessProvider;

class InstagramBusinessExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite('instagram-business', InstagramBusinessProvider::class);
    }
}
```

#### Laravel 12+

In Laravel 12, event discovery was introduced. Create a listener in your `Listeners` directory and Laravel will automatically discover it.

```php
namespace JanykSteenbeek\\SocialiteInstagramBusiness\\Listeners;

use SocialiteProviders\\Manager\\SocialiteWasCalled;
use JanykSteenbeek\\SocialiteInstagramBusiness\\Two\\InstagramBusinessProvider;

class InstagramBusinessExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite('instagram-business', InstagramBusinessProvider::class);
    }
}
```

#### Laravel 11+

In Laravel 11, the default `EventServiceProvider` provider was removed. Instead, add the listener using the `listen` method on the `Event` facade, in your `AppServiceProvider` `boot` method.

* Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.

```php
Event::listen(function (\\SocialiteProviders\\Manager\\SocialiteWasCalled $event) {
    $event->extendSocialite('instagram-business', \\JanykSteenbeek\\SocialiteInstagramBusiness\\Two\\InstagramBusinessProvider::class);
});
```
<details>
<summary>
Laravel 10 or below
</summary>
Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \\SocialiteProviders\\Manager\\SocialiteWasCalled::class => [
        // ... other providers
        \\JanykSteenbeek\\SocialiteInstagramBusiness\\Listeners\\InstagramBusinessExtendSocialite::class.'@handle',
    ],
];
```
</details>

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('instagram-business')->redirect();
```

### Returned User fields
- `id`
- `nickname` (Instagram username)
- `name`
- `account_type`
- `media_count`
- `followers_count`
- `follows_count`
- `avatar` (URL to profile picture)

