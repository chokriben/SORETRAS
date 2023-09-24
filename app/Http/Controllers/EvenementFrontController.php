<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evenement;





class EvenementFrontController extends Controller
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
    public function show()
    {
        $limit = 3; // Valeur par défaut de la limite est 3

        $evenements = Evenement::orderBy('created_at', 'desc')->take($limit)->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des evenements récupérée avec succès.',
            'Evenements' => $evenements
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function index()
    {

        $evenements = Evenement::all();

        return response()->json([
            'success' => true,
            'message' => 'Liste complète des actualités récupérée avec succès.',
            'actualites' => $evenements
        ], 200);
    }
}
