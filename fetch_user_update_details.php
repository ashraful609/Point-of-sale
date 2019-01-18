<?php
require_once('core/database.php');
if(isset($_POST['user_id'])) {
	$user_id = $_POST['user_id'];
	$sql = "SELECT ud.first_name, ud.last_name, ud.email, ud.registered, ud.phone, ud.address, u.last_login, u.username, u.user_id, u.user_detail_id FROM user as u LEFT JOIN user_detail ud ON u.user_id=ud.user_detail_id WHERE u.user_id='{$user_id}'";
	$db = new Database();
	$db->connect();
	$db->set_sql($sql);
	$db->get_all_rows();
	if($db->get_num_rows() !== 0) {
		$result = $db->get_result();
	}
	echo json_encode($result);
}
?>