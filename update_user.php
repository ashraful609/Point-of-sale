<?php
require_once('includes/functions/user_access.php');
require_once('includes/functions/utils.php');
require_once('core/database.php');
if(isset($_POST['id']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
	$id = $_POST['id'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];

	$db = new Database();
	$db->connect();

	// GET USER DETAIL ID
	$sql = "SELECT user_detail_id FROM user WHERE user_id='{$id}' LIMIT 1";
	$db->set_sql($sql);
	$db->get_all_rows();
	if($db->get_num_rows() !== 0) {
		$result = $db->get_result();
		$id = $result[0]['user_detail_id'];

		// UPDATE USER
		$sql = "UPDATE user_detail SET first_name='{$firstname}', last_name='{$lastname}', email='{$email}', phone='{$phone}', address='{$address}' WHERE user_detail_id='{$id}'";

		$db->set_sql($sql);
		if($db->run_query()){
			echo "user_updated";
		}else{
			echo "user_update_failed";
		}
	}else{
		echo "invalid_user";
	}
}else{
	echo "invalid_form_submit";
}
?>