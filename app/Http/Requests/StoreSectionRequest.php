<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // if(request()->isMethod('post')) {
            return [
                // 'title' => 'nullable|string|max:258',
                // 'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
                // 'contain' => 'required|string'
            ];
        // } else {
            // return [

        //     ]
        // }
        
    }
}
