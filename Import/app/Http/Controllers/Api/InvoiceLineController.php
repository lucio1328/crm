<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvoiceLine;
use Illuminate\Http\Request;

class InvoiceLineController extends Controller
{
    public function data(Request $request)
    {
        // Récupérer le nombre d'éléments par page (10 par défaut)
        $perPage = $request->query('per_page', 10);

        return response()->json(InvoiceLine::paginate($perPage));
    }

    public function nbdata()
    {
        return response()->json([
            "nb_invoice_lines" => InvoiceLine::count()
        ]);
    }
}
