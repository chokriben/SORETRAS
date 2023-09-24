<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;





class ContactFrontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  /**
 * Display a listing of the resource.
 */
/**
 * Display a listing of the resource.
 */
public function index(Request $request)
{
    $limit = $request->input('limit', 3); // Valeur par défaut de la limite est 3

    $contacts = Contact::orderBy('created_at', 'desc')->take($limit)->get();

    return response()->json([
        'success' => true,
        'message' => 'Liste des actualités récupérée avec succès.',
        'contacts' => $contacts
    ], 200);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Contact = Contact::find($id);

        if (!$Contact) {
            return response()->json([
                'success' => false,
                'message' => 'Actualité non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Détails de l\'actualité récupérés avec succès.',
            'Contact' => $Contact
        ], 200);
    }
}
