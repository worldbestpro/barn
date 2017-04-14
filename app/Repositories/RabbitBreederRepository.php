<?php

namespace App\Repositories;


use App\Models\RabbitBreeder;

class RabbitBreederRepository extends Repository
{
    protected $createFromFields = ['name', 'breed', 'cage', 'tattoo', 'sex', 'weight', 'father_id', 'mother_id', 'color', 'aquired', 'image', 'notes'];
    protected $updateFromFields = ['name', 'breed', 'cage', 'tattoo', 'sex', 'weight', 'father_id', 'mother_id', 'color', 'aquired', 'image', 'notes'];

    /**
     * RabbitBreederRepository constructor.
     * @param RabbitBreeder $breeder
     */
    public function __construct(RabbitBreeder $breeder)
    {
        $this->object = $breeder;
    }
}