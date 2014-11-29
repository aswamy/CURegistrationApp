<head>
	<link rel="stylesheet" type="text/css" href="css/PrerequisiteTree.css" />
	<link rel="stylesheet" type="text/css" href="css/common.css" />
</head>
<body>
	<?php include 'header.html';?>
	<div class="mainMessage">
		* Select completed courses:
	</div>
	<div class='mainContainer'><form method='POST' action='view2a.php'>
		<table style='margin: 0 auto'>
			<tr class='courseRow'>

			<?php

			// Render the entire table in PHP to avoid slow client side rendering
			$course_prereq_json = "{";
			$course_current_year = 0;
			$course_current_semester = '';

			// When a user says he has completed a year of courses, check all of those courses
			// and don't allow him/her to uncheck those boxes
			$course_completed = 'checked onclick="return false"';

			foreach($courses_array as $course) {
				$course_name = $course['course_prerequisite'];

				if($course['course_semester'] != $course_current_semester || $course['course_year'] != $course_current_year) {

					if($course_current_year != 0) {
						echo '</td>';
						if($course['course_year'] != $course_current_year) echo '<td style="width:10px; background-color: gray"></td>';
					}

					$course_current_year = $course['course_year'];
					$course_current_semester = $course['course_semester'];

					if($course_current_year >= $_SESSION['registering_year'] || $_SESSION['on_track'] == 'false') $course_completed = '';
					echo "<td class='courseAlignment'><div class='courseTitle'>Year $course_current_year, $course_current_semester</div>";
				}

				$course_is_full_year = ($course['course_size']=='year') ? '(Full Year)' : '';

				echo "<div class=courseElement id=$course[course_name] onmouseover=highlightPrerequisites(this) onmouseout=restoreCourseElements()><div>$course[course_name]</div><div>$course_is_full_year</div><br><div><input name='$course[course_name]' $course_completed type='checkbox'></input></div></div>";

				if ($course_name != null) {
					$course_prereq_json .= "\"$course[course_name]\" : $course[course_prerequisite],";
				}
			}

			$course_prereq_json = rtrim($course_prereq_json, ',');
			$course_prereq_json .= "}";
			
			?>
			</tr>
		</table>
		<br><input type="submit" value="Submit"/></form>
	</div>
	<p>Prerequisite: <span id="displayPreq"></span></p>
</body>	
<script>

function highlightPrerequisites(el) {
	
	var prereq_json_all = <?php echo $course_prereq_json?>;
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
	document.getElementById('displayPreq').innerHTML = '';
}
</script>