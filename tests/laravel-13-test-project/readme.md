readme.md
# Laravel 13 Compatibility Test Suite

## Purpose

This test suite validates that the `socialite-instagram-business` package works correctly with Laravel 13.

## Requirements

- PHP 8.3+
- Laravel 13+
- Composer 2.0+

## Setup Instructions

### 1. Navigate to the test project
```bash
cd /path/to/socialite-instagram-business/tests/laravel-13-test-project
```

### 2. Install dependencies
```bash
composer install
```

### 3. Copy the package to the test project
The test project automatically includes the package as a local dependency via:

```json
"repositories": [
  {
    "type": "path",
    "path": "./.."
  }
]
```

### 4. Run the tests
```bash
phpunit
```

## What This Tests

1. **Package Registration**: Verifies the provider can be registered with Laravel Socialite
2. **Event System**: Tests Laravel 13 event discovery and listener functionality
3. **OAuth Flow**: Validates authentication flow with Instagram Business API
4. **Laravel 13 Features**: Tests compatibility with new Laravel 13 features
5. **Configuration**: Verifies config file handling in Laravel 13

## Expected Results

- All tests should pass without errors
- No warnings about deprecated APIs
- Successful registration of Instagram Business provider
- Proper handling of Laravel 13 authentication events

## Laravel 13 Specific Checks

This test suite validates the following Laravel 13 features:

1. **Event Discovery**: Automatic detection of listeners in the `Listeners` directory
2. **PHP 8.3 Requirements**: Compatibility with PHP 8.3+ features
3. **Enhanced Security**: CSRF protection and request forgery prevention
4. **JSON:API Support**: Potential for JSON:API resource compatibility
5. **Expanded PHP Attributes**: Support for new Laravel attribute syntax
6. **Queue Routing**: Class-based queue routing capabilities

## Test Coverage

The test suite includes unit tests for:

- Provider class initialization
- Authentication URL generation
- Token retrieval
- User data mapping
- Event listener registration
- Configuration handling
- Error handling

## Reporting Issues

If any tests fail:

1. Check the test output for specific error messages
2. Review the Laravel 13 migration guide for relevant changes
3. Verify the package version matches Laravel 13 requirements
4. Check composer.json for correct dependency versions

## Support

For issues with Laravel 13 compatibility:
1. Review the [Laravel 13 release notes](https://laravel.com/docs/13.x/releases)
2. Check the [Laravel Socialite documentation](https://laravel.com/docs/13.x/socialite)
3. Review the `socialiteproviders/manager` package changelog for Laravel 13 updates
4. Verify the `Instagram Business API` documentation for any changes

## License

This test suite is provided for compatibility testing purposes only and is not part of the original package.
