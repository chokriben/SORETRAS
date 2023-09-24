<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qualite;





class QualiteFrontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  /**
 * Display a listing of the resource.
 */
/**
 * Display a listing of the resource.
 */
public function index(Request $request)
{
    $limit = $request->input('limit', 3); // Valeur par défaut de la limite est 3

    $Qualites = Qualite::orderBy('created_at', 'desc')->take($limit)->get();

    return response()->json([
        'success' => true,
        'message' => 'Liste des qualités récupérée avec succès.',
        'Qualites' => $Qualites
    ], 200);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Qualite = Qualite::find($id);

        if (!$Qualite) {
            return response()->json([
                'success' => false,
                'message' => 'Qualités non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détails de l\'qualité récupérés avec succès.',
            'Qualite' => $Qualite
        ], 200);
    }
}
