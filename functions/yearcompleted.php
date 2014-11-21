<?php

function getYearCompleted($courses_completed, $degree) {

	$conn = new mysqli('localhost', 'root', '', 'sysc4504');
	$result = $conn->query("SELECT * FROM cu_program_progression WHERE degree_name='$degree' ORDER BY course_year, course_semester");

	while ($row = $result->fetch_assoc()) {
		if($row['course_year_status_weight'] == 1) {
			if (in_array($row['course_name'], $courses_completed))
				$creditsCompleted[$row['course_year']-1] += 0.5;
			$totalCredits[$row['course_year']-1] += 0.5;
		}		
	}

	echo "Year 1 credits: $creditsCompleted[0]/$totalCredits[0]<br>";
	echo "Year 2 credits: $creditsCompleted[1]/$totalCredits[1]<br>";
	echo "Year 3 credits: $creditsCompleted[2]/$totalCredits[2]<br>";
	echo "Year 4 credits: $creditsCompleted[3]/$totalCredits[3]<br>";

	$yearstatus = 1;
	if(!isSecondYearStatus($creditsCompleted, $totalCredits)) return $yearstatus;
	$yearstatus = 2;
	if(!isThirdYearStatus($creditsCompleted, $totalCredits)) return $yearstatus;
	$yearstatus = 3;
	if(!isForthYearStatus($creditsCompleted, $totalCredits)) return $yearstatus;
	$yearstatus = 4;
	return $yearstatus;
}


/*
* The following data is from http://carleton.ca/engineering-design/current-students/undergrad-academic-support/status-vs-standing/
* to determine year status
*/

/*
* 1st Year Status: Admission to the program.
*/
function isFirstYearStatus() {
	return true;
}

/*
* 2nd Year Status: Successful completion of all Engineering, Science and Mathematics course requirements in the first year of the program, all English as a Second Language Requirements, and any additional requirements as determined in the admissions process.
*/
function isSecondYearStatus($creditsCompleted, $totalCredits) {
	if ($creditsCompleted[0] >= $totalCredits[0]) return true;
	return false;
}

/*
* 3rd Year Status: Successful completion of 4.0 credits from the second year requirements of the program.
*/
function isThirdYearStatus($creditsCompleted, $totalCredits) {
	if ($creditsCompleted[0] >= $totalCredits[0] && $creditsCompleted[1] >= 2.0) return true;
	return false;
}

/*
* 4th Year Status: Succcessful completion of all second year requirements and 3.5 credits from the third year requirements of the program.
*/
function isForthYearStatus($creditsCompleted, $totalCredits) {
	if ($creditsCompleted[0] >= $totalCredits[0] && $creditsCompleted[1] >= $totalCredits[1] && $creditsCompleted[2] >= 3.5) return true;
}

?>