<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class StockController extends Controller
{
  public function index()
{
    $perPage = 100; // Nombre d'éléments par page, vous pouvez ajuster ceci en fonction de vos besoins.
    $stocks = Stock::paginate($perPage);

    return response()->json([
        'success' => true,
        'message' => 'Sélection effectuée avec succès',
        'stocks' => $stocks,
    ], 200);
}

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_debut' => 'required',
            'code_fin' => 'required',
            'code_actuel' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errorsValidation' => $validator->messages(),
            ], 400);
        } else {
            $stock = new Stock;
            $stock->code_debut = $request->input('code_debut');
            $stock->code_fin = $request->input('code_fin');
            $stock->code_actuel = $request->input('code_actuel');
            $stock->user_id = $request->input('user_id');
            
            // Utilisez Carbon pour obtenir la date actuelle au format 'Y-m-d H:i:s'
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            
            // Affectez la date actuelle à la colonne 'created_at'
            $stock->created_at = $currentDateTime;
            
            // Affectez également la date actuelle à la colonne 'updated_at'
            $stock->updated_at = $currentDateTime;

            $stock->save();

            return response()->json([
                'success' => true,
                'message' => 'Insertion effectuée avec succès',
            ], 200);
        }
    }

    public function show($id)
    {
        $stock = Stock::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Sélection effectuée avec succès',
            'stock' => $stock,
        ], 200);
    }

 public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $stock->code_debut = $request->input('code_debut');
        $stock->code_fin = $request->input('code_fin');
        $stock->code_actuel = $request->input('code_actuel');
        $stock->user_id = $request->input('user_id');
      
        // Utilisez Carbon pour obtenir la date actuelle au format 'Y-m-d H:i:s'
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
        
        // Affectez la date actuelle à la colonne 'updated_at'
        $stock->updated_at = $currentDateTime;
        
        $stock->save();

        return response()->json([
            'success' => true,
            'message' => 'Modification effectuée avec succès',
        ], 200);
    }
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return response()->json([
            'success' => true,
            'message' => 'Suppression effectuée avec succès',
        ], 200);
    }
}
