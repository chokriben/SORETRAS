<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\AbonnePrix;
use Illuminate\Support\Facades\Validator;

class AbonnePrixController extends Controller
{

    public function index(Request $request, $perPage = 10)
    {

        $abonnesprix = AbonnePrix::paginate($perPage);
        $abonnesprix->each(
            function ($abonnesprix, $key) {
            }
        );

        if ($request->page) {
            $abonnesprix = new LengthAwarePaginator($abonnesprix, count($abonnesprix), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'abonnesprix' => $abonnesprix,
            ],
            200
        );
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' =>  'max:255',


        ]);
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $abonnesprix = new AbonnePrix;

            //------------------------------(update champ )-------------------------
            $abonnesprix->prix = $request->input('prix');
            $abonnesprix->annuel = $request->input('annuel');
            $abonnesprix->klm = $request->input('klm');
            $abonnesprix->active = $request->input('active');
            $abonnesprix->vdf = $request->input('nom');
            $abonnesprix->code = $request->input('code');
            $abonnesprix->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "insertion est effectuée avec success",
                ],
                200
            );
        }
    }


    public function show(Request $request)
    {
        $tarif = AbonnePrix::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                'tarif' => $tarif
            ],
            200
        );
    }

    public function update(Request $request)
    {
        $abonnesprix = AbonnePrix::findOrFail($request->id);
        $abonnesprix->prix = $request->prix;
        $abonnesprix->prix_demi = $request->prix_demi;
        $abonnesprix->annuel = $request->annuel;
        $abonnesprix->klm = $request->klm;
        $abonnesprix->active = $request->active;
        $abonnesprix->vdf = $request->vdf;
        $abonnesprix->code = $request->code;

        $abonnesprix->save();
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
        $task = AbonnePrix::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
