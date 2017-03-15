<?php 
            include "../curl.php";
            for ($i=1; $i < 50; $i++) {
                $offset = $i*40 + 1;
                $curl = new curl("https://www.sunfrog.com/search/paged2.cfm?schTrmFilter=popular&search=jena&cID=0&offset=".$offset);
                $shirts = $curl->getShirtByName();
                if(sizeof($shirts) > 0) {
                    foreach($shirts as $k => $s) {
                        echo '<img src="'.$s['src'].'" alt="">';
                        echo "<br>";
                    }
                }
                $curl = null;
            }

?>