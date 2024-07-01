<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class  UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:4096',
            'phone_number' => 'sometimes|required|string|max:15',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 400,
            'message' => 'Erreur de validation',
            'errorsList' => $validator->errors()
        ]));
    }

    // public function messages()
    // {
    //     return[
    //         'user_id.required' => "Identifiant de l'utilisateur requis",
    //         'start_on.date' => "La date n'est pas au bon format JJ-MM-AAAA",
    //         'defaultSub_id.required' => "Souscription par défaut requise",
    //         // 'email.unique' => ' Cette addresse mail existe déjà',
    //         // 'password.required' => 'Le mot de passe est requis'

    //     ];
    // }
}
