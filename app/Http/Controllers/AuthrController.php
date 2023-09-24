<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Abonne;

class AuthrController extends Controller
{
    use HttpResponses;

    public function loginr(Request $request)
    {
        // Récupération des données de la requête
        $code = $request->input('code');

        // Recherche de l'Abonne en fonction du code
        $Abonne = Abonne::where('code', $code)->first();

        // Vérification si l'Abonne existe
        if (!$Abonne) {
            return $this->error('', 'Le code n\'existe pas', 404);
        }

        // Retourne une réponse de succès avec les détails de l'Abonne
        return $this->success([
            'success' => true,
            'message' => 'Sélection effectuée avec succès',

            'abonne' => $Abonne,
        ],
        200
    );
    }
}
