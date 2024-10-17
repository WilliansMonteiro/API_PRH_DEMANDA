<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SAAUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fdecpf' => 'required|min:11',
            'fdetelcel' => 'required|min:9',
            'ddd' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'fdecpf.required' => 'O campo CPF é obrigatório',
            'fdecpf.min' => 'O campo CPF deve conter no mínimo 11 números',
            'fdetelcel.required' => 'O campo Telefone é obrigatório',
            'fdetelcel.min' => 'O campo Telefone deve conter no mínimo 9 números',
            'ddd.required' => 'O campo DDD é obrigatório'
        ];
    }
}
