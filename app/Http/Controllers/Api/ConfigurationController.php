<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ConfigurationController extends Controller
{
    public function insert(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'remise_globale' => 'required|numeric|min:0|max:100',
            ]);

            $configuration = Configuration::create([
                'remise_globale' => $validated['remise_globale'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'message' => 'Configuration enregistrée avec succès.',
                'data' => $configuration
            ], 201);

        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation échouée',
                'details' => $e->errors()
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue lors de l\'enregistrement.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
