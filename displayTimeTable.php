<?php
	require_once 'timeTableClass.php';
	session_start();
	$selected = array();
	foreach($_GET as $key => $val) {
  		array_push($selected, $key);
	}

	$t1 = new TimeTable($selected);
	$t1->generateTimeTable();

	echo $t1->displayHtml();
	echo "</br>";
	$selectedString = "(";
	foreach($selected as $a) {
		$selectedString = $selectedString . $a . ",";
	}
	$selectedString = rtrim($selectedString, ",") . ")";
	echo "<input type='hidden' name='courses' value='$selectedString'/>";
?>