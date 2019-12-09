<?php
	//Initiation
	ini_set("session.cookie_httponly", 1);

	session_start();

	$previous_ua = @$_SESSION['useragent'];
	$current_ua = $_SERVER['HTTP_USER_AGENT'];

	if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
		die("Session hijack detected");
	}else{
		$_SESSION['useragent'] = $current_ua;
	}

	require 'database.php';

	header("Content-Type: application/json");

	//Check see if fields are empty
	$events = array();

	$stmt = $mysqli->prepare("select event_id, title, day, time from events where owner_id=? and month=? and year=?");
	if(!$stmt) {
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
	}

	$owner_id = $_SESSION['user_id'];
	$month = $_POST['month'] + 1;
	$year = $_POST['year'];

	$stmt->bind_param('sss', $owner_id, $month, $year);
	$stmt->execute();
	$stmt->bind_result($event_id, $title, $day, $time);

	//push variables onto array
	while($stmt->fetch()) {
		array_push($events, array("title" => htmlentities($title), "day" => htmlentities($day), "event_id" => htmlentities($event_id),
	"time" => htmlentities($time)));
	}

	echo json_encode($events);
?>