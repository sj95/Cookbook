<?php
	$db = new mysqli('localhost', 'spj916', "cs4501", 'spj916');
    if ($db->connect_error):
       die ("Could not connect to db " . $db->connect_error);
    endif;
    
    # picks recipes by specified category and sorts by rating
    $type = $_POST["type"];
    $query = "select * from Recipes where Recipes.type = '$type' order by rating";
    $result = $db->query($query);
    $arr = [];
    $arr[0] = $type;
    $i = 2;
    while ($row = $result->fetch_array()) {
    	$arr[$i] = $row[2];
    	$arr[$i+1] = $row[5];
    	$i = $i + 2;
    }
    $arr[1] = $i;
    echo json_encode($arr);
?>