<?php

namespace App\Jobs;

use App\Handlers\ImageHandler;
use App\Jobs\Job;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserJob extends Job implements ShouldQueue, SelfHandling
{
    use InteractsWithQueue, SerializesModels;

    private $password;
    private $user;
    private $role;
    private $name;
    private $email;
    /**
     * @var
     */
    private $image;

    public function __construct($user, $name, $email, $role, $image, $password)
    {
        $this->password = $password;
        $this->user     = $user;
        $this->name     = $name;
        $this->email    = $email;
        $this->role     = $role;
        $this->image    = $image;
    }


    public function handle(UserRepository $repo, ImageHandler $handler)
    {
        $image = $handler->prepareImageUsingTemp($this->image, 'users');

        $password = $this->password ? bcrypt($this->password) : $this->user->password;

        $user = $repo->update($this->user, $this->name, $this->email, $image['name'], $password);
        $user->roles()->detach();
        if ($this->role)
            $user->roles()->attach($this->role);

        return $user;
    }
}
