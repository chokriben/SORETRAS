<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presentation;





class PresentationFrontController extends Controller
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

    $Presentations = Presentation::orderBy('created_at', 'desc')->take($limit)->get();

    return response()->json([
        'success' => true,
        'message' => 'Liste des presentations récupérée avec succès.',
        'Presentations' => $Presentations
    ], 200);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Presentation = Presentation::find($id);

        if (!$Presentation) {
            return response()->json([
                'success' => false,
                'message' => 'presentation non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détails de l\'presentation récupérés avec succès.',
            'Presentation' => $Presentation
        ], 200);
    }
}
