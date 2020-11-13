# Laravel Model Exists Rule

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mvanduijker/laravel-model-exists-rule.svg?style=flat-square)](https://packagist.org/packages/mvanduijker/laravel-model-exists-rule)
![Build Status](https://github.com/mvanduijker/laravel-model-exists-rule/workflows/Run%20tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/mvanduijker/laravel-model-exists-rule.svg?style=flat-square)](https://packagist.org/packages/mvanduijker/laravel-model-exists-rule)


Laravel validation rule to check if a model exists.

You want to use this rule if the standard laravel `Rule::exists('table', 'column')` is not powerful enough.
When you want to add joins to your exist rule, or the advanced Eloquent\Builder features like whereHas this might be for you.


## Installation

You can install the package via composer:

```bash
composer require mvanduijker/laravel-model-exists-rule
```

## Usage

Simple

```php
<?php

use Duijker\LaravelModelExistsRule\ModelExists;
use Illuminate\Foundation\Http\FormRequest;

class ExampleUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => [
                'required',
                new ModelExists(\App\Models\User::class, 'id'),        
            ],
        ];
    }
}
```

Advanced

```php
<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExampleUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => [
                'required',
                Rule::modelExists(\App\Models\User::class, 'id', function (Builder $query) {
                    $query->whereHas('role', function (Builder $query) {
                        $query->whereIn('name', ['super-admin', 'admin']);
                    });                    
                }),        
            ],
        ];
    }
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Mark van Duijker](https://github.com/mvanduijker)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
