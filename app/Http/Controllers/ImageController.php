<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('image');
        // Vérification du type de fichier
        if ($file->getClientOriginalExtension() !== 'jpg' && $file->getClientOriginalExtension() !== 'jpeg' && $file->getClientOriginalExtension() !== 'png' && $file->getClientOriginalExtension() !== 'gif') {
            return response()->json(['error' => 'Le fichier doit être une image (jpg, jpeg, png ou gif)'], 400);
        }
        $name = Str::random(10);
        $url = Storage::putFileAs('uploads', $file, $name . time() . '.' . $file->extension());
        return [
            'url' => $url
        ];
    }
}
