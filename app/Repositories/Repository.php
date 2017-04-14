<?php

namespace App\Repositories;


use App\Contracts\RepositoryContract;
use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryContract
{
    /**
     * @var Model
     */
    protected $object;

    /**
     * @var array[]
     */
    protected $updateFromFields;

    /**
     * @var array[]
     */
    protected $createFromFields;

    public function getAll($columns = ['*'])
    {
        return $this->object->all($columns);
    }

    public function whereIn($field, $values = [])
    {
        return $this->object->whereIn($field, $values)->get();
    }

    public function where($field, $value)
    {
        return $this->object->where($field, '=', $value)->first();
    }

    public function find($id)
    {
        return $this->object->find($id);
    }

    public function getPaginated($perPage)
    {
        return $this->object->paginate($perPage);
    }


    public function update()
    {
        $args   = collect(func_get_args());
        $object = $args->shift();
        $this->fillFields($object, $this->updateFromFields, $args->toArray());
        $object->update();

        return $object;
    }


    /**
     * Should pass arguments like they specified in "createFromFields" field
     * return object that extends Model class
     * @param params
     * @return mixed
     */
    public function create()
    {
        $this->fillFields($this->object, $this->createFromFields, func_get_args());
        $this->object->save();

        return $this->object;
    }

    public function fillFields($object, $fields, $arguments)
    {
        foreach ($fields as $key => $field) {
            $object->{$field} = $arguments[$key];
        }
    }

    public function createFromJob($job)
    {
        foreach ($this->createFromFields as $field) {
            $this->object->{$field} = $job->{$field};
        }
        $this->object->save();

        return $this->object;
    }

    public function updateFromJob($object, $job)
    {

        foreach ($this->updateFromFields as $field) {

            $object->{$field} = $job->{$field};
        }
        $object->save();

        return $object;
    }

    public function delete($object)
    {
        $object->delete();
    }
}