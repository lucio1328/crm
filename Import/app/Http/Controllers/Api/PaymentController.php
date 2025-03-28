<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Invoice\GenerateInvoiceStatus;
use App\Services\Invoice\InvoiceCalculator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function data(Request $request)
    {
        
        $perPage = $request->query('per_page', 10);

        return response()->json(Payment::paginate($perPage));
    }

    public function nbdata()
    {
        return response()->json([
            "nb_payments" => Payment::count()
        ]);
    }

    public function sumpayment()
    {
        return response()->json([
            "sum_payments" =>doubleval( Payment::sum("amount"))/100
        ]);
    }



    public function monthlyRevenueChart()
    {
        $currentDate = Carbon::now();
    
        $revenueData = [];   
        for ($i = 0; $i < 12; $i++) {
            
            $monthStart = $currentDate->copy()->startOfMonth()->toDateString();  
            $monthEnd = $currentDate->copy()->endOfMonth()->toDateString();

    
            // return response()->json($monthStart);
            $monthKey =$currentDate->copy()->startOfMonth()->format('F Y');  
    
            
            $revenue = Payment::whereBetween('payment_date', [$monthStart, $monthEnd])
                            ->sum('amount')/100;   
    
             
            $revenueData[$monthKey] = intval($revenue);
    
             
            $currentDate->subMonth();
        }
    
        $revenueData = array_reverse($revenueData);
        return response()->json($revenueData);
    }
    


    public function updateAmount(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0', 
        ]);
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'message' => 'Payment not found'
            ], 404);
        }
        $invoice=Invoice::find($payment->invoice_id);
        $invoiceCalculator = new InvoiceCalculator($invoice);
        $totalPrice = $invoiceCalculator->getTotalPrice();
        $subPrice = $invoiceCalculator->getSubTotal();
        $vatPrice = $invoiceCalculator->getVatTotal();
        $amountDue = $invoiceCalculator->getAmountDue();
        

        $payment->amount = $validated['amount'] * 100; 
        // $payment->updated_at=Carbon::now();
        $payment->update();

        return response()->json([
            'message' => 'Payment amount updated successfully',
            'payment' => $payment
        ]);
    }
    

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0',
            ]);

            $payment = Payment::find($id);

            if (!$payment) {
                return response()->json(['error' => 'Paiement introuvable'], 404);
            }

            $invoice = Invoice::find($payment->invoice_id);

            if (!$invoice) {
                return response()->json(['error' => 'Facture introuvable'], 404);
            }

            if (!$invoice->isSent()) {
                return response()->json([
                    'error' => "Impossible de modifier un paiement sur une facture non envoyée."
                ], 400);
            }

            $totalPaid = Payment::where('invoice_id', $invoice->id)
                            ->where('id', '!=', $id)
                            ->whereNull('deleted_at')
                            ->sum('amount');

            $invoiceCalculator = new InvoiceCalculator($invoice);
            $invoiceTotal = $invoiceCalculator->getTotalPrice()->getAmount();

            $newAmount = $request->input('amount') * 100;

            if (($totalPaid + $newAmount) > $invoiceTotal) {
                return response()->json([
                    'error' => sprintf(
                        "Le montant du paiement (%.2f) depasse le solde restant a payer.",
                        $newAmount / 100
                    )
                ], 400);
            }
            
            $payment->amount = $newAmount;
            $payment->save();
            app(GenerateInvoiceStatus::class, ['invoice' => $invoice])->createStatus();

            return response()->json(['message' => 'Paiement mis à jour avec succès'], 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deletePayment(Request $request, $id)
    {
        
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'message' => 'Payment not found'
            ], 404);
        }
        $invoice_id=$payment->invoice_id;
        $payment->delete();
        
        $invoice = Invoice::find($invoice_id);
            app(GenerateInvoiceStatus::class, ['invoice' => $invoice])->createStatus();

        return response()->json([
            'message' => 'Payment amount deleted successfully'
        ]);
    }


}
