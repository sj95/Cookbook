<html>
	<head>
	<?php session_start(); ?>
	<link rel = "stylesheet" type = "text/css" href = "exer4.css"/>
	
	<!-- div that contains contents of any given recipe -->
	<div id = "div1"><table id = "theTable"></table>
	<button onclick = "flipBack()">Return</button>
	<button onclick = "setUpSaveChanges()">Save</button>
	<button id = "addit" onclick = "finishAdd()">Add</button>
	<button onclick = "addIngredient()">Add Ingredient</button>
	</div>
	<div id="div2">
	<form action = "switchType.php"
		  method = "POST">
	<table id = "theOtherTable" border = "1" border-spacing = "5px">
		<tr><th></th><th><?php echo $_POST["category"]; ?></th><th>Rating</th></tr>
	<?php
	
		# sets up initial table
		$db = new mysqli('localhost', 'spj916', "cs4501", 'spj916');
		if ($db->connect_error): 
 		die ("Could not connect to db " . $db->connect_error); 
		endif;
		$cat = $_POST["category"];
		$query = "select * from Recipes where Recipes.type = '$cat'";
		$result = $db->query($query) or die ($db->error);
		$rows = $result->num_rows;
		for ($i = 1; $i <= $rows; $i++) {
		$row = $result->fetch_array();
	?>
	<tr><td><input type = "radio" name = "choice" value = "<?php echo $row[0]; ?>"></td>
	<td><a name = "<?php echo $row[2]; ?>" class = "recipe"><?php echo $row[2]; ?></a></td>
	<td><?php echo $row[6]; ?></td></tr>
	<?php } ?>
	</table>
	<input type = "hidden" name = "genre" value = "<?php echo $row[1]; ?>">
	
	<!-- choose where to move recipe to -->
	<input type = "submit" value = "Move">
	<select name = "selection">
		<option value = "Entree">Entree</option>
		<option value = "Appetizer">Appetizer</option>
		<option value = "Dessert">Dessert</option>
		<option value = "Favorite">Favorite</option>
	</select>
	</form>
	<button onclick = "addRecipe()">Add</button>
	<button onclick = "deleteRecipe()">Delete</button>
	<button onclick = "sortRecipes()">Sort</button>
	<form action = "homescreen.html"
		  method = "POST">
	<input type = "submit" value = "Return">
	</form>
	</div>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript">

		// these three functions set up the table for the chosen recipe
    	$(document).ready(function() {
       		$("a").click(function(ev)
            {
              var el = $(this);
              loadify(el, ev);
          	}
       		);
    	});
       
    	function loadify(el, ev)
    	{	
        	ev.preventDefault();
        	var rcp = $(el).attr("name");
        	setUpRecipe(rcp);
    	}
    	
    	function setUpRecipe(rcp) {
    		$.post("getRecipe.php", {recipe:rcp}, function(data) {
    			$("#div1").toggle();
              	$("#div2").toggle();
    			arr = JSON.parse(data);
				var table = "<tr><td>Category</td><td><input type = 'text' name = 'category' value = '" + arr[0] + "'</td></tr>";
				table += "<tr><td>Title</td><td><input type = 'text' name = 'title' value = '" + arr[1] + "'</td></tr>";
				table += "<tr><td>Rating</td><td><input type = 'text' name = 'rating' value = '" + arr[2] + "'</td></tr>";
				table += "<tr><td>Directions</td><td><input type = 'text' name = 'directions' value = '" + arr[3] + "'</td></tr>";
    			for (i = 4; i < arr.length; i++) {
    				table += "<tr><td>Ingredient</td><td><input type = 'text' name = 'ingredient" + (i - 3) + "' value = '" + arr[i][0] + "'</td>";
    				table += "<td>Quantity</td><td><input type = 'text' name = 'quantity" + (i - 3) + "' value = '" + arr[i][1] + "'</td></tr>";
    				localStorage.num = i;
    			}
    			localStorage.recipe = arr[1];
    			$("#theTable").html(table);	
    		});
    	}
    	
    	// function for button that returns to menu
    	function flipBack() {
    		$("#div1").toggle();
        	$("#div2").toggle();
    	}
    	
    	// these two functions keep track of the user's changes to the recipe
    	function setUpSaveChanges() {
    		$("#div1").toggle();
        	$("#div2").toggle();
        	arr = [];
        	arr2 = [];
        	arr[0] = $("input[name=category]").val();
        	arr[1] = $("input[name=title]").val();
        	arr[2] = $("input[name=rating]").val();
        	arr[3] = $("input[name=directions]").val();
        	num = Number(localStorage.num);
        	for (i = 4; i <= num; i++) {
        		ingredName = [];
        		var ingName = $("input[name=ingredient" + (i - 3) + "]").val();
        		var quanName = $("input[name=quantity" + (i - 3) + "]").val();
        		if (!(ingName === "" || quanName === "")) {
        			ingredName[0] = $("input[name=ingredient" + (i - 3) + "]").val();
        			ingredName[1] = $("input[name=quantity" + (i - 3) + "]").val();
        			arr2[i] = ingredName;
        		}
        	}
        	recipe = localStorage.recipe;
        	saveChanges(arr, recipe, arr2, num);
    	}
    	
    	function saveChanges(arr, recipe, arr2, num) {
    		$.post("updateData.php", {theArray:arr, recipe:recipe, theOtherArray:arr2, num:num}, function(data) {
    			arr = JSON.parse(data);
    			$("#theOtherTable tr").remove();
    			var table = "<tr><th></th><th>" + arr[0] + "</th><th>Rating</th></tr>";
    			for (i = 2; i < arr[1]; i = i + 2) {
    				table += "<tr><td><input type = 'radio' name = 'choice' value = '" + arr[i] + "'></td>";
    				table += "<td><a onclick = 'setUpRecipe(\"" + arr[i] + "\")' name = '" + arr[i] + "' class = 'recipe'>" + arr[i] + "</a></td>";
    				table += "<td>" + arr[i+1] + "</td></tr>";
    			}
    			$("#theOtherTable").html(table);
    		});
    	}
    	
    	// these three functions create a form to add a recipe
    	function addRecipe() {
    		$("#div1").toggle();
        	$("#div2").toggle();
        	$("#addit").toggle();
        	$("#theTable tr").remove();
        	var table = "<tr><td>Category</td><td><input type = 'text' name = 'newCat'></td></tr>";
        	table += "<tr><td>Title</td><td><input type = 'text' name = 'newTitle'></td></tr>";
        	table += "<tr><td>Rating</td><td><input type = 'text' name = 'newRate'></td></tr>";
        	table += "<tr><td>Directions</td><td><input type = 'text' name = 'newDir'></td></tr>";
        	table += "<tr><td>Ingredient</td><td><input type = 'text' name = 'newIng1'></td></tr>";
        	table += "<tr><td>Ingredient</td><td><input type = 'text' name = 'newIng2'></td></tr>";
        	table += "<tr><td>Ingredient</td><td><input type = 'text' name = 'newIng3'></td></tr>";
        	table += "<tr><td>Ingredient</td><td><input type = 'text' name = 'newIng4'></td></tr>";
        	$("#theTable").html(table);
    	}
    	
    	function finishAdd() {
    		$("#div1").toggle();
        	$("#div2").toggle();
        	$("#addit").toggle();
        	arr = [];
        	arr[0] = $("input[name=newCat]").val();
        	arr[1] = $("input[name=newTitle]").val();
        	arr[2] = $("input[name=newRate]").val();
        	arr[3] = $("input[name=newDir]").val();
        	arr[4] = "";
        	for (i = 1; i < 5; i++) {
        		temp = $("input[name=newIng" + i + "]").val();
        		if (temp.length > 0)
        			arr[4] += temp + "#";
        	}
        	actuallyFinishAdd(arr);
    	}
    	
    	function actuallyFinishAdd() {
    		$.post("addRecipe.php", {theArray:arr}, function(data) {
    		});
    	}
    	
    	// these two functions remove the selected recipe from the database
    	function deleteRecipe() {
    		choice = $("input[type='radio'][name='choice']:checked").val();
    		finishDelete(choice);
    	}
    	
    	function finishDelete(choice) {
    		$.post("finishDelete.php", {choice:choice}, function(data) {
    		});
    	}
    	
    	// adds another text box to the form to add another ingredient
    	function addIngredient() {
    		num = Number(localStorage.num);
    		num++;
    		localStorage.num = num;
    		var newRow = "<tr><td>Ingredient</td><td><input type = 'text' name = 'ingredient" + (num-3) + "'</td>";
    		newRow += "<td>Quantity</td><td><input type = 'text' name = 'quantity" + (num-3) + "'</td></tr>";
    		$("#theTable").append(newRow);
    	}
    	
    	// sorts the recipes of the given type by rating
    	function sortRecipes() {
    		genre = $("input[name=genre]").val();
    		returnSort(genre);
    	}
    	
    	function returnSort(genre) {
    		$.post("returnSorted.php", {type:genre}, function(data) {
    			arr = JSON.parse(data);
    			$("#theOtherTable tr").remove();
    			var table = "<tr><th></th><th>" + arr[0] + "</th><th>Rating</th></tr>";
    			for (i = 2; i < arr[1]; i = i + 2) {
    				table += "<tr><td><input type = 'radio' name = 'choice' value = '" + arr[i] + "'></td>";
    				table += "<td><a onclick = 'setUpRecipe(\"" + arr[i] + "\")' name = '" + arr[i] + "' class = 'recipe'>" + arr[i] + "</a></td>";
    				table += "<td>" + arr[i+1] + "</td></tr>";
    			}
    			$("#theOtherTable").html(table);
    		});
    	}
	</script>
</head>
<body>	
</body>
</html>