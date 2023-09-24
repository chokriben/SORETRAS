<?php

namespace App\Http\Controllers;


use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\Media;
use Auth;
use Illuminate\Support\Facades\Validator;
class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::all();
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'commandes' => $commandes
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
           // 'title' =>  'max:255',


        ]);
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $commandes = new Commande;

            //------------------------------(update champ )-------------------------
            $commandes->num_commande = $request->input('num_commande');
            
            $commandes->save();
            
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
        $commande = Commande::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                'commande' => $commande
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $commandes = Commande::findOrFail($request->id);
        $commandes->num_commande = $request->num_commande;
        $commandes->save();
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
        $task = Commande::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
