<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
                'max:30',
            ],

            'categories_description' => [
                'nullable',
                'string',
                'max:255'
            ],

            'categories_status' => [
                'required',
                'boolean'
            ],

            'categories_created_date' => [
                'required',
                'date'
            ],
        ];
    }

    public function messages()
    {
        return [
            'categories_name.required' => 'El nombre de la categoría es obligatorio.',
            'categories_name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'categories_name.max' => 'El nombre no puede superar los 30 caracteres.',

            'categories_description.max' => 'La descripción no puede superar 255 caracteres.',

            'categories_status.required' => 'Debe seleccionar un estado válido.',
            'categories_status.boolean' => 'El estado no es válido.',

            'categories_created_date.required' => 'Debe ingresar la fecha de creación.',
            'categories_created_date.date' => 'La fecha de creación no es válida.',
        ];
    }
}
