<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class maintenanceinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        $isPartitNon = $this->input('partit') === 'non';
        $ispannemecanique = $this->input('pannemecaniquecheck') === 'on';
        $ispannelectrique = $this->input('panneelectriquecheck') === 'on';
        $ispannetolle = $this->input('pannetollecheck') === 'on';

        // dd($this->input('pannemecaniquecheck'),$this->input('panneelectriquecheck'),$this->input('pannetollecheck'),$ispannemecanique,$ispannelectrique,$ispannetolle);
        return [
            'date' => ['required', 'date'],
            'bus' => ['required', 'exists:buses,id'],
            'id_chauffeur' => $isPartitNon ? ['nullable'] : ['required', 'exists:chauffeurs,id'],
            'brigade' => ['required'],
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
            'pannemecanique' => !$ispannemecanique ? ['nullable'] :  'required|array',
            'panneelectrique' => !$ispannelectrique ? ['nullable'] :  'required|array',
            'pannetolle' => !$ispannetolle ? ['nullable'] :  'required|array',
        ];
    }
}
