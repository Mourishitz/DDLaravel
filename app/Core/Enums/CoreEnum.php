<?php

namespace App\Core\Enums;

use App\Core\Exceptions\EnumException;
use Illuminate\Support\Arr;

trait CoreEnum
{
    public static function toString(): string
    {
        return implode(',', self::toArray());
    }

    public static function toStringLabel(): string
    {
        return implode(',', self::toArrayLabel());
    }

    public static function toArray(): array
    {
        return array_map(static fn ($case) => $case->value, self::cases());
    }

    /**
     * @return string|array|null[]
     */
    public static function toArrayLabel(): array
    {
        return array_map(static fn ($case) => __($case->value), self::cases());
    }

    public static function toArrayName(): array
    {
        return array_map(static fn ($case) => $case->name, self::cases());
    }

    /**
     * @throw EnumException
     */
    public static function getByIndex(int $index): object
    {
        $cases = self::cases();
        if (array_key_exists($index, $cases)) {
            return $cases[$index];
        }

        throw new EnumException(
            message: "Index $index is not a valid index"
        );
    }

    public static function getByName(string $name): object
    {
        return Arr::first(self::cases(), function ($case) use ($name) {
            return $case->name === $name;
        });
    }

    public function label(): string
    {
        return __($this->value);
    }

    public function replace(string|array $search, string|array $replace): string
    {
        return str_replace($search, $replace, __($this->value));
    }
}
