<?php

namespace App\Connection\Sso;



class SSO_Ntlm {

	private $user;
	private $domain;
	private $machine;

	public function __construct()
	{

		$headers = getallheaders();

		if(!array_key_exists('Authorization', $headers)) {
			header("Content-Length: 0");
			header("HTTP/1.1 401 Authorization-Required");
			header("WWW-Authenticate: NTLM");
			flush(); die;
		}

		@$auth =  $headers['Authorization'];
		@$msg = unpack('c*', base64_decode(substr($headers['Authorization'], 5)));
		@$off = $length = $offset  = 0;

		// Switch
		//$msg[9] = 3;
		switch($msg[9])
		{
			// Solicita dados adicionais
			case 1:
				$off = 18;
				header("Content-Length: 0");
				header("HTTP/1.1 401 Authorization-Required");
				header("WWW-Authenticate: NTLM TlRMTVNTUAACAAAAAAAAACgAAAABggAAAAICAgAAAAAAAAAAAAAAAA==\r\n");
				flush(); die;
			break;

			// Dados suficientes
			case 3:
				$off = 31;

				// Machine
				@$length = $msg[$off+17]*256 + $msg[$off+16];
				@$offset = $msg[$off+19]*256 + $msg[$off+18];

				@$m = array_slice($msg, $offset, $length);
				$this->machine = trim(implode('', array_map('chr', array_diff($m, array(0)))));

				// Domain
				@$length = $msg[$off+1]*256 + $msg[$off];
				@$offset = $msg[$off+3]*256 + $msg[$off+2];

				@$d = array_slice($msg, $offset, $length);
				$this->domain = trim(implode('', array_map('chr', array_diff($d, array(0)))));

				// User
				@$length = $msg[$off+9]*256 + $msg[$off+8];
				@$offset = $msg[$off+11]*256 + $msg[$off+10];

				@$u = array_slice($msg, $offset, $length);
				$this->user = substr(trim(implode('', array_map('chr', array_diff($u, array(0))))), 1);
			break;

			// N�o foi poss�vel autenticar
			default:
				die("N�o foi poss�vel detectar suas credenciais de acesso!");
		}
	}

	public function getUser ()
	{
		return $this->user;
	}

	public function getDomain ()
	{
		return $this->domain;
	}

	public function getMachine ()
	{
		return $this->machine;
	}


}
?>
