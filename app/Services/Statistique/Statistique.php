<?php
namespace App\Services\Statistique;

use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class Statistique
{
    public function getRevenu()
    {
        return Payment::selectRaw('YEAR(payment_date) as annee, MONTH(payment_date) as mois, SUM(amount) as total')
            ->groupByRaw('YEAR(payment_date), MONTH(payment_date)')
            ->orderByRaw('YEAR(payment_date) DESC, MONTH(payment_date) DESC')
            ->get();
    }
}
