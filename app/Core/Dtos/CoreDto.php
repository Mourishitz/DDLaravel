<?php

namespace App\Core\Dtos;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

abstract class CoreDto extends Collection
{
    protected $items = [];

    private array $base_items = [
        'limit',
        'paginate',
        'page',
    ];

    private array $subtypes = [];

    private array $rules;

    public function __construct()
    {
        $this->items = array_merge($this->items, $this->base_items);

        parent::__construct($this->items);
    }

    public function getSubtype(string $field): bool|string
    {
        return $this->subtypes[$field] ?? false;
    }

    public function populateFromArray(array $data): CoreDto
    {
        $this->populate($data);

        $this->items = collect($this->items)
            ->filter(function ($value, $key) {
                return ! is_numeric($key);
            })
            ->all();

        return $this;
    }

    private function populate(array $data): void
    {
        $data = array_intersect_key($data, array_flip($this->toArray()));

        collect($data)
            ->filter(function ($value) {
                return ! in_array($value, [null, ''], true);
            })
            ->map(function ($value, $key) {
                if (is_array($value) && $this->getSubtype($key)) {
                    $value = $this->populateSubtypeFromArray($key, $value);
                }
                $this[$key] = $value;
                $key = array_search($key, $this->items, false);
                if ($key !== false) {
                    unset($this->items[$key]);
                }
            });
    }

    /**
     * Populate subtype(s) from array, and return the toArray() of subtype(s)
     *
     * Subtypes in DTO-s can be defined as an array of subtype objects, or as one subtype object.
     *
     * This method detects if DTO subtype is an array of subtypes, or just one subtype.
     * After populating the subtype(s) from array(s), the toArray() of one subtype DTO is returned,
     * or an array of subtype DTO-s toArray()-s.
     *
     * @return <missing>@param  array<int,mixed>  $value
     */
    private function populateSubtypeFromArray(string $key, array $value): array
    {
        if (is_array($this[$key])) {
            return collect($value)->map(function (array $values) use ($key) {
                return (new $this->subtypes[$key]())->populateFromArray($values)
                    ->toArray();
            })->toArray();
        }

        return (new $this->subtypes[$key]())->populateFromArray($value)
            ->toArray();
    }

    public static function fromArray(array $source, ?array $rules = []): CoreDto
    {
        if (! empty($rules)) {
            return (new static())->setRules($rules)->populateFromArray($source);
        }

        return (new static())->populateFromArray($source);
    }

    public function setRules(array $rules): BaseDto
    {
        $this->rules = $rules;

        return $this;
    }

    public function getLimit(): ?int
    {
        return Arr::get($this->items, 'limit');
    }

    public function setLimit(int $limit): void
    {
        $this->items['limit'] = $limit;
    }

    public function getPaginate(): ?bool
    {
        return Arr::get($this->items, 'paginate', false);
    }

    public function setPaginate(bool $paginate): void
    {
        $this->items['paginate'] = $paginate;
    }

    public function getPage(): ?int
    {
        return Arr::get($this->items, 'page');
    }

    public function setPage(int $page): void
    {
        $this->items['page'] = $page;
    }

    public function allPopulate(): array
    {
        return Arr::where($this->items, function (mixed $value, string|int $key) {
            return is_string($key);
        });
    }

    public function getNumericAttribute(string $attribute): ?string
    {
        $item = Arr::get($this->items, $attribute);

        return is_numeric($item) ? $item : null;
    }
}
