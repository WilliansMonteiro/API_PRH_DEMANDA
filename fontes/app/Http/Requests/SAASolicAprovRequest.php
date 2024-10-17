<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SAASolicAprovRequest extends FormRequest
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
            'no_prestador' => 'required|min:3|max:45',
            'nr_cpf' => 'required|min:11',
            'nr_cel' => 'required|min:9',
            'ds_endereco' => 'required|max:200',
            'nr_cep' => 'required|min:8',
            'no_cidade' => 'required|max:20',
            'nr_ddd' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'no_prestador.required' => 'O campo Nome é obrigatório',
            'nr_cpf.required' => 'O campo Cpf é obrigatório',
            'nr_cpf.min' => 'O campo Cpf deve conter no mínimo 11 números',
            'ds_endereco.required' => 'O campo Endereço é obrigatório',
            'ds_endereco.max' => 'O campo Endereço deve conter no máximo 200 caracteres',
            'nr_cep.required' => 'O campo Cep é obrigatório',
            'nr_cep.min' => 'O campo Cep deve conter no mínimo 8 números',
            'no_cidade.required' => 'O campo Cidade é obrigatório', 
            'nr_cel.required' => 'O campo Telefone é obrigatório',
            'nr_cel.min' => 'O campo Telefone deve conter no mínimo 9 números',
            'nr_ddd.required' => 'O campo DDD é obrigatório'
            
           
        ];
    }
}
