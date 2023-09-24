<?php

namespace App\Http\Controllers;

use App\Models\EmailsNewsletter;
use Illuminate\Http\Request;
use App\Models\Media;
use Auth;
use Illuminate\Support\Facades\Validator;

class EmailsNewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newslettres = EmailsNewsletter::all();
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'newslettres' => $newslettres
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
        $newslettres = new EmailsNewsletter;
        $newslettres->email = $request->input('email');
        $newslettres->status = $request->input('status');
        $newslettres->save();
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
        $newslettres = EmailsNewsletter::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                'newslettres' => $newslettres
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $newslettres = EmailsNewsletter::findOrFail($request->id);
        $newslettres->email = $request->email;
        $newslettres->status = $request->status;
        $newslettres->save();
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
        $task = EmailsNewsletter::findOrFail($request->id)->delete();
   
        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        ); 
    }
}