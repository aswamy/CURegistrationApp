<?php
	echo "course_name,course_year,course_semester,course_size;";
	foreach($courses_array as $course) {
		echo "$course[course_name],$course[course_year],$course[course_semester],$course[course_size];";
	}
?>