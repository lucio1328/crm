<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function dataProjects(Request $request)
    {
        // Filtrer les résultats en fonction de la condition source_type=App\Models\Project
        $statuses = Status::where('source_type', 'App\\Models\\Project')->get();

        return response()->json($statuses);
    }


    public function dataTasks(Request $request)
    {
        // Filtrer les résultats en fonction de la condition source_type=App\Models\Project
        $statuses = Status::where('source_type', 'App\\Models\\Task')->get();

        return response()->json($statuses);
    }

}
