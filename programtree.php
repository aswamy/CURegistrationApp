<?php

	echo "<form method='GET' action='splash.php'>";

	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}

	$courses_query = "SELECT * FROM cu_program_progression WHERE degree_name='$_GET[degree]' ORDER BY course_year, course_semester";
	$courses_query_rs = $conn->query($courses_query);
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);

	foreach($courses_array as $course) {
		echo "$course[course_name] in $course[course_semester] in year $course[course_year]<br />";
	}

	echo '<input type="submit" value="Done"/></form>';

?>