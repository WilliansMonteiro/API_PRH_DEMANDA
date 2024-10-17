<?php

namespace App\Connection;

use App\Connection\Sso\SSO_Ntlm;
///** EXCEPTION */
//class SSO_Exception extends Exception {}

/** CLASS */
class SSOFactory {
	public static function getInstance($sso=null) {
		switch ($sso) {
			default:
				return new SSO_Ntlm();
		}
	}
}
?>