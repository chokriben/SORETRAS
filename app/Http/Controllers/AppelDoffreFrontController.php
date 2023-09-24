<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppelDoffre;





class AppelDoffreFrontController extends Controller
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

    $AppelDoffres = AppelDoffre::orderBy('created_at', 'desc')->take($limit)->get();

    return response()->json([
        'success' => true,
        'message' => 'Liste des appel doffres récupérée avec succès.',
        'AppelDoffres' => $AppelDoffres
    ], 200);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $AppelDoffre = AppelDoffre::find($id);

        if (!$AppelDoffre) {
            return response()->json([
                'success' => false,
                'message' => 'appele offres non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détails de l\'appel offres récupérés avec succès.',
            'AppelDoffre' => $AppelDoffre
        ], 200);
    }
}
