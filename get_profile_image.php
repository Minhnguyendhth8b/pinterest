<?php
/**
 * Created by PhpStorm.
 * User: minhnguyen
 * Date: 12/27/16
 * Time: 2:15 AM
 */

include 'base/craw.php';
include 'base/config.php';
for ($i=1 ; $i < 27; $i++) {
    $html = file_get_html("http://eskipaper.com/girls/".$i);
//    $html = file_get_html("http://eskipaper.com/girls/");

    $images = $html->find('.item-image > img');
    $selectquery = "SELECT * FROM `profile_img`";
    $result = mysqli_query($conn, $selectquery);
    $allUrls = array();
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            array_push($allUrls, $row['url']);
        }
    } else {
        echo "0 results";
    }


    foreach ($images as $key => $img) {
        if($img->attr['src'] !== '' && !in_array($img->attr['src'], $allUrls)) {
            $url = $img->attr['src'];
            $sql = "INSERT INTO `profile_img` (`id`, `url`) VALUES (NULL, '$url')";
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }

}
mysqli_close($conn);
?>