<?php
require('vendor/autoload.php');
include 'base/config.php';
use seregazhuk\PinterestBot\Factories\PinterestBot;

$sql = "SELECT * FROM `account` WHERE `hasAvatar` = 0";
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
        $login = $bot->auth->login($email,$password);
        if(!$login) {
            echo $bot->getLastError();
            die();
        }
        $selectImage = "SELECT * FROM profile_img ORDER BY RAND() LIMIT 1";
        $rs = mysqli_query($conn, $selectImage);
        if (mysqli_num_rows($rs) > 0) {
            // output data of each row
            $row = mysqli_fetch_assoc($rs);
            $image_url = $row['url'];
            $profile = $bot->user->profile(['profile_image_url' => $image_url]);
            $update = "UPDATE `account` SET `hasAvatar` = '1' WHERE `account`.`id` = $id";
            if (mysqli_query($conn, $update)) {
                $pf = $bot->user->profile();
                echo "<a href=\"https://www.pinterest.com/".$pf['username']."\">".$Name."</a>"; //prints your username
                echo "<br>"; // Reset $bot;
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } else {
            echo "0 results";
        }
        $bot = null;
    }
}

?>