<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UsuarioRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nr_matricula' => 'required_without:no_usuario',
            'no_usuario' => ['required_without:nr_matricula',
                'string',
                'min:8',
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[0-9]/',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[@$!%*#?&]/'
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'nr_matricula.required_without' => 'O Parâmetro nr_matricula é obrigatório',
            'no_usuario.required_without' => 'O Parâmetro Nome de Usuário é obrigatório',
            'no_usuario.min' => 'O Parâmetro Nome de Usuário deve conter pelo menos 8 caracteres',
            'no_usuario.regex' => 'O Parâmetro Nome de Usuário deve ser formado por letras maiúsculas. Ex: USR_NOMESISTEMA',
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
