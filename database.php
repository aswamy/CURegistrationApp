<?php


	class DataBaseClass {

		function __construct($db){
			$host="localhost";
			$user="root";
			$password="";
			if($db!="")
				$this->connection = mysqli_connect($host, $user, $password, $db);
			else
				$this->connection = mysqli_connect($host, $user, $password);
		}

		function execute($sql){
			echo $sql."</br>";
			$courses_query_rs = $this->connection->query($sql);
			return $courses_query_rs->fetch_all(MYSQLI_ASSOC);
		}
		
		function getError(){
			return mysqli_error($this->connection);
		}

		function getPrereqs($degree, $year_status, $term) {
			$sql = "SELECT p.*, o.course_prerequisite, o.course_has_lab FROM cu_program_progression p LEFT JOIN cu_offered_courses o ON p.course_name = o.course_name WHERE p.degree_name='$degree' AND p.course_year = $year_status AND p.course_semester = '$term' ORDER BY p.course_year, p.course_semester";
			return $this->execute($sql);
		}

		function getNameInCourses($degree, $year_status, $inCourses, $term) {
			$sql = "SELECT course_name FROM cu_program_progression WHERE degree_name = '$degree' AND course_year = $year_status AND course_name IN $inCourses AND course_semester = '$term'";
			return $this->execute($sql);
		}

		function getNameSectionPairs($inCourses, $term) {
			$sql = "SELECT course_name, course_section FROM cu_running_courses WHERE course_name IN $inCourses AND (seats_left > 0 OR seats_left = -1) AND course_semester = '$term'";
			return $this->execute($sql);
		}

	}


?>