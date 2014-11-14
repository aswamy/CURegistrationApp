<?php
	// Get all the degree names from the database and put it in a select box
	$conn = @new mysqli('localhost', 'root', '', 'sysc4504'); //@ suppresses warning output from being sent to client
	if ($conn->connect_error) { // check the error ourselves and return a custom message instead of "ugly" warning
		http_response_code(503); // Service is unavailable
		echo "<p>The database service is currently unavailable. Please try again</p>";
		exit();
	}
	$degree_query = 'SELECT DISTINCT degree_name FROM cu_program_progression';
	$degree_query_rs = $conn->query($degree_query);
	$degree_array = $degree_query_rs->fetch_all(MYSQLI_ASSOC);	
?>

<head>
<link rel="stylesheet" type="text/css" href="css/Splash.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
</head>
<body style="background-color: gray">
<div class='titleContainer'>
	<div>
		<img class='titleImage' src='img/logo-cu-shield-outlined.svg' />
	</div>
	<span class='title'>Carleton University Registration App</span>
</div>
<div class='mainContainer'>
	<form class='inputForm' method='GET' action='view1b.php'>
		<table class='inputsContainer'>

		<!-- Student number to tell who is logging in -->
		<tr>
		<td>Student #:</td>
		<td>
			<input class="inputField" name="studentnum" type="text"></input>
		</td>
		</tr>

		<!-- Which degree is the user in -->
		<tr>
		<td>Degree:</td>
		<td>
			<select class="inputField" id="degree" name="degree">
			<?php
				foreach($degree_array as $degree) {
					echo "<option value='$degree[degree_name]'>$degree[degree_name]</option>";
				}
			?>
			</select>
		</td>
		</tr>

		<!-- A radio button to see if the student is on track -->
		<tr>
		<td>On Track:</td>
		<td>
			<input class="inputField" onchange="isOnTrack(this)" checked type="radio" name="ontrack" id="ontrack" value="true">Yes</input><input onchange="isOnTrack(this)" type="radio" name="ontrack" value="false">No</input>
		</td>
		</tr>
		<!-- If the student is on track, let him/her choose, what year he/she is in-->
		<tr>
		<td>Years Completed:</td>
		<td>
			<select class="inputField" name="yearscompleted" id="yearscompleted"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>
		</td>
		</tr>
		</table>
		<input type="submit" value="Done"/>
	</form>
</div>
<script src="js/splash.js"></script>
</body>