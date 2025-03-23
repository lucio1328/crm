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

    public function update($id): JsonResponse
    {

    }

}
