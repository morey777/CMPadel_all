<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd($this);
        return [
            'name' => 'required',
            'lastname' => 'required',
            'dni' => [
                'nullable',
                Rule::unique('users')->ignore($this->input('id'))
            ],
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->input('id'))
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Se tiene que poner el nombre",
            'lastname.required' => "Se tiene que poner el apellido",
            'dni.unique' => "Ese dni ya existe",
            'email.required' => "Se tiene que poner un email",
            'email.unique' => "Ese email ya existe",
        ];
    }
}
