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
        'gasoile' => $isPartitNon ? ['nullable'] : ['required', 'numeric'],
        'hdepart' => $isPartitNon ? ['nullable'] : [
            'required',
            function ($attribute, $value, $fail) {
                if ($this->harrive && strtotime($value) >= strtotime($this->harrive)) {
                    $fail('The departure time (hdepart) must be before the arrival time (harrive).');
                }
            },
        ],
        'harrive' => $isPartitNon ? ['nullable'] : ['required'],
        'kmhlp' => $isPartitNon ? ['nullable'] : [
            'required',
            'numeric',
            function ($attribute, $value, $fail) {
                if ($this->kmarive && $this->kmdepart && $value >= ($this->kmarive - $this->kmdepart)) {
                    $fail('The kmhlp must be less than the difference between kmarrive and kmdepart.');
                }
            },
        ],
        'kmdepart' => $isPartitNon ? ['nullable'] : ['required', 'numeric'],
        'kmarive' => $isPartitNon ? ['nullable'] : ['required', 'numeric', 'gt:kmdepart'],
    ];
    }
}
