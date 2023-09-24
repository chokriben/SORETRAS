<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;;

use App\Models\Ligne;




class LigneFrontController extends Controller
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
        $Lignes = Ligne::all();

        return response()->json([
            'success' => true,
            'message' => 'Sélection effectuée avec succès',
            'Lignes' => $Lignes,
        ]);
    }

    public function show($id)
    {
        $Ligne = Ligne::select('id', 'cod')->where('id', '=', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste complète des Lignes récupérée avec succès.',
            'Ligne' => $Ligne
        ], 200);
    }
}
