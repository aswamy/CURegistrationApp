<?php

	echo '<link rel="stylesheet" type="text/css" href="css/PrerequisiteTree.css" />';
	echo "<form method='POST' action='splash.php'>";

	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}

	$courses_query = "SELECT p.*, o.course_prerequisite FROM cu_program_progression p LEFT JOIN cu_offered_courses o ON p.course_name = o.course_name WHERE p.degree_name='$_GET[degree]' ORDER BY p.course_year, p.course_semester";
	$courses_query_rs = $conn->query($courses_query);
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);

	$course_prereq_json = "{";

	foreach($courses_array as $course) {
		$course_name = $course['course_name'];

		if ($course_name != 'S_ELECTIVE' && $course_name != 'E_ELECTIVE' && $course_name != 'C_ELECTIVE') {
			echo "<div class=courseElement id=$course[course_name] onmouseover=highlightPrerequisites(this) onmouseout=restoreCourseElements()>$course[course_name] in $course[course_semester] in year $course[course_year]</div>";
			$course_prereq_json .= "\"$course[course_name]\" : $course[course_prerequisite],";
		} else {
			echo "<div class=courseElement id=$course[course_name]>$course[course_name] in $course[course_semester] in year $course[course_year]</div>";
		}
	}

	$course_prereq_json = rtrim($course_prereq_json, ',');
	$course_prereq_json .= "}";

	echo '<input type="submit" value="Done"/></form>';

	echo $course_prereq_json;

	echo
	"<script>

	function highlightPrerequisites(el) {
		
		var prereq_json_all = $course_prereq_json;
		var prereq_list = prereq_json_all[el.id]['courses'];
		
		for (var prereq_index in prereq_list) {
			var prereq = prereq_list[prereq_index];
			for (var course_index in prereq) {				
				var element = document.getElementById(prereq[course_index]['name']);
				if (element != null) {
					element.style.background='yellow';
				}
			}
		}
	}

	function restoreCourseElements() {
		var elements = document.querySelectorAll('.courseElement');

		for (var i = 0; i < elements.length; i++) {
			elements[i].style.background='lightgray';
		}
	}
	</script>";
	
?>