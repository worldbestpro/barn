<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Collective\Bus\Dispatcher;
use App\Models\Pedigree;
use App\Http\Requests\UpdatePedigreeRequest;
use App\Jobs\UpdatePedigreeJob;

class AdminPedigreesController extends Controller
{
    //
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

    public function show(Pedigree $pedigree)
    {
        //$breeder->load('father', 'mother','user');
        return $pedigree;
    }

    public function update(Pedigree $pedigree, UpdatePedigreeRequest $request)
    {
        $request['pedigree'] = $pedigree;

        $updatedBreeder = $this->dispatcher->dispatchFrom(UpdatePedigreeJob::class, $request);
        //$updatedBreeder->load('mother', 'father');

        return $updatedBreeder;
    }
}
