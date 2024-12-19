<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class add_user_request extends FormRequest
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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'telephone' => 'required|regex:/^[0-9]{10}$/',
            'poste' => 'required|string|max:255',
            'service' => 'required|string|in:maintenance,exploatation,archive,magasin,securité',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'autorisations' => 'required|array',
        ];
    }
}
