<?php

	echo '<link rel="stylesheet" type="text/css" href="css/PrerequisiteTree.css" />';
	echo "<form method='GET' action='splash.php'>";

	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	if ($conn->connect_error) {
		trigger_error('Connection to database has failed');
	}

	$courses_query = "SELECT p.*, o.course_prerequisite FROM cu_program_progression p LEFT JOIN cu_offered_courses o ON p.course_name = o.course_name WHERE p.degree_name='$_GET[degree]' ORDER BY p.course_year, p.course_semester";
	$courses_query_rs = $conn->query($courses_query);
	$courses_array = $courses_query_rs->fetch_all(MYSQLI_ASSOC);

	$course_prereq_json = "{";
	$course_current_year = 1;
	$course_current_semester = 'fall';

	echo "<div><div style='float:left'><div>Year $course_current_year, $course_current_semester</div>";

	foreach($courses_array as $course) {
		$course_name = $course['course_prerequisite'];
		
		if($course['course_semester'] != $course_current_semester || $course['course_year'] != $course_current_year) {
			$course_current_year = $course['course_year'];
			$course_current_semester = $course['course_semester'];			
			echo "</div><div style='float:left'><div>Year $course_current_year, $course_current_semester</div>";
		}
		
		echo "<div class=courseElement id=$course[course_name] onmouseover=highlightPrerequisites(this) onmouseout=restoreCourseElements()><div>$course[course_name]</div><div><input name='$course[course_name]' type='checkbox'></input></div></div>";

		if ($course_name != null) {
			$course_prereq_json .= "\"$course[course_name]\" : $course[course_prerequisite],";
		}
	}

	echo '</div></div>';

	$course_prereq_json = rtrim($course_prereq_json, ',');
	$course_prereq_json .= "}";

	echo '<p id="displayPreq"></p>';
	
	
	echo '<input type="submit" value="Done"/></form>';

	echo $course_prereq_json;

	echo
	"<script>

	function highlightPrerequisites(el) {
		
		var prereq_json_all = $course_prereq_json;
		var prereq_json = prereq_json_all[el.id];

		if (prereq_json != null) {
			var prereq_list = prereq_json['courses'];			
			var prereq_display_message = '';
			
			for (var prereq_index in prereq_list) {
				var prereq = prereq_list[prereq_index];
				for (var course_index in prereq) {
					var element = document.getElementById(prereq[course_index]['name']);
					if (course_index == 0) {
						prereq_display_message += '(';
					}
					prereq_display_message += prereq[course_index]['name'];
					if (element != null) {
						element.style.background='yellow';
					}
					if (course_index < prereq.length-1) {
						prereq_display_message += ' OR ';
					}
					if (course_index == prereq.length-1) {
						prereq_display_message += ')';
					}
				}
				if (prereq_index < prereq_list.length-1) {
					prereq_display_message += ' AND ';
				}
			}
			document.getElementById('displayPreq').innerHTML = prereq_display_message;
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