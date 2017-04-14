<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\RabbitBreeder;
use Illuminate\Http\Request;
use App\Helpers\BaseIntEncoder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cache = false;
        $user = auth()->user();
        return view('app', ['user'=>$user]);
    }

    public function external(Request $request){
        if($breeder = RabbitBreeder::find(BaseIntEncoder::decode($request->id))){
            $pedigree = $breeder->pedigree();
            return view('layouts.profile.public')->with(compact('pedigree'));
        }else{
            return view('layouts.profile.public_not_found');
        }


    }


}
