<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actualite;




class ActualiteFrontController extends Controller
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
public function show(Request $request)
{
    $limit = $request->input('limit', 3); // Valeur par défaut de la limite est 3

    $actualite = Actualite::orderBy('created_at', 'desc')->take($limit)->get();

    return response()->json([
        'success' => true,
        'message' => 'Liste des actualités récupérée avec succès.',
        'actualites' => $actualite
    ], 200);
}


public function index()
{
    $actualites = Actualite::all();

    return response()->json([
        'success' => true,
        'message' => 'Liste complète des actualités récupérée avec succès.',
        'actualites' => $actualites
    ], 200);
}



}
