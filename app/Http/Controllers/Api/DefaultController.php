<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    function index(){
        return response()->json(['message' => 'Connexion réussie',]);

    }
}
