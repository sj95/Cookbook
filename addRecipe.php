<?php
	$db = new mysqli('localhost', 'spj916', "cs4501", 'spj916');
    if ($db->connect_error):
       die ("Could not connect to db " . $db->connect_error);
    endif;
    
    # adds user's recipe to database
    $arr = $_POST["theArray"];
    $query = "insert into Recipes values (NULL, '$arr[0]', '$arr[1]', '$arr[4]', '$arr[3]', '$arr[2]')";
    $db->query($query) or die ($db->error);
?>