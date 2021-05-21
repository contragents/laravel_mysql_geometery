<?php

namespace App\Http\Controllers;
use App\Models\User;
use http\Env\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController
{
    public function show(){
        return view('welcome');
    }

    public function submit(\Illuminate\Http\Request $request){
        $objects = User::findNears($request->lat, $request->lng, $request->radiusKM, $request->numResults);

        return view('welcome',compact('objects', 'request'));
    }
}
