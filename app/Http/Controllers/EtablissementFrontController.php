<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;




class EtablissementFrontController extends Controller
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
public function index()
{
    $etablissements = Etablissement::all();

    return response()->json([
        'success' => true,
        'message' => 'Sélection effectuée avec succès',
        'etablissements' => $etablissements,
    ]);
}

public function show()
{
    $etablissement = Etablissement::select('id', 'labelle')->get();

    return response()->json([
        'success' => true,
        'message' => 'Liste complète des etablissements récupérée avec succès.',
        'etablissement' => $etablissement
    ], 200);
}



}
