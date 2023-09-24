<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getToken(Request $request)
    {
        $token = $request->session()->token();
        $csrfToken = csrf_token();

        // Votre logique ici (par exemple, retourner le jeton CSRF en réponse JSON).
        return response()->json(['csrf_token' => $csrfToken]);
    }
}
