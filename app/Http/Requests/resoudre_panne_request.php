<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class resoudre_panne_request extends FormRequest
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
            'fichepanne_id' => 'required|exists:fichepanne,id',
            'dateresolu' => 'required|date',
            'lieuresolu' => 'required|string',
            'brigade' => 'required|string',
            'equipe' => 'nullable|array',
            // 'equipe.*' => 'exists:maintenance_agents,id',
            // 'description' => 'required|string',
            'pieces' => 'nullable|array', 
            'pieces.*' => 'nullable|exists:pieces_maintenance,id', 
            'piece_quantities' => 'nullable|array', 
            'piece_quantities.*' => 'nullable|integer|min:1',
        ];
    }

}
