<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;





class PageFrontController extends Controller
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

        $Securites = Pages::orderBy('created_at', 'desc')->take($limit)->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des Securités récupérée avec succès.',
            'Pages' => $Securites
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Page = Pages::where('slug', 'like', $id)->first();

        if (!$Page) {
            return response()->json([
                'success' => false,
                'message' => 'Securité non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détails de page  sécurité récupérés avec succès.',
            'Securite' => $Page
        ], 200);
    }
}
