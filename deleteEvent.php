<?php
//Initiation
ini_set("session.cookie_httponly", 1);
session_start();
$previous_ua = @$_SESSION['useragent'];
$current_ua = $_SERVER['HTTP_USER_AGENT'];
if (isset($_SESSION['useragent']) && $previous_ua !== $current_ua) {
    die("Session hijack detected");
} else {
    $_SESSION['useragent'] = $current_ua;
}
if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("Request forgery detected");
}
require 'database.php';

header("Content-Type: application/json");


    $stmt = $mysqli->prepare("delete from events where event_id=?;");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $event_id = $_POST['event_id'];

    //add recurring to statement
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true
    ));
    exit;
    
?>
