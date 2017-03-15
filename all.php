<?php 

	require('vendor/autoload.php');
    include 'base/config.php';
    use seregazhuk\PinterestBot\Factories\PinterestBot;

    $sql = "SELECT * FROM `account`";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "<a href=\"https://www.pinterest.com/".$row['username']."\">".$row["name"]."</a>";
            echo "<br>";
        }
    } else {
        echo "0 results";
    }

?>