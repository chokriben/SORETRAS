<?php

namespace App\Http\Requests;

use App\Models\ParentModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class LoginParentModelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'cin' => ['required', 'string', new CINRule],
            'password' => ['required', 'string', 'min:6'],
        ];
    }
    
}

class CINRule implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^\d{8}$/', $value);
    }

    public function message()
    {
        return 'The :attribute is not a valid CIN.';
    }
}

