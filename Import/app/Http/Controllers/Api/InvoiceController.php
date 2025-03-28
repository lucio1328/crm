<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Invoice\InvoiceCalculator;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function data(Request $request)
    {
        // Récupérer le nombre d'éléments par page (10 par défaut)
        $perPage = $request->query('per_page', 10);

        return response()->json(Invoice::paginate($perPage));
    }

    public function nbdata()
    {
        return response()->json([
            "nb_invoices" => Invoice::count()
        ]);
    }

    public function invoicePaymentSummary($annee = null, $mois = null)
    {
        $totalPaid = 0;
        $totalUnpaid = 0;

        // Si aucune année n'est fournie, utilisez l'année actuelle
        if (!$annee) {
            $annee = date('Y');
        }

        // Si aucun mois n'est fourni, utilisez le mois actuel
        if (!$mois) {
            $mois = date('m');
        }

        // Filtrer les factures en fonction de l'année et du mois
        $invoices = Invoice::whereYear('created_at', $annee)
                        ->whereMonth('created_at', $mois)
                    ->get();

        foreach ($invoices as $invoice) {
            $invoiceCalculator = new InvoiceCalculator($invoice);
            $amountDue = $invoiceCalculator->getAmountDue(); 
            $totalPayments = $invoice->payments()->sum('amount');
            $totalPaid += $totalPayments;
            if ($amountDue->getBigDecimalAmount() != 0) {
                $totalUnpaid += $amountDue->getBigDecimalAmount(); 
            } 
        }

        return response()->json([
            'total_paid' => $totalPaid/100,
            'total_unpaid' => $totalUnpaid,
        ]);
    }



    public function sumInvoice(){
        $invoices=Invoice::all();
        $sumInvoice=0;
        foreach ($invoices as $invoice) {
            $invoiceCalculator = new InvoiceCalculator($invoice);
            $sumInvoice+= $invoiceCalculator->getTotalPrice()->getBigDecimalAmount(); 
    
        }
        return response()->json([
            'sum_invoice'=> $sumInvoice
            ]);
    }



    


}
