<?php

	require_once 'classes/Database.php';

/**
* Meant to calc year status (**Not to be confused with year completed**)
* Year completed = all credits offered that year is complete
* Year status = defined by this page http://carleton.ca/engineering-design/current-students/undergrad-academic-support/status-vs-standing/
* NOTE: CCDP2100 + science electives + complementary electives do not count for calculating year status
*/
function getYearStatus($courses_completed, $degree) {

	$yearstatus = 1;

	//Credits completed in each year of degree. 0 => Year 1, 1 => Year 2, 3 => Year 3, 4 => Year 4
	$creditsCompleted[0] = 0.0;
	$creditsCompleted[1] = 0.0;
	$creditsCompleted[2] = 0.0;
	$creditsCompleted[3] = 0.0;

	// Total available credits each year of degree
	// **CCDP2100, science electives, and complementary electives don't count!
	$totalCredits[0] = 0.0;
	$totalCredits[1] = 0.0;
	$totalCredits[2] = 0.0;
	$totalCredits[3] = 0.0;


	$db = new Database("sysc4504");
	$result = $db->getDegreeCourses($degree);

	foreach ($result as $row) {
		if($row['course_year_status_weight'] == 1) {
			if (in_array($row['course_name'], $courses_completed))
				$creditsCompleted[$row['course_year']-1] += 0.5;
			$totalCredits[$row['course_year']-1] += 0.5;
		}

	}

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