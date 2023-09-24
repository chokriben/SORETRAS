<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunicationMedia;





class CommunicationMediaControllerfront extends Controller
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

        $Comm = CommunicationMedia::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des appel doffres récupérée avec succès.',
            'Comm' => $Comm
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Comm = CommunicationMedia::find($id);

        if (!$Comm) {
            return response()->json([
                'success' => false,
                'message' => 'appele offres non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détails de l\'appel offres récupérés avec succès.',
            'Comm' => $Comm
        ], 200);
    }
}
