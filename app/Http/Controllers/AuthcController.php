<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParentModel;

class AuthcController extends Controller
{
    use HttpResponses;

    public function loginc(Request $request)
    {
        // Récupération des données de la requête
        $code = $request->input('code');
        $cin = $request->input('cin');

        // Recherche de l'Abonne en fonction du code
        $Parent = ParentModel::where('cin', $cin)->where('code', $code)->first();

        // Vérification si l'Abonne existe
        if (!$Parent) {
            return $this->error('', 'Le code n\'existe pas', 404);
        }
        // Mettre à jour le champ 'active' à 1
        $Parent->update(['active' => 1]);
        // Retourne une réponse de succès avec les détails de l'Abonne
        return $this->success(
            [
                'success' => true,
                'message' => 'Sélection effectuée avec succès',

                'Parent' => $Parent,
            ],
            200
        );
    }
}
