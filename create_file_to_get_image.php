<?php 
    include 'base/craw.php';
    include 'base/config.php';

    $q = "SELECT * FROM `name`";
    $rs = mysqli_query($conn, $q);
    $names = array();
    if (mysqli_num_rows($rs) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($rs)) {
            array_push($names, $row['name']);
        }
    }

    foreach ($names as $key => $name) {
        error_reporting(E_ALL);
        $name = strtolower($name);
        $newFileName = './pages/'.$name.".php";
        $newFileContent = '<?php 
            include "../curl.php";
            for ($i=1; $i < 50; $i++) {
                $offset = $i*40 + 1;
                $curl = new curl("https://www.sunfrog.com/search/paged2.cfm?schTrmFilter=popular&search='.$name.'&cID=0&offset=".$offset);
                $shirts = $curl->getShirtByName();
                if(sizeof($shirts) > 0) {
                    foreach($shirts as $k => $s) {
                        echo \'<img src="\'.$s[\'src\'].\'" alt="">\';
                        echo "<br>";
                    }
                }
                $curl = null;
            }

?>';

        if (file_put_contents($newFileName, $newFileContent) !== false) {
            echo "File created (" . basename($newFileName) . ")";
        } else {
            echo "Cannot create file (" . basename($newFileName) . ")";
        }
    }
?>