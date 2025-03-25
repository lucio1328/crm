<?php
namespace App\Services\Statistique;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class Statistique
{
    public function getRevenu()
    {
        return DB::table('invoices')
            ->selectRaw('YEAR(sent_at) as annee, MONTH(sent_at) as mois, COUNT(*) as factures')
            ->groupByRaw('YEAR(sent_at), MONTH(sent_at)')
            ->get()
            ->map(function ($facture) {
                $paiement = DB::table('payments')
                    ->whereYear('payment_date', $facture->annee)
                    ->whereMonth('payment_date', $facture->mois)
                    ->count();

                return [
                    'annee' => $facture->annee,
                    'mois' => $facture->mois,
                    'factures' => $facture->factures,
                    'paiements' => $paiement
                ];
            });
    }
}

