<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Media;
use Auth;
use Illuminate\Support\Facades\Validator;


class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();
    
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'reservations' => $reservations
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
            $reservations = new Reservation;

            //------------------------------(update champ )-------------------------
            $reservations->cin = $request->input('cin');
            $reservations->nom = $request->input('nom');
            $reservations->email = $request->input('email');
            $reservations->date_reservation = $request->input('date_reservation');
            $reservations->depart = $request->input('depart');
            $reservations->destination = $request->input('destination');
            $reservations->save();
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
        $reservation = Reservation::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                'reservation' => $reservation
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $reservations = Reservation::findOrFail($request->id);
        $reservations->cin = $request->cin;
        $reservations->nom = $request->nom;
        $reservations->email = $request->email;
        $reservations->date_reservation = $request->date_reservation;
        $reservations->depart = $request->depart;
        $reservations->destination = $request->destination;
        $reservations->save();
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
    public function destroy( Request $request)
    {
        $task = Reservation::findOrFail($request->id)->delete();
        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}