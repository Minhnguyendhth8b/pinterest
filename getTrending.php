<?php
/**
 * Created by PhpStorm.
 * User: minhnguyen
 * Date: 2/20/17
 * Time: 12:51 AM
 */
// Run 1/1 hour
include 'base/twitterAPI.php';
include 'curl.php';

$settings = array(
    'oauth_access_token' => "2514190525-8xia21YjAyfSZQcLZvRTmrDse2iBTA9J9EUvL3Y",
    'oauth_access_token_secret' => "6C1YMFL0b6f6phNsW84tIs2LfHbAOOA22SZDyBHE94Ln8",
    'consumer_key' => "CgKhMSWPwIOpd31XINB8O7qtj",
    'consumer_secret' => "zn8mJgof6fqACC6ASJZYYSxWWDx27kyQ7NV01e1c005Zwyk2qr"
);

$url = 'https://api.twitter.com/1.1/trends/place.json';
$getfield = '?id=23424977';
$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);
$twits = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

$arr_twt = json_decode($twits);
$trends = $arr_twt[0]->trends;
$sql = "";
foreach ($trends as $k => $t) {
    if(strpos($t->name, "#") === false) {
        $curl = new curl("https://www.sunfrog.com/search/?cId=0&cName=&search=".$t->query);
        $shirts = $curl->getShirtByName();
        if(sizeof($shirts) > 0) {

            foreach($shirts as $shirt) {
                $sql .= "INSERT INTO `shirt` (`id`, `src`, `name`, `link`) VALUES (NULL, '".$shirt["src"]."', '".$shirt["name"]."', '".$shirt["link"]."');";
            }


        }
    }
}

if (mysqli_multi_query($conn, $sql)) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}