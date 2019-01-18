<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
	$_SESSION['user_id'] = $_COOKIE['user_id'];
	$_SESSION['username'] = $_COOKIE['username'];
}
if(!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit(0);
	die();
}

?>