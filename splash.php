<?php
	echo "<form method='GET' action='programtree.php'>";
	echo "<table>";

	// Get all the degree names from the database and put it in a select box
	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}
	$degree_query = 'SELECT DISTINCT degree_name FROM cu_program_progression';
	$degree_query_rs = $conn->query($degree_query);
	$degree_array = $degree_query_rs->fetch_all(MYSQLI_ASSOC);
	
	echo '<tr><td>Degree:</td>';
	echo '<td><select name="degree">';

	foreach($degree_array as $degree) {
		echo "<option value='$degree[degree_name]'>$degree[degree_name]</option>";
	}
	echo '</td></select>';


	// A radio button to see if the student is on track
	echo 
	'<tr>
		<td>On Track:</td>
		<td>
		<input onchange="isOnTrack(this)" checked type="radio" name="ontrack" value="true">Yes</input><input onchange="isOnTrack(this)" type="radio" name="ontrack" value="false">No</input>
		</td>
	</tr>';

	// If the student is on track, let him/her choose, what year he/she is in
	echo 
	'<tr>
		<td>Year Status:</td>
		<td>
			<select name="yearstatus" id="yearstatus"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>
		</td>
	</tr>';

	echo '</table>';

	echo '<input type="submit" value="Done"/></form>';

	echo '<script src="js/splash.js"></script>';
?>