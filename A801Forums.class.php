<?php
/**
*	Atelier801 Forums Class
*	@version 	0.1
*	@author 	Fish
**/

class A801Forums
{
	// Declare and init vars.
	private $cURL, $cURLCookieJar;
	private $username, $userpass;
	private $basePath = "http://www.atelier801.com";

	public function __construct($cookieJar, $userName, $userPass)
	{
		// Initialize many fun things.
		$this->username = $userName;
		$this->userpass = $userPass;

		$this->cURLCookieJar = $cookieJar;
	}

	public function __destruct()
	{
		// Close the request and then unset the var.
		curl_close($this->cURL);
		unset($this->cURL);
	}

	/**
	*	Private functions.
	*	Used as helpers.
	**/
	private function setOpt($option, $value)
	{
		// Set a new option.
		curl_setopt($this->cURL, $option, $value);
	}

	private function request($url, $curlOpt = null, $isPost = true, $postParams = null)
	{
		// Do some shitty stuff to set differents cURL options.
		$this->cURL = curl_init();
		$this->setOpt(CURLOPT_COOKIEJAR, $this->cURLCookieJar);
		$this->setOpt(CURLOPT_COOKIEFILE, $this->cURLCookieJar);

		if ($isPost)
		{
			$this->setOpt(CURLOPT_POST, true);
			$this->setOpt(CURLOPT_POSTFIELDS, $postParams);
		}

		$this->setOpt(CURLOPT_COOKIESESSION, true);
		$this->setOpt(CURLOPT_FOLLOWLOCATION, true);
		$this->setOpt(CURLOPT_RETURNTRANSFER, true);
		$this->setOpt(CURLOPT_HTTPHEADER, array("Host: www.atelier801.com",
										        "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",
										        "Accept: application/json, text/javascript, */*; q=0.01",
										        "Accept-Language: fr-fr,en;q=0.5",
										        //"Accept-Encoding: gzip, deflate",
										        "Connection: keep-alive",
										        "X-Requested-With: XMLHttpRequest",
										        "Referer: http://www.atelier801.com/"));

		$this->setOpt(CURLOPT_URL, $url);

		if ($curlOpt != null)
		{
			foreach ($curlOpt as $key => $value)
				$this->setOpt($key, $value);
		}

		// Request it !
		$exec = curl_exec($this->cURL);

		// Some debugs informations.
		$header_size = curl_getinfo($this->cURL, CURLINFO_HEADER_SIZE);
		$header = substr($exec, 0, $header_size);
		$body = substr($exec, $header_size);

		return $body;

		// At least, close the request.
		curl_close($this->cURL);
	}

	/**
	*	Public functions.
	**/
	public function connect($userName = null, $userPass = null)
	{
		// Disconnect first to avoid problems.
		$this->disconnect();

		// If we want to connect another account than the main account.
		if ($userName != null || $userPass != null)
		{
			$this->username = $userName;
			$this->userpass = $userPass;
		}

		// Get the indexPage
		$indexPage = (string)$this->request($this->basePath . "/forums");

		// Find the token name/value.
		preg_match('/<input type="hidden" name="([a-zA-Z0-9 -]{1,10})" value="([a-zA-Z0-9 -]{1,50})"/', $indexPage, $matches);
		$tokenName = $matches[1];
		$tokenValue = $matches[2];

		// Post fields for the login request.
		/*$fields = array("id" => $this->username,
						"pass" => $this->userpass,
						$tokenName => $tokenValue);*/

		$fields = "id=" . $this->username . "&pass=" . $this->userpass . "&" . $tokenName . "=" . $tokenValue;

		// Let's log into.
		$response = (string)$this->request($this->basePath . "/identification", null, true, $fields);
		
		/**
		*	Bad ids: {"resultat":"ECHEC_AUTHENTIFICATION","message":"Identifiant ou mot de passe invalide."}
		*	Good ids : {"redirection":"http://atelier801.com/index"}
		**/

		return $response;
	}

	public function disconnect()
	{
		/**
		*	TODO:
		*	Check if an user is logged and then disconnect (/deconnexion) him.
		**/
		return true;
	}
}
