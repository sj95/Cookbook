<?php
	$db = new mysqli('localhost', 'spj916', "cs4501", 'spj916');
    if ($db->connect_error):
       die ("Could not connect to db " . $db->connect_error);
    endif;
    
    # script to edit recipe's category
    $choice = $_POST["choice"];
    echo $choice;
    $pick = $_POST["selection"];
    echo $pick;
    $query = "update Recipes set type = '$pick' where id = '$choice'";
    $db->query($query) or die ($db->error);
    header("Location: homescreen.html");
?>