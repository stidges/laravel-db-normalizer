Laravel DB Normalizer
=====================

[![Build Status](https://travis-ci.org/stidges/laravel-db-normalizer.png?branch=master)](https://travis-ci.org/stidges/laravel-db-normalizer) [![Latest Stable Version](https://poser.pugx.org/stidges/laravel-db-normalizer/version.png)](https://packagist.org/packages/stidges/laravel-db-normalizer) [![Latest Unstable Version](https://poser.pugx.org/stidges/laravel-db-normalizer/v/unstable.png)](//packagist.org/packages/stidges/laravel-db-normalizer) [![Total Downloads](https://poser.pugx.org/stidges/laravel-db-normalizer/downloads.png)](https://packagist.org/packages/stidges/laravel-db-normalizer)

This [Laravel](http://www.laravel.com) package allows you to easily swap out your repository implementations, by providing a unified interface to your database results.

It intercepts your results and turns them into collections and entities. That way, whether you are using Eloquent, the Query Builder or any other implementation, the results will be the same!

![Result](http://i.imgur.com/Y0rEyYq.jpg)

## Getting Started

This package can be installed through [Composer](http://www.getcomposer.org), just add it to your composer.json file:

```json
{
    "require": {
        "stidges/laravel-db-normalizer": "0.*"
    }
}
```

After you have added it to your composer.json file, make sure you update your dependencies:

```sh
composer install
```

Next, you can do either of these two:

##### 1. Enable auto-normalization:

By registering this package's ServiceProvider class, all the queries you run will be automatically normalized to the unified `Collection` and `Entity` classes. Add the following line to your `app/config/app.php` file:

```php
'Stidges\LaravelDbNormalizer\DbNormalizerServiceProvider',
```

When using Eloquent models, they should extend the `NormalizableModel` class:

```php
use Stidges\LaravelDbNormalizer\NormalizableModel;

class User extends NormalizableModel
{
    // ...
}
```


##### 2. Disable auto-normalization:

If you would rather want some more control, don't register the ServiceProvider. That way you can control when the results get cast to the classes. To do this, `use` the Normalizer class. **Make sure you always pass an array to the normalizer!**

```php
use Stidges\LaravelDbNormalizer\Normalizer;

//...

// Example using the query builder
$result = DB::table('users')->get();
// ... Do stuff with the result.
$normalized = with(new Normalizer)->normalize($result);

// Example using Eloquent
$user = User::find(1);
// ... Do stuff with the user.
$normalized = with(new Normalizer)->normalize($user->toArray());

// Example using Eloquent collection
$users = User::all();
// ... Do stuff with the users.
$normalized = with(new Normalizer)->normalize($users->toArray());
```

## Using the normalized results

This package provides 2 classes:

1. A `Collection` class. As it currently stand, this class just extends Laravel's  `Illuminate\Support\Collection` class.
2. An `Entity` class. This class can contain nested entities and collections (relations). It provides a fluent interface to accessing the attributes of the entity, and can be cast to an array or JSON using the familiar `toJson` and `toArray` methods. On top of that, it provides a `getDirtyAttributes()` function, which allows you to get all the attributes that were changed after creation.

## Example usage

For examples on how to use the package, please view the `examples` directory!

## Contributing

All suggestions and pull requests are welcome! If you make any substantial changes, please provide tests along with your pull requests!

## License

Copyright (c) 2014 Stidges - Released under the [MIT license](LICENSE).
