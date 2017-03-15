<?php 
	require('vendor/autoload.php'); 
	include 'base/config.php';
	use seregazhuk\PinterestBot\Factories\PinterestBot;
	/*
		Database user structure: 
			- Table : account - Time creating is about 5 minute for 10 account 
	*/

	$selectquery = "SELECT * FROM `name`";
	$allNames = array();
	$result = mysqli_query($conn, $selectquery);
	if (mysqli_num_rows($result) > 0) {
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result)) {
	        array_push($allNames, $row['name']);
	    }
	} else {
	    echo "0 results";
	}

	$firstName = $allNames[mt_rand(0, count($allNames) - 1)];
	$lastName = $allNames[mt_rand(0, count($allNames) - 1)];
	$email = random_username($firstName." ".$lastName);
	$email = $email."@hotmail.com";
	$password = generatePassword();
	$Name = $firstName." ".$lastName;
	$bot = PinterestBot::create();
	$regs = $bot->auth->register($email, $password, $Name);
	$insert = "INSERT INTO `account` (`id`, `email`, `password`, `name`) VALUES (NULL, '".$email."', '".$password."', '".$Name."')";
	if (mysqli_query($conn, $insert)) {
		// Tao Board
		$last_id = mysqli_insert_id($conn);
		$login = $bot->auth->login($email, $password);
		if(!$login) {
		    echo $bot->getLastError();
		    die();
		}
		$title = 'Sunfrog T-Shirt Shop - '.$Name;
		$desc = 'Welcome to SunFrog! Shop t-shirts. Choose from over 2000000 unique tees. Sunfrog has a large selection of shirt styles';
		$bot->boards->create($title , $desc);
		$username = $bot->user->username();
		$update_username = "UPDATE `account` SET `username` = '".$username."', `hasBoard` = '1' WHERE `account`.`id` = $last_id";
		if(update($update_username, $conn)) {
			// Update profile image
			$selectImage = "SELECT * FROM profile_img ORDER BY RAND() LIMIT 1";
	        $rs = mysqli_query($conn, $selectImage);
	        if (mysqli_num_rows($rs) > 0) {
	            // output data of each row
	            $row = mysqli_fetch_assoc($rs);
	            $image_url = $row['url'];
	            $profile = $bot->user->profile(['profile_image_url' => $image_url, 'website_url' => 'http://innotshirt.com/']);
	            $update = "UPDATE `account` SET `hasAvatar` = '1' WHERE `account`.`id` = $last_id";
	            if (mysqli_query($conn, $update)) {
	                echo "<a href=\"https://www.pinterest.com/".$username."\">".$Name."</a>"; //prints your username
					echo "<br>"; // Reset $bot;
	            } else {
	                echo "Error updating record: " . mysqli_error($conn);
	            }
	        } else {
	            echo "0 results";
	        }

	        //Update board_id;

		}


	} else {
	    echo "Error: " . $insert . "<br>" . mysqli_error($conn);
	}


?>