<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface RepositoryInterface 
{
    /**
     * Get all
     * @return mixed
     */
    public function getAll();

    /**
     * Get all
     * @return mixed
     */
    public function all();

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Get one
     * @return mixed
     */
    public function first();

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = []);

    /**
     * Insert
     * @param array $attributes
     * @return mixed
     */
    public function insert(array $attributes);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, $attributes = []);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Show
     * @param $id
     * @return mixed
     */
    public function show($id);

    /**
     * Get query
     * @return mixed
     */
    public function getQuery();

    /**
     * Clear query
     * @return \Illuminate\Database\Query\Builder
     */
    public function clearQuery();

    /**
     * File all
     * @param array $filter
     * @return mixed
     */
    public function findBy(array $filter, bool $toArray = true);

    /**
     * Find one
     * @param array $filter
     * @return mixed
     */
    public function findOneBy(array $filter, bool $toArray = true);

    /**
     * paginate
     * @param $page
     * @return LengthAwarePaginator|mixed
     */
    public function paginate($page);

    public function updateWhere(
        array $attributes = [],
        array $params = []
    ): void;

    /**
     * Delete by
     * @param array $filter
     * @return void
     */
    public function deleteBy(array $filter): void;

    /**
     * Find where in the record with the given id
     * @param array $filter
     * @param bool $toArray
     * @return array|Collection
     */
    public function findWhereIn(array $filter, bool $toArray = true);
}
