<?php
    require('vendor/autoload.php');
    include 'base/config.php';
    use seregazhuk\PinterestBot\Factories\PinterestBot;

    $bot = PinterestBot::create();
    if(!$bot->auth->isLoggedIn()) {
        $login = $bot->auth->login("juliannacu228@hotmail.com","lALhFpbg");
        if(!$login) {
            echo $bot->getLastError();
            die();
        }
    }
    $username = $bot->user->username();
    $boards = "SELECT * FROM `boards` WHERE `user_id` != 2";
    $result = mysqli_query($conn, $boards);
    $boardIds = array();
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            array_push($boardIds, $row["board_id"]);
        }
    } else {
        echo "0 results";
    }
    print_r($boardIds);

    echo json_encode($boardIds);
    // $bot->boards->follow($row["board_id"]);
    echo "success";


?>