<?php

namespace Duijker\LaravelModelExistsRule\Tests\Unit;

use Duijker\LaravelModelExistsRule\ModelExists;
use Duijker\LaravelModelExistsRule\Tests\Support\Role;
use Duijker\LaravelModelExistsRule\Tests\Support\User;
use Duijker\LaravelModelExistsRule\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class ModelExistsTest extends TestCase
{
    /** @test */
    public function it_passes_when_model_exists()
    {
        $user = User::create(['email' => 'one@example.com']);

        $this->assertTrue((new ModelExists(User::class))->passes('user_id', $user->id));
    }

    /** @test */
    public function it_does_not_pass_when_model_does_not_exists()
    {
        User::create(['email' => 'one@example.com']);

        $this->assertFalse((new ModelExists(User::class))->passes('user_id', 'unexisting'));
    }

    /** @test */
    public function it_passes_when_multiple_models_exists()
    {
        $user1 = User::create(['email' => 'one@example.com']);
        $user2 = User::create(['email' => 'two@example.com']);

        $this->assertTrue((new ModelExists(User::class))->passes('user_id', [$user1->id, $user2->id]));
    }

    /** @test */
    public function it_does_not_pass_when_any_of_multiple_models_does_not_exist()
    {
        $user = User::create(['email' => 'one@example.com']);

        $this->assertTrue((new ModelExists(User::class))->passes('user_id', [$user->id, 'unexisting']));
    }

    /** @test */
    public function it_validates_with_advanced_query()
    {
        $user = User::create(['email' => 'one@example.com']);
        $adminRole = Role::create(['name' => 'admin']);
        $superAdminRole = Role::create(['name' => 'super admin']);
        $user->roles()->sync([$adminRole->id]);

        $rule = new ModelExists(User::class, 'id', function (Builder $query) {
            $query->whereHas('roles', function(Builder $query) {
                $query->where('name', 'admin');
            });
        });
        $this->assertTrue($rule->passes('user_id', $user->id));

        $rule = new ModelExists(User::class, 'id', function (Builder $query) {
            $query->whereHas('roles', function(Builder $query) {
                $query->where('name', 'super admin');
            });
        });
        $this->assertFalse($rule->passes('user_id', $user->id));
    }

    /** @test */
    public function it_translate_validation_message()
    {
        Lang::addLines([
            'validation.model_exists' => 'Field :attribute has no :model with :model_attribute :value',
        ], Lang::getLocale());

        $rule = new ModelExists(User::class);
        $rule->passes('user_id', 'unexisting');

        $this->assertEquals('Field :attribute has no User with id unexisting', $rule->message());
    }

    /** @test */
    public function it_can_be_constructed_through_macro()
    {
        $this->assertTrue(Rule::modelExists(User::class) instanceof ModelExists);
    }
}
