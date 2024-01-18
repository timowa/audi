<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(){
        $models = CarModel::with('generations')->get();
        return view('index',compact('models'));
    }
}
