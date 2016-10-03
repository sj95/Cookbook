<?php

$db = new mysqli('localhost', 'spj916', "cs4501", 'spj916');

if ($db->connect_error): 
 die ("Could not connect to db " . $db->connect_error); 
endif;

$db->query("drop table Recipes"); 

$result = $db->query("create table Recipes (id int primary key not null auto_increment, type varchar(30), title varchar(30), ingredients varchar(100), quantities varchar(100), directions varchar(50), rating int)") or die ("Invalid: " . $db->error);

# retrieve xml through JSON and insert into database
$xml=simplexml_load_file("cookbook.xml") or die("Error: Cannot create object");
$json = json_encode($xml);
$array = json_decode($json,TRUE);
foreach ($array["recipe"] as $food) {
    $type = $food["type"];
    $title = $food["title"];
    $ingredients = $food["ingredients"];
    $quantities = $food["quantities"];
    $directions = $food["directions"];
    $rating = $food["rating"];
    $query = "insert into Recipes values (NULL, '$type', '$title', '$ingredients', '$quantities', '$directions', '$rating')"; 
    $db->query($query) or die ("Invalid insert " . $db->error);
} 
echo "Successfully initialized!";
header("Location: introscreen.html");
?>
