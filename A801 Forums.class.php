<?php
/**
*	Atelier801 Forums Class
*	@version 	0.1
*	@author 	Fish
*	@author 	Toonney
**/

class A801Forums
{
	// Declare and init vars.
	private $cURL;
	private $username, $userpass;
	private $basePath = "http://www.atelier801.com";

	public function __construct($userName, $userPass)
	{
		$this->username = $userName;
		$this->userpass = $userPass;

		$this->cURL = curl_init();
		curl_setopt($this->cURL, CURLOPT_COOKIEJAR, "");
	}

	private function setCURLOpt($option, $value)
	{
		curl_setopt($this->cURL, $option, $value);
	}

	public function connect($userName = null, $userPass = null)
	{
		if ($userName != null || $userPass != null)
		{
			$this->username = $userName;
			$this->userpass = $userPass;
		}

		setCURLOpt(CURLOPT_URL, $this->basePath . "/identification");
	}

	public function __unset()
	{
		curl_close($this->cURL);
		unset($this->cURL);
	}
}