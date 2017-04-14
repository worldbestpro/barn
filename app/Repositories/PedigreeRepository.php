<?php

namespace App\Repositories;


use App\Models\Pedigree;

class PedigreeRepository extends Repository
{
    protected $createFromFields = ['name', 'custom_id', 'day_of_birth', 'breed', 'sex', 'image', 'notes'];
    protected $updateFromFields = ['name', 'custom_id', 'day_of_birth', 'breed', 'sex', 'image', 'notes'];

    /**
     * PedigreeRepository constructor.
     * @param Pedigree $pedigree
     */
    public function __construct(Pedigree $pedigree)
    {
        $this->object = $pedigree;
    }
}