<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $legende = $request->query('legende'); // Get the 'legende' query parameter
        // $mediaId = $request->query('media_id'); // Get the 'media_id' query parameter

        /* Check if 'legende' and 'media_id' are provided
        if (!$legende || !$mediaId) {
            return response()->json([
                'success' => false,
                'message' => 'The "legende" and "media_id" query parameters are required.',
            ], 400);
        }
*/
        // Query the Media model with the provided 'legende' and 'media_id'
        //  $media = Media::where('legende', $legende)->where('id', $mediaId)->first();
        $media = Media::where('legende', 'medias')->get();


        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found for the given "legende" and "media_id".',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Selection est effectuée avec succès',
            'media' => $media,
        ], 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,png,jpeg,csv,txt,xls,xlsx,pdf|max:2048'
        ]);

        $medias = new Media();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = Str::random(10);
            $url = Storage::putFileAs('medias', $file, $name . '.' . $file->extension());



            //  $file_src = time() . '_' . $request->file('file')->getClientOriginalName();
            // $file_path = $request->file('file')->storeAs('uploads', $file_src, 'public');

            // Save the file path in the database or do any other necessary operations

            $medias->src  = $name . '.' . $file->extension();
            $medias->path = $url;
            $medias->legende = 'medias';
            $medias->save();

            // Return a response or redirect as needed
            return response()->json(['message' => 'File uploaded successfully']);
        }

        // Handle the case when no file is uploaded
        return response()->json(['message' => 'No file uploaded'], 400);
    }









    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $media = Media::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                'medias' => $media
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */ public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'src' => 'required|max:255',
            'nbr_vues' => 'required|integer',
            'legende' => 'required|max:255',
            'datetime' => 'required|date',
            'type' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        }

        $media = Media::findOrFail($request->id);
        $media->src = $request->src;
        $media->nbr_vues = $request->nbr_vues;
        $media->legende = $request->legende;
        $media->datetime = $request->datetime;
        $media->type = $request->type;
        $media->save();

        return response()->json(
            [
                "success" => true,
                "message" => "Modification est effectuée avec succès",
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $task = Media::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
