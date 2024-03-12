<?php

namespace App\Core\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class CoreRequest extends FormRequest
{
    protected array $rules = [];

    abstract public function rules(): array;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get keys of rules
     */
    public function getKeys(): array
    {
        return collect($this->rules)->keys()->all();
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        $attributes = $this->getKeys();
        $parseAttributes = [];
        foreach ($attributes as $attribute) {
            $parsedAttribute = self::parseAttribute($attribute);
            $parseAttributes[$attribute] = __("attribute.{$parsedAttribute}");
        }

        return $parseAttributes;
    }

    public static function parseAttribute(string $attribute): string
    {
        if (strrchr($attribute, '.')) {
            return substr(strrchr($attribute, '.'), 1);
        }

        return $attribute;
    }
}
