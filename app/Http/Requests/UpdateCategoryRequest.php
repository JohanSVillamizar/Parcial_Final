<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categories_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],

            'categories_description' => [
                'nullable',
                'string',
                'max:255'
            ],

            'categories_status' => [
                'required',
                'boolean'
            ]
        ];
    }

    public function messages()
    {
        return [
            'categories_name.required' => 'El nombre es obligatorio.',
            'categories_name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'categories_name.max' => 'El nombre no puede superar 100 caracteres.',

            'categories_description.max' => 'La descripción no puede superar 255 caracteres.',

            'categories_status.required' => 'Debe seleccionar un estado.',
            'categories_status.boolean' => 'El estado no es válido.',
        ];
    }
}
