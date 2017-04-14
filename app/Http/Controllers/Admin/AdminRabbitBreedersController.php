<?php

namespace App\Http\Controllers\Admin;

use App\Events\RabbitBreederWasArchived;
use App\Http\Requests\ArchiveRequest;
use App\Http\Requests\CreateRabbitBreederRequest;
use App\Http\Requests\UpdateRabbitBreederRequest;
use App\Jobs\CreateRabbitBreederJob;
use App\Jobs\UpdateRabbitBreederJob;
use App\Models\Filters\RabbitBreedersFilter;
use App\Models\RabbitBreeder;
use App\Repositories\RabbitBreederRepository;
use Carbon\Carbon;
use Collective\Bus\Dispatcher;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use App\Helpers\BaseIntEncoder;

class AdminRabbitBreedersController extends Controller
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * RabbitBreedersController constructor.
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->middleware('protect', ['only' => ['update', 'destroy','archive','show','getLitters','events']]);
    }

    public function index(RabbitBreedersFilter $filter, Requests\GetRabbitBreedersRequest $request)
    {
        $breeders = $filter->filter(auth()->user()->breeders()->with('user'), 'allBreeders', getenv('BREEDERS_PER_PAGE'));

        return response()->json(['breeders' => $breeders, 'order' => $request->get('order')]);
    }

    public function show(RabbitBreeder $breeder)
    {
        $breeder->load('father', 'mother','user');

        return $breeder;
    }

    public function store(CreateRabbitBreederRequest $request)
    {
        return $this->dispatcher->dispatchFrom(CreateRabbitBreederJob::class, $request);
    }

    public function update(RabbitBreeder $breeder, UpdateRabbitBreederRequest $request)
    {
        $request['breeder'] = $breeder;

        $updatedBreeder = $this->dispatcher->dispatchFrom(UpdateRabbitBreederJob::class, $request);
        $updatedBreeder->load('mother', 'father');

        return $updatedBreeder;
    }

    public function destroy(RabbitBreeder $breeder, RabbitBreederRepository $breeders)
    {
        $breeders->delete($breeder);
    }

    public function archive(RabbitBreeder $breeder, ArchiveRequest $request)
    {
        $breeder->archived = $request->get('archived');
        $breeder->update();
        event(new RabbitBreederWasArchived($breeder));
    }

    public function getList()
    {
        $breeders = auth()->user()->breeders()->where('archived','=',0)->select(['id', 'name', 'tattoo', 'cage', 'color', 'sex', 'breed', 'user_id'])->get();
        $males    = $breeders->where('sex', 'buck')->flatten();
        $females  = $breeders->where('sex', 'doe')->flatten();

        return response()->json([
            'bucks' => $males,
            'does'  => $females,
        ], 200);
    }

    public function getLitters(RabbitBreeder $breeder)
    {
        $litters = $breeder->litters()->with("parents")->orderBy('archived', 'ASC')->paginate(getenv('LITTERS_PER_PAGE'));
        foreach($litters as $litter) {
            $father = $litter->parents()->where('sex', '=', 'buck')->first();
            $litter->father_id = $father? $father->id : null;
            $mother = $litter->parents()->where('sex', '=', 'doe')->first();
            $litter->mother_id = $mother? $mother->id : null;
        }
        return $litters;
    }

    public function getPedigree(RabbitBreeder $breeder)
    {
        //dd($breeder->pedigree());
        return response()->json($breeder->pedigree());
    }

    public function checkId(Request $request)
    {
        $count = auth()->user()->breeders()->where('tattoo', '=', $request->get('id'))->count();
        return response()->json(['idDoubled' => (boolean)$count], 200);
    }

    public function events(RabbitBreeder $breeder, Request $request)
    {
        if ($request->has('weekStart'))
            return $breeder->dateWeeklyEvents(Carbon::createFromFormat('m/d/Y',$request->get('weekStart')))->get();
        return $breeder->futureEvents;
    }

    public function getPdf( Request $request){


        //dd(BaseIntEncoder::encode('1302')); //4eWKnk  4eWKnu 4eWKnz
        //dd(BaseIntEncoder::decode('pt'));

        $pedigree=RabbitBreeder::find($request->id)->pedigree();
        $directory = public_path() . DIRECTORY_SEPARATOR;
        $pdf = PDF::loadView('layouts.profile.pdf', compact('pedigree'), compact('directory'));
        return $pdf->download('Pedigree_Report_' . date('Y_m_d_H_i_s') . '.pdf');

    }
}
