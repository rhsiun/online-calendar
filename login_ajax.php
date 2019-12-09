<?php
// login_ajax.php
//Initiation
ini_set("session.cookie_httponly", 1); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Start session
//session_start();
    
//More initiations
require 'database.php';
header("Content-Type: application/json");

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
//$json_str = file_get_contents('php://input');
//This will store the data into an associative array
//$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $_POST['username'];
$password = $_POST['password'];

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
// Use a prepared statement
$stmt = $mysqli->prepare("SELECT password_hash, user_id FROM users WHERE username =?");

//Print error message if failed
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

// Bind the parameter
$stmt->bind_param('s', $username);
$stmt->execute();

// Bind the results
$stmt->bind_result($password_hash, $user_id);
$stmt->fetch();
session_start();
if(password_verify($password, $password_hash) ){
    //Check for hijack
    $previous_ua = @$_SESSION['useragent'];
	$current_ua = $_SERVER['HTTP_USER_AGENT'];

	if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
        	die("Session hijack detected");
	}else{
		    $_SESSION['useragent'] = $current_ua;
    }
    
    //Give session variables
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

	echo json_encode(array(
        "success" => true,
        "user_id" => $_SESSION['user_id'],
        "token" => $_SESSION['token']
    ));
    
    $stmt->close();
	exit;
}else{
	echo json_encode(array(
		"success" => false,
        "message" => "Incorrect Username or Password"
    ));

    $stmt->close();
	exit;
}
?>