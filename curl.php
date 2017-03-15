<?php
    include 'base/craw.php';
    include 'base/config.php';
	class curl{

		public $html;
		public $link;
		function __construct($link = "https://sunfrog.com") {
			$this->html = $this->curlInit($link);
		}

		function curlInit($link) {
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, $link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $cookie = "CFID=598708347; CFTOKEN=82150e50bc84e12b-417E0838-155D-158F-9AC67A5BDBCA9E06; BNES_CFID=1APCPvy5TlLwbH03MFIQuA8NytbNb0BCh+hGRyyHxV+Ankz1Vhn76sD6ErT2AG3IZekUNtPDVlldLqjkLf4Qlg==; BNES_CFTOKEN=/eVlyEsSJWNHWIOtIfusUUk3tYOTUjZI69S8Avqpp7RUpC1Qep+D0qpHrIp4V7XUAdyDG2kJv4fs3N1ORX/sRzCjbg+WiP3dZBWQLC7DK3biR8ZVGNQ2nzLHSeGih826i8e9OsNJR0M=; _ga=GA1.2.643503338.1489604653; _gat=1; __asc=0ec71d5d15ad35b31a867e8744a; __auc=0ec71d5d15ad35b31a867e8744a; BNI_PeanutButter=0000000000000000000000006d005c0a00000000";
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
            $header[] = 'Upgrade-Insecure-Requests: 1';
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36');
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		    curl_setopt($ch,CURLOPT_AUTOREFERER, true);
		    curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);  
			$string = curl_exec($ch);
			curl_close($ch);
			$html = str_get_html($string);
			return $html;
		}

		function getCategories() {
			$html = $this->html;
			$categories = $html->find(".catwrap",0)->find("div");
			$results = array();
			foreach ($categories as $key => $category) {
				$name = $category->find("a",0)->plaintext;
				$link = $category->find("a",0)->href;
				$cId = $category->find("a",0)->attr['data-cid'];
				$results[] = array("name" => $name, "link" => $link, "cId" => $cId);
			}
			$results[] = array("name" => "Best-Sellers" , "link" => "https://www.sunfrog.com/Best-Sellers/" , "cId" => -1);
			return $results;
		}

		function getShirtByName() {
			$html = $this->html;
			$shirts = $html->find(".frontThumb");
			$results = array();
			foreach ($shirts as $key => $shirt) {
				$image = $shirt->find("img", 0);
				$link = $image->parent->parent->href;
				$imgSrc = $image->attr['data-src'];
				$imgName = $image->attr['title'];
				$results[] = array("src" => $imgSrc, "name" => $imgName, "link" => $link);
			}
			return $results;
		}
	}
	
	/* GET CATEGORIES */
	// $curl = new curl("https://sunfrog.com");
	// $categories = $curl->getCategories();
	// foreach ($categories as $key => $value) {
	// 	$name = $value['name'];
	// 	$cId = $value['cId'];
	// 	$link = $value['link'];
	// 	$database->query("INSERT INTO `sunfrog`.`category` (`id`, `name`, `linkSunfrog`, `cId`) VALUES (NULL, '".$name."', '".$link."', '".$cId."')");
	// }


	/* INSERT IMAGES */
	// $categories = Category::get_all();
	// foreach ($categories as $key => $category) {
	// 	$link = $category->linkSunfrog;
	// 	$curl = new curl($link);
	// 	$cId = $category->cId;
	// 	$html = $curl->html;
	// 	$frameit = $html->find(".frameit");
	// 	foreach ($frameit as $key => $frame) {
	// 		$linkTshirt = $frame->find("a",0)->href;
	// 		$src = $frame->find("img",0)->attr['data-src'];
	// 		$title = $frame->find("img",0)->title;
	// 		$title = str_replace("'","\'",$title);
	// 		$link =  urlencode("https://sunfrog.com".$linkTshirt."?".AFFILIATE_ID);
	// 		$pinterest = "https://www.pinterest.com/pin/create/button/?url=".urlencode("http://icytuts.com/red.php?url=https://sunfrog.com".$linkTshirt."?".AFFILIATE_ID."")."&media=http:".rawurlencode($src)."&description=".$title;
	// 		$facebook = "https://www.facebook.com/sharer.php?u=".rawurlencode("https://www.sunfrog.com".$linkTshirt)."?".AFFILIATE_ID;
	// 		$twitter = "https://twitter.com/share?url=".rawurlencode("https://www.sunfrog.com".$linkTshirt)."&text=".rawurlencode($title);
	// 		$googleplus = "https://plus.google.com/share?url=".rawurlencode("https://www.sunfrog.com".$linkTshirt."?".AFFILIATE_ID);
	// 		$database->query("INSERT INTO `sunfrog`.`images` (`id`, `title`, `src`, `linkAffiliate`, `cId`, `pinterest`, `facebook`, `twitter`, `googleplus`) VALUES (NULL, '".$title."', '".$src."', '".$link."', '".$cId."', '".$pinterest."', '".$facebook."', '".$twitter."', '".$googleplus."')");
	// 	}
	// }
	// https://www.sunfrog.com/search/paged2.cfm?schTrmFilter=popular&search=Hillary&cID=0&offset=41

    
    // var_dump($names);
