<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubscriptionRequest extends FormRequest
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
            'user_id' => 'required',
            'defaultSub_id' => 'required',
            'service_name',
            'logo',
            'amount' => 'required|numeric' ,
            'start_on' => 'required|Date',
            'end_on',
            'payment_method' => 'required|string',
            'cycle' => 'required|string',
            'plan_type' => 'required|string',
            'reminder' => 'required|integer',
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

    public function messages()
    {
        return[
            'user_id.required' => "Identifiant de l'utilisateur requis",
            'start_on.date' => "La date n'est pas au bon format JJ-MM-AAAA",
            'defaultSub_id.required' => "Souscription par défaut requise",
            // 'email.unique' => ' Cette addresse mail existe déjà',
            // 'password.required' => 'Le mot de passe est requis'

        ];
    }
}
