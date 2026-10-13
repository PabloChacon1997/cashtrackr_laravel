<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Override;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function messages(): array
    {
        return [
            'name.required'=> 'El nombre es obligatorio',
            'email.required'=> 'El email es obligatorio',
            'email.email'=> 'El email es inválido',
            'email.unique'=> 'El email ya esta registrado, ingrese otro.',
            'password.required'=> 'La contraseña es obligatoria',
            'password.confirmed'=> 'Las contraseñas no coinciden',
            'password.min'=> 'La contraseña debe tener almenos :min caracteres',
            'password.mixed'=> 'La contraseña debe tener almenos 1 letra mayuscula y 1 letra minuscula',
            'password.symbols'=> 'La contraseña debe tener almenos 1 caracter especial (@-_.)',
            'password.numbers'=> 'La contraseña debe tener almenos 1 número',
            'password.uncompromised'=> 'La contraseña no es segura. Ingresa una mas segura',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','confirmed', 
                Password::min(8)
                    // ->letters()
                    // ->mixedCase()
                    // ->symbols()
                    // ->numbers()
                    // ->uncompromised()
            ],
        ];
    }
}
