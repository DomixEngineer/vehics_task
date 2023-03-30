<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateAcOcRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'netPrice' => 'required',
            'grossPrice' => 'required',
            'yearProduction' => 'required',
            'gpsIncluded' => 'boolean',
            'divideInInstallments' => 'boolean',
            'countOfInstallments' => 'integer'
        ];
    }

    public function messages() {
        return [
            'netPrice.required' => 'Cena netto jest wymagana',
            'grossPrice.required' => 'Cena brutto jest wymagana',
            'yearProduction.required' => 'Rok produkcji jest wymagany',
            'gpsIncluded.boolena' => 'To pole jest wymagane jako wartość true / false',
            'divideInInstallments.boolena' => 'To pole jest wymagane jako wartość true / false',
            'countOfInstallments.integer' => 'To pole musi być typu liczbowego'
        ];
    }
}
