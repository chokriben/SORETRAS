<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recrutement;





class RecrutementFrontController extends Controller
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

    $Recrutements = Recrutement::orderBy('created_at', 'desc')->take($limit)->get();

    return response()->json([
        'success' => true,
        'message' => 'Liste des recrutements récupérée avec succès.',
        'Recrutements' => $Recrutements
    ], 200);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Recrutement = Recrutement::find($id);

        if (!$Recrutement) {
            return response()->json([
                'success' => false,
                'message' => 'recrutement non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détails de l\'recrutement récupérés avec succès.',
            'Recrutement' => $Recrutement
        ], 200);
    }
}
