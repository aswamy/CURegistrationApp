<?php
/* 
This module handles post requests for logins. 
it will initialize the server session object
which is accessible on consecutive pages via calling session_start() 
to resume a session
*/
	$degree      = $_POST['degree'];	
	$year_status = $_POST['yearStatus'];
	$on_track    = $_POST['onTrack'];

	//Initialize a new server session
	session_start();
	$_SESSION['degree']=$degree;
	$_SESSION['year_status']=$year_status;
	$_SESSION['on_track']=$on_track;
?>