Atelier801-Forum-Class
======================

A simple class to do basic operation through Atelier801 Forums. \o/

Usage
-----
``` php
<?php
require_once("class/A801Forums.class.php");

$path_cookie = 'cookie.txt';
if (!file_exists(realpath($path_cookie)))
	touch($path_cookie);

$forum = new A801Forums(realpath($path_cookie), "Username", "Hashed Password");
$con = $forum->connect();
```
