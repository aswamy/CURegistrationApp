<?php

session_start();

$_SESSION['student_num'] = (isset($_GET['studentnum']) ? $_GET['studentnum'] : null);
$_SESSION['degree'] = (isset($_GET['degree']) ? $_GET['degree'] : null);
$_SESSION['on_track'] = (isset($_GET['ontrack']) ? $_GET['ontrack'] : 'false');
$_SESSION['registering_year'] = (isset($_GET['registeringyear']) ? $_GET['registeringyear'] : 1);
$_SESSION['registering_semester'] = (isset($_GET['registeringsemester']) ? $_GET['registeringsemester'] : 'fall');

$conn = new mysqli('localhost', 'root', '', 'sysc4504');
if ($conn->connect_error) {
	trigger_error('Connection to database has failed');
}

$courses_query = "SELECT p.*, o.course_prerequisite, o.course_size FROM cu_program_progression p LEFT JOIN cu_offered_courses o ON p.course_name = o.course_name WHERE p.degree_name='$_SESSION[degree]' ORDER BY p.course_year, p.course_semester";
$courses_query_rs = $conn->query($courses_query);
$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);

$viewType = (isset($_GET['viewType']) ? $_GET['viewType'] : 'HTML');

if($viewType == 'JAVA') {
	include('view1b_display_java.php');
} else {
	include('view1b_display_html.php');
}
?>