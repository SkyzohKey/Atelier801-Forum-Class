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
	private $cURL, $cURLCookieJar;
	private $username, $userpass;
	private $basePath = "http://www.atelier801.com";

	public function __construct($userName, $userPass)
	{
		$this->username = $userName;
		$this->userpass = $userPass;

		$this->cURLCookieJar = tempnam("/tmp", "CURLCOOKIE");

		$this->cURL = curl_init();
		curl_setopt($this->cURL, CURLOPT_COOKIEJAR, $this->cURLCookieJar);
		curl_setopt($this->cURL, CURLOPT_COOKIEFILE, $this->cURLCookieJar);
	}

	private function setOpt($option, $value)
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

		setOpt(CURLOPT_URL, $this->basePath . "/identification");
		setOpt(CURLOPT_POST, 1);
		setOpt(CURLOPT_POSTFIELDS, "id=" . $this->username . "&pass=" . $this->pass . "&" . $tokenName . "=" . $tokenValue);

		if ($response = curl_exec($this->cURL))
		{
			$json = json_decode($response);

			if ($json->redirection == "http://atelier801.com/index")
				return true;
			else
				return false;
		}
	}

	public function __unset()
	{
		curl_close($this->cURL);
		unset($this->cURL);
	}
}
