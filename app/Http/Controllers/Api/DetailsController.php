<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\Offer;
use App\Models\Invoice;
use App\Models\Payment;

class DetailsController extends Controller
{
    public function clients(): JsonResponse
    {
        return response()->json(Client::all());
    }

    public function projets(): JsonResponse
    {
        $projects = Project::with(['assignee', 'status', 'client'])
            ->get()
            ->map(function ($project) {
                return [
                    'external_id' => $project->external_id,
                    'title' => $project->title,
                    'created_at' => $project->created_at ? $project->created_at->format('Y-m-d') : '',
                    'deadline' => $project->deadline ? $project->deadline->format('Y-m-d') : '',
                    'client' => $project->client ? $project->client->company_name : 'N/A',
                    'assignee' => $project->assignee ? $project->assignee->name : 'N/A',
                    'status' => [
                        'title' => $project->status->title,
                        'color' => $project->status->color
                    ]
                ];
            });

        return response()->json($projects);
    }

    public function taches(): JsonResponse
    {
        $tasks = Task::with(['assignee', 'status', 'project'])
            ->get()
            ->map(function ($task) {
                return [
                    'external_id' => $task->external_id,
                    'title' => $task->title,
                    'created_at' => $task->created_at ? $task->created_at->format('Y-m-d') : '',
                    'deadline' => $task->deadline ? $task->deadline->format('Y-m-d') : '',
                    'project' => $task->project ? $task->project->title : 'N/A',
                    'assignee' => $task->assignee ? $task->assignee->name : 'N/A',
                    'status' => [
                        'title' => $task->status->title,
                        'color' => $task->status->color
                    ]
                ];
            });

        return response()->json($tasks);
    }

    public function offres(): JsonResponse
    {
        return response()->json(Offer::all());
    }

    public function factures(): JsonResponse
    {
        return response()->json(Invoice::all());
    }

    public function paiements(): JsonResponse
    {
        return response()->json(Payment::all());
    }

}
