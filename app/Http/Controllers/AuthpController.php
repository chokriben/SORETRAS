<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;

use App\Http\Requests\LoginParentModelRequest;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Http\Requests\StoreParentModelRequest;

class AuthpController extends Controller
{
    use HttpResponses;
 
    public function loginp(LoginParentModelRequest $request)
    {
        $request->validated($request->only(['cin', 'password']));
    
        $parent = ParentModel::where('cin', $request->cin)->first();
    
        if(!$parent) {
            return $this->error('', 'CIN does not exist', 404);
        }
    
        // if (!Auth::attempt($request->only(['cin', 'password']))) {
        //     return $this->error('', 'Credentials do not match', 401);
        // }
    
        
    if (!Hash::check($request->password, $parent->password)) {
        return $this->error('', 'Invalid password', 401);
    }
    
        return $this->success([
            'parent' => $parent,
            'token' => $parent->createToken('API Token')->plainTextToken
        ]);
    }
    

    public function register(StoreParentModelRequest $request)
    {
        $request->validated($request->only(['name', 'cin', 'password']));

        $ParentModel = ParentModel::create([
            'name' => $request->name,
            'cin' => $request->cin,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'ParentModel' => $ParentModel,
            'token' => $ParentModel->createToken('API Token')->plainTextToken
        ]);
    }


    public function logoutp()
    {
        Auth::ParentModel()->currentAccessToken()->delete();
       //Authp()->logout();
        return $this->success([
            'message' => 'Vous avez été déconnecté avec succès'
        ]);
    }
}