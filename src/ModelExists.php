<?php

namespace Duijker\LaravelModelExistsRule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class ModelExists implements Rule
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var string
     */
    private $modelAttribute;

    /**
     * @var callable
     */
    private $closure;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(string $modelClass, string $modelAttribute = null, callable $closure = null)
    {
        $this->modelClass = $modelClass;
        $this->modelAttribute = $modelAttribute ?: (new $modelClass)->getKeyName();
        $this->closure = $closure ?? function () {};
    }

    public function passes($attribute, $value)
    {
        $this->value = $value;

        return $this->modelClass::query()
            ->when(
                is_array($value),
                function (Builder $query) {
                    $query->whereIn($this->modelAttribute, $this->value);
                },
                function (Builder $query) {
                    $query->where($this->modelAttribute, $this->value);
                }
            )
            ->tap($this->closure)
            ->exists();
    }

    public function message()
    {
        return trans('validation.model_exists', [
            'value' => $this->value,
            'model' => class_basename($this->modelClass),
            'model_attribute' => $this->modelAttribute,
        ]);
    }
}
