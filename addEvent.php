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

if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

require 'database.php';

header("Content-Type: application/json");

//Check see if fields are empty
if(empty($_POST['new_event']) || empty($_POST['new_time']) || empty($_POST['new_date'])){
	echo json_encode(array(
        "success" => false,
      "message" => "Please fill out all required fields!"
     ));
  exit;
} else {
    $stmt = $mysqli->prepare("insert into events (title, year, month, day, time, owner_id) values (?, ?, ?, ?, ?, ?);");

	if(!$stmt) {
  	printf("Query Prep Failed: %s\n", $mysqli-> error);
    	exit; }

		$title = $_POST['new_event'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $day = $_POST['day'];
        $time = $_POST['new_time'];
        $owner_id = $_SESSION['user_id'];

	//add recurring to statement
	$stmt->bind_param('siiisi', $title, $year, $month, $day, $time, $owner_id);
	$stmt->execute();
	$stmt->close();

  	echo json_encode(array(
  		"success" => true
        ));
        exit;
}
?>