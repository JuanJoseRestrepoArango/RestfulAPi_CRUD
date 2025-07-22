<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestauranteRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
        ];

        
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del restaurante es obligatorio.',
            'direccion.required' => 'La dirección del restaurante es obligatoria.',
            'telefono.required' => 'El teléfono del restaurante es obligatorio.',
        ];
    }
}
