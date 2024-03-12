<?php

namespace App\Core\Interfaces;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public Builder $query;

    public function model(): mixed;

    public function setQuery(Builder $query): self;

    public function findAll(?array $appends = []): \Illuminate\Support\Collection|Collection|LengthAwarePaginator|array;

    public function findByID(int $id, array $columns = ['*'], ?bool $fail = true): ?Model;

    public function findBy(string $attribute, $value, array $columns = ['*'], bool $cachedResults = false): ?Model;

    public function create(array $data, bool $loadRelationships = true): Model;

    public function firstOrCreate(array $data): Model;

    public function update(array $data, int $id): bool;

    public function delete(int $id): ?bool;

    /**
     * @return $this
     */
    public function with(array $with = []): static;

    public function first(array $columns = ['*']): ?Model;

    public function existsBy(string $attribute, $value): bool;

    /**
     * Add a basic where clause to the query.
     */
    public function where(
        array|Closure|string $column,
        ?string $operator = null,
        mixed $value = null,
        string $boolean = 'and'
    ): Builder;

    /**
     * Add a relationship count / exists condition to the query with where clauses.
     */
    public function whereDoesntHave(string $relation, ?Closure $callback = null): Builder|static;

    /**
     * Add a basic where in clause to the query.
     */
    public function whereIn($column, array $value = []): Builder;

    public function count(): int;

    public function getFillableData(array $data): array;
}
