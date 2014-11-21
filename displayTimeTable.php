<?php
	require_once 'timeTableClass.php';
	session_start();
	//$selected = array("SYSC4405-B", "SYSC4405-B1O", "SYSC4504-A", "SYSC4504-A1O", "SYSC4937-A");//"SYSC4405-A", "SYSC4405-A1O", "SYSC4504A", "SYSC4504-A1O", "SYSC4604-A", "SYSC4604-A1E", "SYSC4937-A");
	$selected = array();
	foreach($_GET as $key => $val) {
		//echo $key;
		//if(preg_match("/[A-Z]{4}[0-9]{4}/", "$key", $matches)) {
			//$courselist = $courselist . "'" . $key . "',";
  			array_push($selected, $key);
		//}
	}
	//foreach($selected as $val) {
	//	echo $val . "</br>";
	//}


	$t1 = new TimeTable($selected);
	$t1->generateTimeTable();
	//foreach($t1->courseList as $course) {
	//	echo $course;
	//}
	echo $t1->displayHtml();
	echo "</br>";
	$selectedString = "(";
	foreach($selected as $a) {
		$selectedString = $selectedString . $a . ",";
	}
	$selectedString = rtrim($selectedString, ",") . ")";
	echo "<input type='hidden' name='courses' value='$selectedString'/>";
	

?>