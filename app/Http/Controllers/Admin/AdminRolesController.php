<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminRolesController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $roles;

    /**
     * AdminRolesController constructor.
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }


    public function getList()
    {
        return $this->roles->getAll(['id','display_name']);
    }
}
