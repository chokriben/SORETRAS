<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\Horaire;
use Illuminate\Support\Facades\Validator;

class HoraireController  extends Controller
{

    public function index(Request $request, $perPage = 10)
    {

        $horaires = Horaire::paginate($perPage);
        $horaires->each(
            function ($horaires, $key) {
            }
        );

        if ($request->page) {
            $horaires = new LengthAwarePaginator($horaires, count($horaires), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'abonnesprix' =>  $horaires,
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
            $horaires = new Horaire;

            //------------------------------(update champ )-------------------------
            $horaires->depart = $request->input('depart');
            $horaires->arrive = $request->input('arrive');
            $horaires->h_depart = $request->input('h_depart');
            $horaires->h_arrive = $request->input('h_arrive');

            $horaires->save();

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
        $tarif = Horaire::findOrFail($request->id);
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
        $horaires = Horaire::findOrFail($request->id);
        $horaires->depart = $request->depart;
        $horaires->arrive = $request->arrive;
        $horaires->h_depart = $request->h_depart;
        $horaires->h_arrive = $request->h_arrive;
        $horaires->active = $request->active;


        $horaires->save();
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
        $task = Horaire::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