//    foreach ($names as $key => $name ) {
//        $curl = new curl("https://www.sunfrog.com/search/?cId=0&cName=&search=".$name);
//        $shirts = $curl->getShirtByName();
//        if(sizeof($shirts) > 0) {
//            foreach($shirts as $k => $s) {
//                echo $s['link'];
//            }
//        }
//
//    }
//    $curl = new curl("https://www.sunfrog.com/search/?cId=0&cName=&search=");
//	$shirts = $curl->getShirtByName();
//	foreach ($shirts as $key => $value) {
//		$linkTshirt = $value['link'];
//		$src = $value['src'];
//		$title = $value['name'];
//		$title = str_replace("'","\'",$title);
//		print_r($value);
//		if(Images::findByTitle($title) === false) {
//			$link =  urlencode($linkTshirt."?".AFFILIATE_ID);
//			$pinterest = "https://www.pinterest.com/pin/create/button/?url=".urlencode("http://icytuts.com/red.php?url=https://sunfrog.com".$linkTshirt."?".AFFILIATE_ID."")."&media=http:".rawurlencode($src)."&description=".$title;
//			$facebook = "https://www.facebook.com/sharer.php?u=".rawurlencode("https://www.sunfrog.com".$linkTshirt)."?".AFFILIATE_ID;
//			$twitter = "https://twitter.com/share?url=".rawurlencode("https://www.sunfrog.com".$linkTshirt)."&text=".rawurlencode($title);
//			$googleplus = "https://plus.google.com/share?url=".rawurlencode("https://www.sunfrog.com".$linkTshirt."?".AFFILIATE_ID);
//			$database->query("INSERT INTO `sunfrog`.`images` (`id`, `title`, `src`, `linkAffiliate`, `cId`, `pinterest`, `facebook`, `twitter`, `googleplus`) VALUES (NULL, '".$title."', '".$src."', '".$link."', '3', '".$pinterest."', '".$facebook."', '".$twitter."', '".$googleplus."')");
//		}
//	}
	
//		$curl = new curl("https://www.sunfrog.com/search/paged2.cfm?schTrmFilter=popular&search=".$row["name"]."&cID=0&offset=".$offset);
//		$shirts = $curl->getShirtByName();
//		foreach ($shirts as $key => $value) {
//			$linkTshirt = $value['link'];
//			$src = $value['src'];
//			$title = $value['name'];
//			$title = str_replace("'","\'",$title);
//			print_r($value);
////			if(Images::findByTitle($title) === false) {
////				$link =  urlencode($linkTshirt."?".AFFILIATE_ID);
////				$pinterest = "https://www.pinterest.com/pin/create/button/?url=".urlencode("http://icytuts.com/red.php?url=https://sunfrog.com".$linkTshirt."?".AFFILIATE_ID."")."&media=http:".rawurlencode($src)."&description=".$title;
////				$facebook = "https://www.facebook.com/sharer.php?u=".rawurlencode("https://www.sunfrog.com".$linkTshirt)."?".AFFILIATE_ID;
////				$twitter = "https://twitter.com/share?url=".rawurlencode("https://www.sunfrog.com".$linkTshirt)."&text=".rawurlencode($title);
////				$googleplus = "https://plus.google.com/share?url=".rawurlencode("https://www.sunfrog.com".$linkTshirt."?".AFFILIATE_ID);
////				$database->query("INSERT INTO `sunfrog`.`images` (`id`, `title`, `src`, `linkAffiliate`, `cId`, `pinterest`, `facebook`, `twitter`, `googleplus`) VALUES (NULL, '".$title."', '".$src."', '".$link."', '3', '".$pinterest."', '".$facebook."', '".$twitter."', '".$googleplus."')");
////			}
//		}
//	}
	
?>