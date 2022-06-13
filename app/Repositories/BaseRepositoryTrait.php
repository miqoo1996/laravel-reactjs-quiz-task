<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait BaseRepositoryTrait
{
    private Model $model;
    private Builder $query;

    protected int $paginationPerPage = 10;

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    public function model(array $data = []) : Model
    {
        if (!empty($data)) {
            $this->model = clone $this->model;

            $this->model = $this->model->fill($data);
        }

        return $this->model;
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function save(array $data = []) : bool
    {
        return $this->model($data)->save();
    }

    public function saveMultiple(array $data = []) : Collection
    {
        return collect($data)->map(function (array $data) {
            ($model = clone $this)->save($data);

            return $model;
        });
    }

    public function applyRelations(array $relations)
    {
        return $this->query()->with($relations);
    }

    public function setPaginationPerPage(int $paginationPerPage): self
    {
        $this->paginationPerPage = $paginationPerPage;

        return $this;
    }

    public function applyWhereConditions(array $conditions) : self
    {
        if (!empty($conditions)) {
            $this->query()->where($conditions);
        }

        return $this;
    }

    public function first(bool $failOnFind = false, bool $returnEmptyMode = false, array $columns = ['*']) :? Model
    {
        if ($failOnFind) {
            return $this->firstOrFail($columns);
        }

        return $this
            ->query()
            ->first($columns) ?: (
        $returnEmptyMode ? $this->model() : null
        );
    }

    public function firstOrFail(array $columns = ['*']) :? Model
    {
        return $this
            ->query()
            ->firstOrFail($columns);
    }

    public function applyId($id) : Builder
    {
        return $this->query()->where('id', $id);
    }

    public function fetchById($id) :? Model
    {
        return $this
            ->applyId($id)
            ->first();
    }

    /**
     * @param bool $paginated
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder|mixed
     */
    public function fetchAll(bool $paginated = false, array $columns = ['*'])
    {
        if ($paginated) {
            return $this->query()->paginate($this->paginationPerPage, $columns);
        }

        return $this->query()->get($columns);
    }

    public function delete(bool $returnDeleted = false, bool $failOnFind = true)
    {
        if (!$returnDeleted) {
            return $this->query()->delete();
        }

        ($model = $this->first($failOnFind))->delete();

        return $model;
    }
}
