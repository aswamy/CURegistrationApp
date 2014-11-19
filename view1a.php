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

	$viewType = (isset($_GET['viewType']) ? $_GET['viewType'] : 'HTML');
	
	if($viewType == 'JAVA') {
		include('view1a_display_java.php');
	} else {
		include('view1a_display_html.php');
}
?>
