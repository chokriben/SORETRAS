<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


class LigneFrontstation extends Controller
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
        $results = DB::table('ligne_station as l')
            ->select('l.id', 'lig.cod', 'l.gare_id', 'l.station_dep_id', 'l.station_fin_id', 's.id as station_id', 'st.name as station_name', 'sf.id as fin_station_id', 'star.name as fin_station_name')
            ->join('stations as s', 'l.station_dep_id', '=', 's.id')
            ->join('lignes as lig', 'l.ligne_id', '=', 'lig.id')
            ->join('stations as sf', 'l.station_fin_id', '=', 'sf.id')
            ->join('station_translations as st', 's.id', '=', 'st.station_id')
            ->join('station_translations as star', 'sf.id', '=', 'star.station_id')

            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Sélection effectuée avec succès',
            'Lignes' => $results,
        ]);
    }

    public function show($id)
    {
        $results = DB::table('ligne_station')
            ->select('ligne_id')
            ->where('id', '=', $id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Sélection effectuée avec succès',
            'Lignes' => $results,
        ]);
    }
}
