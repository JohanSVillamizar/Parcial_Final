<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComputerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'computer_brand' => 'required|string|max:100',
            'computer_model' => 'required|string|max:100',
            'computer_price' => 'required|numeric|min:0',
            'computer_ram_size' => 'required|integer|min:0',
            'computer_is_laptop' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'computers_barcode' => [
                'required',
                'string',
                'size:12',
                'regex:/^[A-Z0-9]{12}$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'computers_barcode.required' => 'El código de barras es obligatorio.',
            'computers_barcode.size' => 'El código de barras debe tener exactamente 12 caracteres.',
            'computers_barcode.regex' => 'El código de barras solo puede contener letras mayúsculas y números.',
        ];
    }
}
