<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Payment;
use App\Models\Integration;

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
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Paiement introuvable'], 404);
        }

        if (!auth()->user()->can('payment-update')) {
            return response()->json(['message' => "Vous n'avez pas la permission de modifier ce paiement"], 403);
        }

        $invoice = Invoice::find($payment->invoice_id);

        if (!$invoice) {
            return response()->json(['message' => 'Facture introuvable'], 404);
        }

        $invoiceCalculator = new InvoiceCalculator($invoice);

        $totalPaid = Payment::where('invoice_id', $invoice->id)
                        ->where('id', '!=', $id)
                        ->whereNull('deleted_at')
                        ->sum('amount');

        $invoiceTotal = $invoiceCalculator->getTotalPrice();
        $newAmount = $request->input('amount');

        if (($totalPaid + $newAmount) > $invoiceTotal) {
            return response()->json([
                'message' => "Le montant total des paiements dépasse le montant à payer de la facture."
            ], 400);
        }

        $payment->amount = $newAmount;
        $payment->save();

        return response()->json(['message' => 'Paiement mis à jour avec succès'], 200);
    }

}
