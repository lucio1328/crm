<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function data(Request $request)
    {
         
        $perPage = $request->query('per_page', 10);

        return response()->json(Client::paginate($perPage));
    }

    public function nbdata()
    {
        return response()->json([
            "nb_clients" => Client::count()
        ]);
    }
}
