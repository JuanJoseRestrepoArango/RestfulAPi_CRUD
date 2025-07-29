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

        
        if($this->isMethod('PUT')) {
            return [
                'nombre' => 'required|string|max:255',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|regex:/^[0-9\-\+\(\) ]+$/|max:20',
            ];
        }elseif($this->isMethod('PATCH')) {
            return [
                'nombre' => 'sometimes|string|max:255',
                'direccion' => 'sometimes|string|max:255',
                'telefono' => 'sometimes|regex:/^[0-9\-\+\(\) ]+$/|max:20',
            ];
        }

        return [
                'nombre' => 'required|string|max:255',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|regex:/^[0-9\-\+\(\) ]+$/|max:20',
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
