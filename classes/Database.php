<?php


	class DataBase {

		function __construct($db){
			$host="localhost";
			$user="root";
			$password="";
			if($db!=""){
				$this->connection = mysqli_connect($host, $user, $password, $db);
				}
			else{
				$this->connection = mysqli_connect($host, $user, $password);
				}
		}

		function execute($sql){
			echo $sql."</br>";
			$courses_query_rs = $this->connection->query($sql);
			return $courses_query_rs->fetch_all(MYSQLI_ASSOC);
		}

		function update($sql){
			return $this->connection->query($sql);
		}
		
		function create($db){
			$this->connection->query("CREATE DATABASE IF NOT EXISTS $db");
		}
		
		function getError(){
			return mysqli_error($this->connection);
		}

		function getPrereqs($degree, $year_status, $term) {
			$sql = "SELECT p.*, o.course_prerequisite, o.course_has_lab FROM cu_program_progression p LEFT JOIN cu_offered_courses o ON p.course_name = o.course_name WHERE p.degree_name='$degree' AND p.course_year = $year_status AND p.course_semester = '$term' ORDER BY p.course_name DESC";
			return $this->execute($sql);
		}

		function getNameInCourses($degree, $year_status, $inCourses, $term) {
			$sql = "SELECT course_name FROM cu_program_progression WHERE degree_name = '$degree' AND course_year = $year_status AND course_name IN $inCourses AND course_semester = '$term' ORDER BY course_name DESC";
			return $this->execute($sql);
		}

		function getNameSectionPairs($inCourses, $term) {
			$sql = "SELECT course_name, course_section FROM cu_running_courses WHERE course_name IN $inCourses AND (seats_left > 0 OR seats_left = -1) AND course_semester = '$term'";
			return $this->execute($sql);
		}
		
		function import($pathToFile){
			$lines = file($pathToFile);
			$buff = '';
			
			//Since we don't have access to an SQL parser this function parses SQL
			foreach($lines as $line) {
				//Lines that start with -- are comments. Ignore them
				//Also ignore empty lines
				if ($line == '' || substr($line,0,2) == '--')
					continue;
				// If we get actual content, add it to the buffer
				$buff .= $line;
				//Lines that end with a semicolon need to be executed.
				if (substr(trim($line), -1, 1) == ';')
				{
					$this->connection->query($buff);
					// Reset the buffer now that we executed a thing
					$buff = '';
				}
			}
			
		}

	}


?>
