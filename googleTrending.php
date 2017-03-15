<?php
/**
 * Created by PhpStorm.
 * User: minhnguyen
 * Date: 3/16/17
 * Time: 1:31 AM
 */
include 'curl.php';
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, 'http://hawttrends.appspot.com/api/terms/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$cookie = "CFID=526625456; CFTOKEN=344a04c698313abc-733A1767-B064-ED72-928E9D737771A751; BNES_CFID=3WRXUWu2FpuyUcRnno0vYsgqbGvyISb+DT5WKjR1JCxpHuhIDSP2XvTDTfi7dcUzogisEUUqrYeuURm5IeMCyQ==; BNES_CFTOKEN=hoStZtvmz2H8xE6/e0XTZJ2xD8N6Z+keWU2xS6RYU0Udp8SwfD90uzLygVQ6LrkCvfVSdDvMHJfn4kHkvODbB6Ib6pLm4PKd18ubThD3f/XrTGvnkRqZ1aUstfHZSJrfVSQKUNP/cBo=; AFFILIATE=56846; BNES_AFFILIATE=LzbYKJqcVCUEwOaGCNwzFKg54kDK3ZEozf5pQLmUy1F6+H6e8RvxXODsnyT8LoELDRaGAPvqYx2sVYK64zBssw==; _gat=1; BNI_PeanutButter=0000000000000000000000006d00590a00000000; _ga=GA1.2.1512150065.1474732962; __asc=27950bcf15771dad070a9c8308f; __auc=b2d061061575cef33397ab21c0d";
$header = array();
$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
$header[] =  "Cache-Control: max-age=0";
$header[] =  "Connection: keep-alive";
$header[] = "Keep-Alive: 300";
$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
$header[] = "Accept-Language: en-us,en;q=0.5";
$header[] = "Pragma: ";
$header[] = 'Cookie: ' . $cookie;
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch,CURLOPT_AUTOREFERER, true);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
$trends = curl_exec($ch);
//echo $trends;
curl_close($ch);
$t = json_decode($trends, true);
ksort($t);
    foreach($t[1] as $tr) {
        $curl = new curl("https://www.sunfrog.com/search/?cId=0&cName=&search=".urlencode($tr));
        $shirts = $curl->getShirtByName();
        if(sizeof($shirts) > 0) {
            foreach($shirts as $shirt) {
                $sql = "INSERT INTO `shirt` (`id`, `src`, `name`, `link`) VALUES (NULL, '".$shirt["src"]."', '".$shirt["name"]."', '".$shirt["link"]."');";
                if (mysqli_query($conn, $sql)) {
                    echo "New records created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
        }
    }
