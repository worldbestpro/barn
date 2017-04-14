<?php

namespace App\Repositories;


use App\Models\Role;

class RoleRepository extends Repository
{
    /**
     * @var Role
     */
    private $role;

    /**
     * RoleRepository constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->object = $role;
    }


}