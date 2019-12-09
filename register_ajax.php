<?php
    //Initiation
    ini_set("session.cookie_httponly", 1);
    session_start();

    //Check for hijack
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
            die("Session hijack detected");
    }else{
            $_SESSION['useragent'] = $current_ua;
    }

    //More initiations
    require 'database.php';
    header("Content-Type: application/json");

    //Instances from mySql
    $username = $_POST['new_user'];
    $password = $_POST['new_password'];
    $nameExists=false;

    //Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    //Select data from the database
    $stmt = $mysqli->prepare("select username from users");

    //Print error message if failed
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    //Since both fields have "required" attributes, we do not need to check if filled
    //Check if the user already existed
    $stmt->execute();
    $stmt->bind_result($existedNames);
    while($stmt->fetch()){
        if($username == $existedNames){
            $nameExists=true;
            echo json_encode(array(
                "success" => false,
                "message" => "Username already exists."
            ));
            exit;
        }
    }

    //Bind ajax if both username and password are fine
    if(!$nameExists){
        //Close stmt
        $stmt->close();

        //Store the entered value into databses
        $stmt = $mysqli->prepare("insert into users (username, password_hash) values (?, ?)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('ss', $username, $password_hash);
        $stmt->execute();
        $stmt->close();

        //Token and session id
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

        echo json_encode(array(
                "success" => true,
                "token" => $_SESSION['token']
            ));
            exit;
        }
?>