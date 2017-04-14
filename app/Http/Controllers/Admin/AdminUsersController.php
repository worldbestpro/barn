<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageHandler;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\GetUpcomingRequest;
use App\Http\Requests\GetUsersRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UploadImageRequest;
use App\Jobs\CreateUserJob;
use App\Jobs\UpdateUserJob;
use App\Models\Litter;
use App\Models\RabbitBreeder;
use App\Models\RabbitKit;
use App\Models\User;
use App\Repositories\UserRepository;

use Collective\Bus\Dispatcher;
use File;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Log;
use Auth;
class AdminUsersController extends Controller
{

    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * AdminUsersController constructor.
     * @param UserRepository $users
     * @param Dispatcher $dispatcher
     */
    public function __construct(UserRepository $users, Dispatcher $dispatcher)
    {
        $this->middleware('role:admin',['only'=>['index','store','update','destroy']]);
        $this->users = $users;

        $this->dispatcher = $dispatcher;
    }

    public function index(GetUsersRequest $request)
    {
        if ($request->get('paginated'))
            return $this->users->getPaginated(getenv('USERS_PER_PAGE'));

        return $this->users->getAll();
    }

    public function getCurrent()
    {
        return auth()->user();
    }

    public function store(CreateUserRequest $request)
    {
        return $this->dispatcher->dispatchFrom(CreateUserJob::class, $request);

    }

    public function show(User $user)
    {
        $roles = $user->roles->isEmpty() ? 0 : $user->roles()->first()->id;
        $user->setAttribute('role', $roles);

        return $user;
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $request['user'] = $user;

        $this->dispatcher->dispatchFrom(UpdateUserJob::class, $request);
    }

    public function destroy(User $user)
    {
        $this->users->delete($user);
    }

    public function settings(User $user)
    {

        return view('settings', compact('user'));
    }

    public function updateSettings(UpdateSettingsRequest $request, User $user)
    {
        $user->email = $request->get('email');
        if ($request->get('new_password')) {
            $user->password = bcrypt($request->get('new_password'));
        }
        $user->general_weight_units = $request->general_weight_units;
        $user->pedigree_number_generations = $request->pedigree_number_generations;
        $user->pedigree_rabbitry_information = $request->pedigree_rabbitry_information;
        $user->update();
        $user->updateBreadChains($request->breedchains);

        return response()->json(['success' => ['User has been updated']], 200);
    }

    public function updateSettingsLogo(Request $request)
    {
        $dir = public_path() . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'pedigree' . DIRECTORY_SEPARATOR;
        $user = $request->users;
        //dd($request->avatar->getClientOriginalName());
        //dd($request->users);

        if(substr($request->avatar->getMimeType(), 0, 5) == 'image') {
            //is image

            File::delete($dir. DIRECTORY_SEPARATOR . $user->pedigree_logo);
            $request->avatar->move($dir , $request->avatar->getClientOriginalName());
            $user->pedigree_logo = $request->avatar->getClientOriginalName();
            $user->save();
        }

        return response()->json(['success' => ['File has been updated'],'filename'=>$user->pedigree_logo], 200);
    }

    public function upcomingEvents(GetUpcomingRequest $request)
    {
        return auth()->user()->upcomingEvents()->take($request->get('count'))->get();
    }

    public function plans()
    {
        return auth()->user()->plans()->has('litters', '=', 0)->whereHas('events', function($query){
            $query->where('type', '=', 'litter')->where('holderName', null);
        })->get();
    }

    public function dashboard()
    {
        return response()->json([
            'breedersTotal'  => RabbitBreeder::count(),
            'littersTotal'  => Litter::count(),
            'kitsTotal'  => RabbitKit::count(),
        ], 200);
    }


    /**
     * When user clicks on events round in the right part of the schedule
     * @param Request $request
     * @return mixed
     */
    public function events(Request $request)
    {
        $user = Auth::user();
        if ($request->has('weekStart')){
            $data = $user->dateWeeklyEvents(Carbon::createFromFormat('m/d/Y',$request->get('weekStart')))->where('type','general')->get();
            return $data;
        }
        return $user->entireEvents;
    }
}
