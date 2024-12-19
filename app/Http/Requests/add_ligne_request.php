<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class add_ligne_request extends FormRequest
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
            'name' => 'required|string|unique:lignes,name',
            'station_id' => 'required|string|exists:stations,id',
            'terminus' => 'required|numeric',
        ];
    }
}
