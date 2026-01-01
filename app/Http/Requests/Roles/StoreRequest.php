<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:roles,name',
            ],
            'color'=>[
                'nullable',
                'string',
            ],
            'guard_name'=>[
                'required',
                'string',
            ]
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.string' => 'El nombre del rol debe ser una cadena de texto.',
            'name.max' => 'El nombre del rol no debe exceder los 50 caracteres.',
            'name.unique' => 'El nombre del rol ya está registrado.',
        ];
    }
}
