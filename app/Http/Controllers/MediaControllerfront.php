<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Auth;
use Illuminate\Support\Facades\Validator;

class MediaControllerfront extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type)
    {
        $medias = Media::where('type', '!=', $type)->get();
        return response()->json(
            [
                'success' => true,
                'message' => 'Sélection effectuée avec succès',
                'medias' => $medias
            ],
            200
        );
    }
}
