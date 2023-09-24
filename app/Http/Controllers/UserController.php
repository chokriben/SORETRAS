<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Gare;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 100)
    {

        $users = User::whereNull('gare_id')->paginate($perPage);

        if ($request->page) {
            $users = new LengthAwarePaginator($users, count($users), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'users' => $users,
            ],
            200
        );
    }

    public function list(Request $request, $perPage = 100)
    {
        $query = User::query()
            ->join('gares', 'users.gare_id', '=', 'gares.id')
            ->leftJoin('gare_translations', function ($join) {
                $join->on('gare_translations.gare_id', '=', 'gares.id')
                    ->where('locale', '=', 'fr');
            })
            ->select('users.*', 'users.name as nom', 'gares.*', 'gare_translations.*');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($innerQuery) use ($searchTerm) {
                $innerQuery->where('users.column_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('gares.column_name', 'LIKE', '%' . $searchTerm . '%');
                // Ajoutez d'autres colonnes si vous voulez rechercher dans les colonnes de gare_translations
            });
        }
        $users = $query->paginate($perPage);
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'users' => $users,
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

            'email' =>  'max:255|required',
            'password' => 'max:255|required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $User = new User();
            $User->name = $request->input('name');
            $User->email = $request->input('email');
            $User->password = Hash::make($request->input('password'));
            $User->gare_id = $request->input('gare_id');
            $User->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Actualites/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $User->medias()->save($media);
            }

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
        $user = User::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "user" => $user,
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'max:255|required',
            // 'password' => 'max:255|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $user = User::findOrFail($request->id);

            // Vérification de l'ancien mot de passe
            $oldPassword = $request->input('old_password');
            if (!empty($oldPassword) && !Hash::check($oldPassword, $user->password)) {
                return response()->json([
                    "success" => false,
                    "message" => "Ancien mot de passe incorrect",
                ], 400);
            }

            // Update only if the field exists in the request
            if ($request->has('name')) {
                $user->name = $request->input('name');
            }
            $user->email = $request->input('email');

            // Vérifier si un nouveau mot de passe est fourni
            $newPassword = $request->input('new_password');
            if (!empty($newPassword)) {
                $user->password = Hash::make($newPassword);
            }

            $user->gare_id = $request->input('gare_id');
            $user->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Modification est effectuée avec succès",
                    "new_password" => $newPassword, // Ajout de cette ligne
                ],
                200
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        User::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
