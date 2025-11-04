<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    // Model property on class instances
    protected $model;

    /** @var Builder */
    protected $query;

    // Constructor to bind model to repo
    public function __construct()
    {
        $this->setModel();
        $this->query = $this->model->newQuery();
    }

    // Get the associated model
    abstract public function getModel();

    // Set the associated model
    public function setModel()
    {
        $this->model = app()->make($this->getModel());
    }


    public function getAll()
    {
        return $this->model->all();
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }

    // create a new record in the database
    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    /**
     * Insert
     * @param array $attributes
     * @return mixed
     */
    public function insert(array $attributes)
    {
        return $this->model->insert($attributes);
    }

    // update record in the database
    public function update($id, $attributes = [])
    {
        $record = $this->model->find((int) $id);

        if (!$record) {
            return false;
        }

        $record->update($attributes);

        // ép cập nhật updated_at nếu dữ liệu không đổi
        if (!$record->wasChanged()) {
            $record->touch();
        }

        return $record;
    }


    // remove record from the database
    public function delete($id)
    {
        return $this->model->destroy((int)$id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    // find the record with the given id
    public function find($id)
    {
        return $this->model->find($id);
    }

    // find the first record
    public function first()
    {
        return $this->model->first();
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    // phpcs:ignore
    public function getQuery()
    {
        return $this->query->getQuery();
    }

    public function clearQuery()
    {
        $this->query = $this->model->newQuery();
        return $this->query->getQuery();
    }

    public function findBy(array $filter, bool $toArray = true)
    {
        $builder = $this->model->newQuery();
        foreach ($filter as $key => $val) {
            $builder->where($key, $val);
        }
        $find = $builder->get();

        if (!$toArray) {
            return $find;
        }
        return $find ? $find->toArray() : null;
    }

    public function findOneBy(array $filter, bool $toArray = true)
    {
        $builder = $this->model->newQuery();
        foreach ($filter as $key => $val) {
            $builder->where($key, $val);
        }
        $data = $builder->first();

        if (!$toArray) {
            return $data;
        }
        return $data ? $data->toArray() : [];
    }

    /**
     * paginate
     * @param $page
     * @return LengthAwarePaginator|mixed
     */
    public function paginate($page)
    {
        return $this->query->paginate($page);
    }

    public function updateWhere(
        array $attributes = [],
        array $params = []
    ): void {
        $this->model->where($attributes)->update($params);
    }

    /**
     * deleteBy
     * @param array $filter
     * @return void
     */
    public function deleteBy(array $filter): void
    {
        $this->model->where($filter)->delete();
    }

    /**
     * Find where in the record with the given id
     * @param array $filter
     * @param bool $toArray
     * @return array|Collection
     */
    public function findWhereIn(array $filter, bool $toArray = true)
    {
        $data = $this->model->whereIn($filter['column'], $filter['values'])->get();

        if (!$toArray) {
            return $data;
        }
        return $data ? $data->toArray() : [];
    }
}
