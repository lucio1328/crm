<?php
namespace App\Services\Invoice;

use App\Models\Invoice;
use App\Services\Invoice\InvoiceCalculator;
use Illuminate\Http\JsonResponse;

class Util {
    public function sommeInvoice() {
        $totalSum = 0;
        Invoice::chunk(100, function ($invoices) use (&$totalSum) {
            foreach ($invoices as $invoice) {
                $invoiceCalculator = new InvoiceCalculator($invoice);
                $totalSum += $invoiceCalculator->getTotalPrice()->getBigDecimalAmount();
            }
        });
        return $totalSum;
    }
}