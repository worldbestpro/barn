<?php


namespace App\Repositories;


use App\Models\User;

class UserRepository extends Repository
{
    protected $createFromFields = ['name', 'email', 'image', 'password'];
    protected $updateFromFields = ['name', 'email', 'image', 'password'];

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->object = $user;
    }

}