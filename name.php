<?php 

	include 'base/craw.php';
	include 'base/config.php';

	$html = file_get_html("http://www.behindthename.com/top/lists/united-states/2009");

	$names = $html->find('body > div.body-wrapper > div > table:nth-child(7) > tbody > tr > td:nth-child(1) td>a[href*="/name/"]');

	$selectquery = "SELECT * FROM `name`";
	$result = mysqli_query($conn, $selectquery);
	$allNames = array();
	if (mysqli_num_rows($result) > 0) {
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result)) {
	        array_push($allNames, $row['name']);
	    }
	} else {
	    echo "0 results";
	}
	
	
	foreach ($names as $key => $value) {
		if($key > 0 && $value->plaintext !== '' && !in_array($value->plaintext, $allNames)) {
			$name = $value->plaintext;
			$sql = "INSERT INTO `name` (`id`, `name`) VALUES (NULL, '$name')";
			if (mysqli_query($conn, $sql)) {
			    echo "New record created successfully";
			} else {
			    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
	}
	mysqli_close($conn);
?>