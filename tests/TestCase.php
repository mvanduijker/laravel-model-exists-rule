<?php

namespace Duijker\LaravelModelExistsRule\Tests;

use Duijker\LaravelModelExistsRule\LaravelModelExistsServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
        });

        $app['db']->connection()->getSchemaBuilder()->create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $app['db']->connection()->getSchemaBuilder()->create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelModelExistsServiceProvider::class,
        ];
    }
}
