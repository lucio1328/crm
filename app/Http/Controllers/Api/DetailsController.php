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
        $tasks = Task::with(['user', 'status', 'project'])
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
        $offers = Offer::with(['client:id,company_name', 'invoiceLines'])
        ->get()
        ->map(function ($offer) {
            return [
                'id' => $offer->id,
                'external_id' => $offer->external_id,
                'client' => $offer->client ? $offer->client->company_name : 'N/A',
                'status' => $offer->status,
                'created_at' => $offer->created_at ? $offer->created_at->format('d, F Y') : '',
                'price' => $offer->invoiceLines->sum(function ($line) {
                    return ($line->price * $line->quantity) / 100;
                })
            ];
        });
        return response()->json($offers);
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
