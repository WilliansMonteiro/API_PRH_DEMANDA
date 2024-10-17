<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Modules\Usuario\Entities\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'no_usuario' => 'required_without:nr_matricula',
            'nr_matricula' => 'required_without:no_usuario',
            'password' => [
                'required',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[@$!%*#?&]/' // must contain a special character
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'no_usuario.required_without' => 'O Parâmetro no_usuario é obrigatório',
            'nr_matricula.required_without' => 'O Parâmetro nr_matricula é obrigatório',
            'password.required' => 'O Parâmetro Password é obrigatório',
            'password.min' => 'O Parâmetro Password deve conter pelo menos 8 caracteres',
            'password.regex' => 'O Parâmetro Password deve ser formado por letras maiúsculas, minúsculas,números e caracteres especiais',
        ];
    }

    /**
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
