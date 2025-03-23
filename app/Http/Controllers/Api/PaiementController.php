<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Payment;
use App\Models\Integration;
use App\Models\Configuration;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\Invoice\InvoiceCalculator;

class PaiementController extends Controller
{
    public function destroy($id): JsonResponse
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Paiement introuvable'], 404);
        }

        if (!auth()->user()->can('payment-delete')) {
            return response()->json(['message' => "Vous n'avez pas la permission de supprimer ce paiement"], 403);
        }

        $api = Integration::initBillingIntegration();
        if ($api) {
            $deletedFromApi = $api->deletePayment($payment);
            if (!$deletedFromApi) {
                return response()->json(['message' => "Erreur lors de la suppression dans l'intégration externe"], 500);
            }
        }

        $payment->delete();

        return response()->json(['message' => 'Paiement supprimé avec succès'], 200);
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

            return response()->json(['message' => 'Paiement mis à jour avec succès'], 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
