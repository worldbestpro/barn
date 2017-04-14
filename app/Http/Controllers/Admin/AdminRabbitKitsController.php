<?php

namespace App\Http\Controllers\Admin;

use App\Events\KitsWereButched;
use App\Events\KitWasDeleted;
use App\Events\KitWasDied;
use App\Events\KitWasArchived;
use App\Http\Requests\CreateRabbitKitRequest;
use App\Http\Requests\UpdateRabbitKitRequest;
use App\Http\Requests\WeighKitRequest;
use App\Jobs\CreateRabbitKitJob;
use App\Jobs\MakeRabbitBreederFromKitJob;
use App\Jobs\UpdateRabbitKitJob;
use App\Jobs\WeightKitJob;
use App\Models\Litter;
use App\Models\RabbitKit;
use App\Repositories\RabbitKitRepository;
use Collective\Bus\Dispatcher;
use Illuminate\Http\Request;
use App\Http\Requests\ArchiveRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminRabbitKitsController extends Controller
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * AdminRabbitKitsController constructor.
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->middleware('protect', ['except' => ['store','butch']]);
    }

    public function store(CreateRabbitKitRequest $request)
    {
        return $this->dispatcher->dispatchFrom(CreateRabbitKitJob::class, $request);
    }

    public function update(RabbitKit $kit, UpdateRabbitKitRequest $request)
    {
        $request['kit'] = $kit;

        return $this->dispatcher->dispatchFrom(UpdateRabbitKitJob::class, $request);

    }

    public function weigh(RabbitKit $kit, WeighKitRequest $request)
    {
        $request['kit'] = $kit;
        $this->dispatcher->dispatchFrom(WeightKitJob::class, $request);
    }

    public function died(RabbitKit $kit)
    {
        $kit->alive    = 0;
        $kit->survived = 0;
        $kit->update();
        event(new KitWasDied($kit, 'rabbit'));
    }

    public function archive(RabbitKit $kit, ArchiveRequest $request)
    {
        $kit->archived = $request->get('archived');
        $kit->update();
        event(new KitWasArchived($kit, 'rabbitkit'));
    }

    public function destroy(RabbitKit $kit)
    {
        $kit->delete();
        event(new KitWasDeleted($kit, 'rabbitkit'));
    }

    public function butch(Request $request, RabbitKitRepository $repo)
    {

        $kitsCollection = collect($request->get('kits'));
        $ids            = $kitsCollection->pluck('id')->toArray();
        if (count($ids)) {
            $kits = $repo->whereIn('id', $ids);

            foreach ($kits as $kit) {
                $kit->alive          = 0;
                $kit->survived       = 1;
                $kit->current_weight = $kitsCollection->where('id', $kit->id)->first()['current_weight'];
                $kit->update();
            }
            $litter = Litter::find($request->get('litter_id'));
            event(new KitsWereButched($litter, 'rabbit', count($ids),$request->get('date')));
        }

    }

    public function makeBreeder(RabbitKit $kit)
    {
        $kit->alive    = 0;
        $kit->survived = 1;
        $kit->improved = 1;
        $kit->update();
        $breeder = $this->dispatcher->dispatchFromArray(MakeRabbitBreederFromKitJob::class, $kit->toArray());

        return $breeder;
    }
}
