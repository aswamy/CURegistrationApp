<?php
	echo '<body style="background-color: gray">';
	echo '<link rel="stylesheet" type="text/css" href="css/Splash.css" />';
	include('header.html');
	echo "<div class='mainContainer'><form class='inputForm' method='GET' action='programtree.php'>";
	echo "<table class='inputsContainer'>";
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
	
	echo '<tr><td>Student #:</td>';
	echo '<td><input class="inputField" name="studentnum" type="text"></input></td>';
	echo '<tr><td>Degree:</td>';
	echo '<td><select class="inputField" id="degree" name="degree">';
	foreach($degree_array as $degree) {
		echo "<option value='$degree[degree_name]'>$degree[degree_name]</option>";
	}
	echo '</select></td></tr>';
	// A radio button to see if the student is on track
	echo 
	'<tr>
		<td>On Track:</td>
		<td>
		<input class="inputField" onchange="isOnTrack(this)" checked type="radio" name="ontrack" id="ontrack" value="true">Yes</input><input onchange="isOnTrack(this)" type="radio" name="ontrack" value="false">No</input>
		</td>
	</tr>';
	// If the student is on track, let him/her choose, what year he/she is in
	echo 
	'<tr>
		<td>Years Completed:</td>
		<td>
			<select class="inputField" name="yearscompleted" id="yearscompleted"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>
		</td>
	</tr>';
	echo '</table>';
	echo '<input type="submit" value="Done"/></form>';
	echo '</form></div></body>';
	echo '<script src="js/splash.js"></script>';
?>