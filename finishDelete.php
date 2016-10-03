<?php
	$db = new mysqli('localhost', 'spj916', "cs4501", 'spj916');
    if ($db->connect_error):
       die ("Could not connect to db " . $db->connect_error);
    endif;
    
    # deletes selected recipe from database
    $choice = $_POST["choice"];
    $query = "delete from Recipes where Recipes.id = '$choice'";
    $db->query($query) or die ($db->error);
?>