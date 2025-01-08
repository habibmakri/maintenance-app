<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class maintenanceEditRequest extends FormRequest
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
    $isPartitNon = $this->input('partit') === 'non';
    return [
        'partit'=> ['required'],
        'ligne' => $isPartitNon ? ['nullable'] : ['required', 'exists:lignes,id'],
        'id_chauffeur' => $isPartitNon ? ['nullable'] : ['required', 'exists:chauffeurs,id'],
        'gasoile' => $isPartitNon ? ['nullable'] : ['required', 'numeric'],
        'hdepart' => $isPartitNon ? ['nullable'] : [
            'required',
            function ($attribute, $value, $fail) {
                if ($this->harrive && strtotime($value) >= strtotime($this->harrive)) {
                    $fail('L\'heure de départ  doit être antérieure à l`\'heure d\'arrivée .');
                }
            },
        ],
        'harrive' => $isPartitNon ? ['nullable'] : ['required'],
        'kmhlp' => $isPartitNon ? ['nullable'] : [
            'required',
            'numeric',
            function ($attribute, $value, $fail) {
                if ($this->kmarive && $this->kmdepart && $value >= ($this->kmarive - $this->kmdepart)) {
                    $fail(' Le kilométrage HLP doit être inférieur à la différence entre le  kilométrage d\'arrivée et le  kilométrage de départ.');
                }
            },
        ],
        'kmdepart' => $isPartitNon ? ['nullable'] : ['required', 'numeric'],
        'kmarive' => $isPartitNon ? ['nullable'] : ['required', 'numeric', 'gt:kmdepart'],
    ];
    }
}
