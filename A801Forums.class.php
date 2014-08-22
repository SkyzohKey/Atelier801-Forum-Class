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
		$this->setOpt(CURLOPT_COOKIEJAR, $this->cURLCookieJar);
		$this->setOpt(CURLOPT_COOKIEFILE, $this->cURLCookieJar);
	}

	public function __destruct()
	{
		curl_close($this->cURL);
		unset($this->cURL);
	}

	/**
	*	Private functions.
	*	Used as helpers.
	**/
	private function setOpt($option, $value)
	{
		curl_setopt($this->cURL, $option, $value);
	}

	private function request($url, $curlOpt = null, $isPost = false, $postParams = null)
	{
		curl_close($this->cURL);

		$this->cURL = curl_init();
		$this->setOpt(CURLOPT_COOKIEJAR, $this->cURLCookieJar);
		$this->setOpt(CURLOPT_COOKIEFILE, $this->cURLCookieJar);
		$this->setOpt(CURLOPT_FOLLOWLOCATION, true);
		// If you want to add headers : $this->setOpt(CURLOPT_HTTPHEADER, array("" => ""));

		$this->setOpt(CURLOPT_URL, $url);

		if ($curlOpt != null)
		{
			foreach ($curlOpt as $key => $value)
				$this->setOpt($key, $value);
		}

		if ($isPost)
		{
			$this->setOpt(CURLOPT_POST, 1);
			$this->setOpt(CURLOPT_POSTFIELDS, $postParams);
		}

		$exec = curl_exec($this->cURL);

		$header_size = curl_getinfo($this->cURL, CURLINFO_HEADER_SIZE);
		$header = substr($exec, 0, $header_size);
		$body = substr($exec, $header_size);

		return array($header, $body);
	}

	/**
	*	Public functions.
	**/
	public function connect($userName = null, $userPass = null)
	{
		if ($userName != null || $userPass != null)
		{
			$this->username = $userName;
			$this->userpass = $userPass;
		}

		$indexPage = $this->request($this->basePath);
		return $indexPage;

		/*if ($response = $this->request($this->basePath . "/identification", null, true, "id=" . $this->username . "&pass=" . $this->userpass . "&" . $tokenName . "=" . $tokenValue))
		{
			$json = json_decode($response);

			if ($json->redirection == "http://atelier801.com/index")
				return true;
			else
				return false;
		}*/
	}
}
