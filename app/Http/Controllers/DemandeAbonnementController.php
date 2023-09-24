<?php

namespace App\Http\Controllers;

use App\Models\DemandeAbonnement;
use App\Models\Media;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DemandeAbonnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $demande_abonnements = DemandeAbonnement::all();
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'demandes_abonnements' => $demande_abonnements
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'title' =>  'max:255',


        ]);
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $demande_abonnements = new DemandeAbonnement;
             //------------------------------(update champ )-------------------------
             $demande_abonnements->date_reception = $request->input('date_reception');
             $demande_abonnements->date_cmd = $request->input('date_cmd');
             $demande_abonnements->status = $request->input('status');
             $demande_abonnements->code_query = $request->input('code_query');
           //  $demande_abonnements->gare_id = $request->input('gare_id');
             $demande_abonnements->save();
             return response()->json(
                [
                    "success" => true,
                    "message" => "insertion est effectuée avec success",
                ],
                200
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $demande_abonnement = DemandeAbonnement::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
               'demande_abonnement' => $demande_abonnement
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $demandes_abonnements = DemandeAbonnement::findOrFail($request->id);
        $demandes_abonnements->date_reception = $request->date_reception;
        $demandes_abonnements->date_cmd = $request->date_cmd;
        $demandes_abonnements->status = $request->status;
        $demandes_abonnements->code_query = $request->code_query;
        $demandes_abonnements->save();
        return response()->json(
            [
                "success" => true,
                "message" => "Modification est effectuée avec success",
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $task = DemandeAbonnement::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
