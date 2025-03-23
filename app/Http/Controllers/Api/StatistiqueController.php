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
use App\Services\Statistique\Statistique;

class StatistiqueController extends Controller
{
    protected $statistiqueService;

    public function __construct(Statistique $statistiqueService)
    {
        $this->statistiqueService = $statistiqueService;
    }

    public function index(): JsonResponse
    {
        $data = [
            'clients' => Client::count(),
            'projets' => Project::count(),
            'taches' => Task::count(),
            'offres' => Offer::count(),
            'factures' => Invoice::count(),
            'paiements' => Payment::count(),
            'revenus_mensuels' => $this->statistiqueService->getRevenu(),
        ];

        return response()->json($data);
    }
}
