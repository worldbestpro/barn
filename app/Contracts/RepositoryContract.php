<?php

namespace App\Contracts;

interface RepositoryContract
{
    public function getAll();

    public function whereIn($field, $values = []);

    public function getPaginated($perPage);

    public function update();

    /**
     * Should pass arguments like they specified in "createFromFields" field
     * return object that extends Model class
     * @param params
     * @return mixed
     */
    public function create();

    public function fillFields($object, $fields, $arguments);

    public function delete($object);

    public function find($id);

    public function where($field, $value);

    public function createFromJob($job);

    public function updateFromJob($object, $job);

}