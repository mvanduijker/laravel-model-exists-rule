<?php

namespace Duijker\LaravelModelExistsRule;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

class LaravelModelExistsServiceProvider extends ServiceProvider
{
    function boot()
    {
        Rule::macro('modelExists', function (string $modelClass, string $modelAttribute = 'id', callable $callback = null) {
            return new ModelExists($modelClass, $modelAttribute, $callback);
        });

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/modelExists'),
        ]);

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang/', 'modelExists');
    }
}
