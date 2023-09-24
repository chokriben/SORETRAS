<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use Illuminate\Http\Request;
use App\Models\Media;
use Auth;
use Illuminate\Support\Facades\Validator;

class AbonnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $abonnements = Abonnement::all();
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'abonnements' => $abonnements
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
            $abonnements = new Abonnement;

            //------------------------------(update champ )-------------------------
            $abonnements->reference = $request->input('reference');
            $abonnements->cin = $request->input('cin');
            $abonnements->code_a_bare = $request->input('code_a_bare');
            $abonnements->date_debut = $request->input('date_debut');
            $abonnements->date_fin = $request->input('date_fin');
            $abonnements->tarif = $request->input('tarif');
            $abonnements->is_vdf = $request->input('is_vdf');
            $abonnements->is_free = $request->input('is_free');
            $abonnements->date_reception = $request->input('date_reception');
            $abonnements->status = $request->input('status');
            $abonnements->save();
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
        $abonnements = Abonnement::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                'abonnements' => $abonnements
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $abonnements = Abonnement::findOrFail($request->id);
        $abonnements->reference = $request->reference;
        $abonnements->cin = $request->cin;
        $abonnements->code_a_bare = $request->code_a_bare;
        $abonnements->date_debut = $request->date_debut;
        $abonnements->date_fin = $request->date_fin;
        $abonnements->tarif = $request->tarif;
        $abonnements->is_vdf = $request->is_vdf;
        $abonnements->is_free = $request->is_free;
        $abonnements->date_reception = $request->date_reception;
        $abonnements->status = $request->status;
        $abonnements->save();
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
        $task = Abonnement::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
