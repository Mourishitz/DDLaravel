<?php

namespace App\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use App\Core\Interfaces\RepositoryInterface;

class CoreRepository implements RepositoryInterface {

    public Builder $query;

    protected $model;

    protected array $with = [];

    public function __construct(string $model) {
        $this->model = $model;
    }

    public function model(): mixed {
        return app($this->model);
    }

    protected function newQuery(): Builder {
        $this->query = $this->model()->newQuery();
        return $this->query;
    }

    public function all() {
        return $this->model::with($this->with)->get();
    }

    public function findAll(?array $appends = []) {
        // Implement the findAll method
    }

    public function findByID($id) {
        // Implement the findByID method
    }

    public function findBy($field, $value) {
        // Implement the findBy method
    }

    public function create($data) {
        // Implement the create method
    }

    public function firstOrCreate($data) {
        // Implement the firstOrCreate method
    }

    public function update($id, $data) {
        // Implement the update method
    }

    public function delete($id) {
        // Implement the delete method
    }

    public function with($relations) {
        // Implement the with method
    }

    public function first() {
        // Implement the first method
    }

    public function existsBy($field, $value) {
        // Implement the existsBy method
    }

    public function where($field, $operator, $value) {
        // Implement the where method
    }

    public function whereDoesntHave($relation) {
        // Implement the whereDoesntHave method
    }

    public function whereIn($field, $values) {
        // Implement the whereIn method
    }

    public function count() {
        // Implement the count method
    }

    public function getFillableData($data) {
        // Implement the getFillableData method
    }
}
