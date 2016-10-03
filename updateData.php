<?php
	$db = new mysqli('localhost', 'spj916', "cs4501", 'spj916');
    if ($db->connect_error):
       die ("Could not connect to db " . $db->connect_error);
    endif;
    
    # updates recipe's info in the database
    $arr = $_POST["theArray"];
    $recipe = $_POST["recipe"];
    $arr2 = $_POST["theOtherArray"];
    $num = $_POST["num"];
    $ing = "";
    $quan = "";
    for ($i = 4; $i <= $num; $i++) {
    	$ing .= $arr2[$i][0];
    	if (strcmp($arr2[$i][0],"") != 0)
    		$ing .= "#";
    	$quan .= $arr2[$i][1];
    	if (strcmp($arr2[$i][1],"") != 0)
    		$quan .= "#";
    }
    $ing .= $arr2[$i];
    $query = "select * from Recipes where Recipes.title = '$recipe'";
    $result = $db->query($query);
    $item = $result->fetch_array();
    $type = $item[1];
    $query = "update Recipes set type = '$arr[0]', title = '$arr[1]', rating = '$arr[2]', directions = '$arr[3]', ingredients = '$ing', quantities = '$quan' where title = '$recipe'";
    $db->query($query) or die ($db->error);
    $query = "select * from Recipes where Recipes.type = '$type'";
    $result = $db->query($query);
    $rows = $result->num_rows;
    $arr3 = [];
    $arr3[0] = $type;
    $arr3[1] = 2*$rows+1;
    for ($i = 2; $i <= 2*$rows+1; $i = $i + 2) {
    	$item = $result->fetch_array();
    	$arr3[$i] = $item[2];
    	$arr3[$i+1] = $item[6];
    }
    echo json_encode($arr3);
?>