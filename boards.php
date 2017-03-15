<?php 
	
	require('vendor/autoload.php'); 
	include 'base/config.php';
	use seregazhuk\PinterestBot\Factories\PinterestBot;

	$sql = "SELECT * FROM `account` WHERE `hasBoard` = 0";
	$accounts = array();
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result)) {
	        array_push($accounts, array("id" => $row["id"], "email" => $row['email'], "password" => $row["password"], "name" => $row["name"]));
	    }
	} else {
	    echo "0 results";
	}
	if(count($accounts) > 0) {
		foreach ($accounts as $key => $account) {
			$email = $account["email"];
			$password = $account["password"];
			$Name = $account["name"];
			$id = $account["id"];
			$bot = PinterestBot::create();
			$login = $bot->auth->login($email, $password);
			if(!$login) {
			    echo $bot->getLastError();
			    die();
			}
			$title = 'Sunfrog T-Shirt Shop - '.$Name;
			$desc = 'Welcome to SunFrog! Shop t-shirts. Choose from over 2000000 unique tees. Sunfrog has a large selection of shirt styles';
			$bot->boards->create($title , $desc);
            $profile = $bot->user->profile();
			$update = "UPDATE `account` SET `username` = '".$profile['username']."', `hasBoard` = '1' WHERE `account`.`id` = $id";
			if (mysqli_query($conn, $update)) {
				echo "<a href=\"https://www.pinterest.com/".$profile['username']."\">".$Name."</a>"; //prints your username
				echo "<br>"; // Reset $bot;
			} else {
			    echo "Error updating record: " . mysqli_error($conn);
			}
			$bot = null;
		}
	}
?>