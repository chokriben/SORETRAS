<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;





class StationFrontController extends Controller
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

    $Stations = Station::orderBy('created_at', 'desc')->take($limit)->get();

    return response()->json([
        'success' => true,
        'message' => 'Liste des stations  récupérée avec succès.',
        'Stations' => $Stations
    ], 200);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Station = Station::find($id);

        if (!$Station) {
            return response()->json([
                'success' => false,
                'message' => 'Actualité non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détails de l\'stations récupérés avec succès.',
            'Station' => $Station
        ], 200);
    }
}
