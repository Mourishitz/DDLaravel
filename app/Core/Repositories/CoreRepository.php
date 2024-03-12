<?php

namespace App\Core\Repositories;

use App\Core\Enums\ResponseEnum;
use App\Core\Exceptions\RepositoryException;
use App\Core\Interfaces\RepositoryInterface;
use Closure;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * BaseRepository
 */
class CoreRepository implements RepositoryInterface
{
    protected string $model;

    protected array $with = [];

    public function __construct(?string $model = null)
    {
        if ($model !== null) {
            $this->model = $model;
        }
    }

    protected function newQuery(): Builder
    {
        return $this->model()
            ->newQuery();
    }

    public function model(): mixed
    {
        return app($this->model);
    }

    final public function getFillable(): array
    {
        return $this->model()->getFillable();
    }

    public function setQuery(Builder $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return SupportCollection|LengthAwarePaginator|Builder[]|Collection
     */
    protected function doQuery(
        ?Builder $query = null,
        bool $paginate = false,
        ?int $limit = null,
        ?array $appends = null
    ): SupportCollection|Collection|LengthAwarePaginator|array {
        if (is_null($query)) {
            $query = $this->newQuery();
        }

        if (! empty($this->with)) {
            $query->with($this->with);
        }

        if ($paginate) {
            return $query
                ->paginate($limit ?: 15)
                ->appends($appends);
        }

        if ($limit !== null) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function findAll(?array $appends = null): SupportCollection|Collection|LengthAwarePaginator|array
    {
        return $this->doQuery(null, false, null, $appends);
    }

    /**
     * @param  array|string[]  $columns
     *
     * @throws RepositoryException
     */
    public function findByID(
        int $id,
        array $columns = ['*'],
        ?bool $fail = true,
        ?bool $withTrashed = false,
    ): ?Model {
        $query = $this->newQuery()->with($this->with);

        if ($withTrashed) {
            /** @phpstan-ignore-next-line **/
            $query->withTrashed();
        }

        if ($fail) {
            try {
                return $query->findOrFail($id, $columns);
            } catch (ModelNotFoundException $e) {
                throw new RepositoryException(
                    message: ResponseEnum::RESOURCE_NOT_FOUND->label(),
                    code: Response::HTTP_NOT_FOUND,
                    previous: $e
                );
            }
        }

        return $query->find($id, $columns);
    }

    /**
     * @param  array|string[]  $columns
     *
     * @throws RepositoryException
     */
    public function findBy(string $attribute, $value, array $columns = ['*'], bool $cachedResults = false): ?Model
    {
        try {
            if ($cachedResults) {
                return $this->findByCached($attribute, $value, $columns);
            }

            return $this->newQuery()
                ->with($this->with)
                ->where($attribute, '=', $value)
                ->first($columns);
        } catch (Exception|QueryException $e) {
            throw new RepositoryException(
                message: ResponseEnum::RESOURCE_NOT_FOUND->label(),
                code: Response::HTTP_NOT_FOUND,
                previous: $e
            );
        }
    }

    /**
     * @param  array|string[]  $columns
     */
    private function findByCached(string $attribute, mixed $value, array $columns = ['*']): ?Model
    {
        return Cache::rememberForever($this->model.$value, function () use ($attribute, $value, $columns) {
            return $this->newQuery()
                ->with($this->with)
                ->where($attribute, '=', $value)
                ->first($columns);
        });
    }

    /**
     * @throws RepositoryException
     */
    public function create(array $data, bool $loadRelationships = true): Model
    {
        try {
            $fillableData = $this->getFillableData($data);

            $query = $this->newQuery()->create($fillableData);

            if ($loadRelationships) {
                $query->load($this->with);
            }

            return $query;
        } catch (Exception|QueryException $e) {
            throw new RepositoryException(
                message: ResponseEnum::FAILED_REGISTER->label(),
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                previous: $e
            );
        }
    }

    public function firstOrCreate(array $data, bool $loadRelationships = false): Model
    {
        $object = $this->newQuery()->firstOrCreate($data);

        if ($loadRelationships) {
            $object->load($this->with);
        }

        return $object;
    }

    /**
     * @throws Exception
     */
    public function update(array $data, int $id): bool
    {
        try {
            $fillableData = $this->getFillableData($data);

            $object = $this->findByID($id, ['id']);

            return $object?->update($fillableData);
        } catch (QueryException $e) {
            throw new RepositoryException(
                message: ResponseEnum::BAD_REQUEST->label(),
                code: Response::HTTP_BAD_REQUEST,
                previous: $e
            );
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): ?bool
    {
        $object = $this->findByID($id, ['*']);

        return $object?->delete();
    }

    public function with(array $with = []): static
    {
        $this->with = $with;

        return $this;
    }

    /**
     * @param  array|string[]  $columns
     */
    public function first(array $columns = ['*']): ?Model
    {
        $query = $this->newQuery()->first($columns);

        if ($query) {
            return $query->load($this->with);
        }

        return $query;
    }

    public function existsBy(
        string $attribute,
        $value,
        ?string $with = null
    ): bool {
        $query = $this->newQuery();

        if (! empty($with)) {
            return $query->whereHas($with, function ($q) use ($attribute, $value) {
                $q->where($attribute, $value);
            })->exists();
        }

        return $query->where($attribute, '=', $value)->exists();
    }

    /**
     * @param  null  $operator
     */
    public function where(
        array|Closure|string $column,
        $operator = null,
        mixed $value = null,
        string $boolean = 'and'
    ): Builder {
        return $this->newQuery()->where($column, $operator, $value, $boolean);
    }

    public function whereDoesntHave(
        string $relation,
        ?Closure $callback = null
    ): Builder {
        return $this->newQuery()->doesntHave($relation, 'and', $callback);
    }

    public function whereIn($column, array $value = []): Builder
    {
        return $this->newQuery()->whereIn($column, $value);
    }

    public function count(): int
    {
        return $this->newQuery()->count();
    }

    public function getFillableData(array $data): array
    {
        return Arr::only($data, $this->getFillable());
    }

    /**
     * Set the columns to be selected.
     */
    public function select(array|string $columns = ['*']): Builder
    {
        return $this->newQuery()->select($columns);
    }
}
