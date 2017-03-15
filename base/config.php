<?php 
    

    define('_host', "localhost");
    define('_user', "root");
    define('_pass', "root");
    define('_db', "pins");

    $conn = mysqli_connect(_host, _user, _pass, _db) or die("Cannot connect to db");
    $utf8q = mysqli_query($conn, "SET NAMES UTF8");
    if(!$utf8q) {
        die("Can't set utf8");
    }
    

    function random_username($string) {
		$pattern = " ";
		$firstPart = strstr(strtolower($string), $pattern, true);
		$secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);
		$nrRand = rand(0, 1000);

		$username = trim($firstPart).trim($secondPart).trim($nrRand);
		return $username;
	}


	function generatePassword($length = 8) {
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $count = mb_strlen($chars);

	    for ($i = 0, $result = ''; $i < $length; $i++) {
	        $index = rand(0, $count - 1);
	        $result .= mb_substr($chars, $index, 1);
	    }

	    return $result;
	}


	function update($query, $conn) {
		if (mysqli_query($conn, $query)) {
			return true;
		}
		return false;
	}

?>