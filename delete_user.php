<?php
require_once('includes/functions/user_access.php');
require_once('core/database.php');

if(isset($_POST['user_id'])) {
	$user_id = $_POST['user_id'];
	$db = new Database();
	$db->connect();
	$sql = "UPDATE user SET user.user_status='0' WHERE user.user_id='{$user_id}'";
	$db->set_sql($sql);
	if($db->run_query()) {
		echo "user_removed";
	}else{
		echo "user_remove_failed";
	}
}else{
	echo "invalid_user";
}
?>