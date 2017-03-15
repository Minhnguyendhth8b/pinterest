<!-- SELECT * FROM account WHERE `lastPin` < NOW() - INTERVAL 5 MINUTE -->
<!-- This is sql to select all record that already pin within 5 minutes -->


<!-- https://www.sunfrog.com/Names/Pennant-Black-Hoodie.html?70467 -->
<!-- 
$bot->pins->create(
    'http://exmaple.com/image.jpg', 
    $boardId, 
    'pin description', 
    'http://site.com'
);
 -->

<?php 

	require('vendor/autoload.php'); 
	include 'base/config.php';
	use seregazhuk\PinterestBot\Factories\PinterestBot;

    $bot = PinterestBot::create();
    $selectquery = "SELECT * FROM `account` ORDER BY RAND() LIMIT 0,1;";
    $accounts = array();
    $result = mysqli_query($conn, $selectquery);
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            array_push($accounts, array("userId" => $row["id"], "email" => $row['email'] , "password" => $row['password'], "username" => $row['username']));
        }
    } else {
        echo "0 results";
    }
    echo $accounts[0]['username'];
    echo "<br>";
    $auth = $bot->auth->login($accounts[0]['email'], $accounts[0]['password']);

    if(!$auth) {
        echo $bot->getLastError();
        die();
    }


    $selectBoards = "SELECT `board_id` FROM `boards` WHERE `user_id` = ".$accounts[0]['userId'];
    $boardId = mysqli_query($conn, $selectBoards);
    if(mysqli_num_rows($boardId) > 0) {
        while($r = mysqli_fetch_assoc($boardId)) {
            $boardId = $r['board_id'];
        }
    }

    $selectShirt = "SELECT * FROM `shirt` ORDER BY RAND() LIMIT 0,1;";
    $shirt = mysqli_query($conn, $selectShirt);
    if(mysqli_num_rows($shirt) > 0) {
        while($s = mysqli_fetch_assoc($shirt)) {
            $pinInfo = $bot->pins->create(
                'http:'.$s['src'],
                $boardId,
                $s['name'].' - The Legend Is Alive',
                $s['link'].'?56846'
            );
            print_r($pinInfo['id']);
        }
    }







?>