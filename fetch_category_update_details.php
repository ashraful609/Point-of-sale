<?php
require_once('includes/functions/user_access.php');
require_once('core/database.php');

if(isset($_POST['category_id'])) {
	$category_id = $_POST['category_id'];
	$sql = "SELECT * FROM category WHERE category_id='{$category_id}' LIMIT 1";
	$db = new Database();
	$db->connect();
	$db->set_sql($sql);
	$db->get_all_rows();
	$result = $db->get_result();
	echo json_encode($result);
}

?>
