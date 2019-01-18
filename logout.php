<?php
session_start();
if(isset($_COOKIE['user_id'])) {setcookie('user_id', '', time()-3600);}
if(isset($_COOKIE['username'])) {setcookie('username', '', time()-3600);}
if(isset($_SESSION['user_id'])) {
	unset($_SESSION['user_id']);
	unset($_SESSION['username']);
	session_destroy();
}
header("Location: login.php");
?>