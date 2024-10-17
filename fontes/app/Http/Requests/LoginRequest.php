<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class LoginRequest extends FormRequest
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
            'nr_matricula'  => 'required',
            'codigo' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nr_matricula.required' => 'O campo Matricula é obrigatório',            
            'codigo.required'       => 'O Campo Password é obrigatório',           
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'codigo' => $this->descriptografar($this->hashGerado,Session::get('hashLogin'))
        ]);
    }


    public function descriptografar(string $jsonStr, string $passphrase){
        $json = json_decode($jsonStr, true);
        $salt = hex2bin($json["s"]);
        $iv = hex2bin($json["iv"]);
        $ct = base64_decode($json["ct"]);
        $concatedPassphrase = $passphrase . $salt;
        $md5 = [];
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return json_decode($data, true);
     }
}
