<?php
session_start();
if(isset($_SESSION['timeout']) ) {
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive) echo "0";

else echo "1";
}

$_SESSION['timeout'] = time();

?>