<?php

	require_once('yearstatus.php');

	session_start();

	$courses_completed = array_keys($_POST);
	echo 'Year Status: '.getYearStatus($courses_completed, $_SESSION['degree']);
?>