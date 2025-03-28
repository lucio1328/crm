<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    public function data(Request $request)
    {
        // Récupérer le nombre d'éléments par page (10 par défaut)
        $perPage = $request->query('per_page', 10);

        // Retourner les projets avec pagination
        return response()->json(Project::paginate($perPage));
    }

    public function nbdata()
    {
        return response()->json([
            "nb_projects" => Project::count()
        ]);
    }


    public function getProjectCountByStatus()
    {
        $allStatuses = Status::where('source_type', 'App\Models\Project')->get();
        
        $projectCounts = Project::select('status_id', \DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) {
                $status = Status::find($item->status_id);
                return [$status ? $status->title : 'Unknown' => $item->total];
            });

        foreach ($allStatuses as $status) {
            if (!isset($projectCounts[$status->title])) {
                $projectCounts[$status->title] = 0;
            }
        }

        return response()->json($projectCounts);
    }



}
