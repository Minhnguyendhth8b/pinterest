<?php 
	
	require('vendor/autoload.php'); 
	include 'base/config.php';
	use seregazhuk\PinterestBot\Factories\PinterestBot;

	$sql = "SELECT * FROM `account` WHERE `is_update_board_id` = 0";
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
			$username = $bot->user->username();
    		$boards = $bot->boards->forUser($username);
    		$insert = "INSERT INTO `boards` (`id`, `user_id`, `board_id`) VALUES (NULL, '".$id."', '".$boards[0]["id"]."')";
    		if (mysqli_query($conn, $insert)) {
			    $update = "UPDATE `account` SET `is_update_board_id` = 1";
				if (mysqli_query($conn, $update)) {
					echo "<a href=\"https://www.pinterest.com/".$username."\">".$Name."</a>"; //prints your username
					echo "<br>"; // Reset $bot;
				} else {
				    echo "Error updating record: " . mysqli_error($conn);
				}
			} else {
			    echo "Error: " . $insert . "<br>" . mysqli_error($conn);
			}
			
			$bot = null;

		}
	}

?>