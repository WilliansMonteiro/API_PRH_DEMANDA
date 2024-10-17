<?php


namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class SAACargoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(){
        return ['no_cargo' => 'required|max:40'];
    }

    public function messages(){
        return ['no_cargo.required' => 'O campo Nome é obrigatório',
                'no_cargo.max' => 'O campo Nome é não pode ter mais de 40 caracteres'];
    }
}