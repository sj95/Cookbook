<?php
    $db = new mysqli('localhost', 'spj916', "cs4501", 'spj916');
    if ($db->connect_error):
       die ("Could not connect to db " . $db->connect_error);
    endif;

    $recipe = $_POST["recipe"];
   
   	# gets chosen recipe and returns it with json
	$query = "select * from Recipes where Recipes.title = '$recipe'";
   	$result = $db->query($query);
   	$arr = [];
   	$item = $result->fetch_array();
   	$arr[0] = $item[1];
   	$arr[1] = $recipe;
   	$arr[2] = $item[6];
   	$arr[3] = $item[5];
   	$chunks = explode("#", $item[3]);
   	$chunks2 = explode("#", $item[4]);
   	for($i = 0; $i < sizeof($chunks); $i++) {
   		$ingred = [];
   		$ingred[0] = $chunks[$i];
   		$ingred[1] = $chunks2[$i];
   		$arr[$i+4] = $ingred;
   	}
    echo json_encode($arr); 
?>