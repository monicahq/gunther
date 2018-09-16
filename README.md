# Gunther
Laravel translations update on Crowdin.

This package uses [caouecs/Laravel-lang](https://packagist.org/packages/caouecs/laravel-lang) and [umpirsky/locale-list](https://packagist.org/packages/umpirsky/locale-list) to update languages for a Laravel project on Crowdin.


### Configuration

Publish the config file `gunther.php`:

``` bash
php artisan vendor:publish --provider="Gunther\Providers\ServiceProvider" --tag=config
```

## License

Copyright (c) 2018

Licensed under the AGPL License. [View license](/LICENSE).
