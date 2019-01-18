<?php
require_once('includes/functions/user_access.php');
require_once('core/database.php');

if(isset($_POST['product_id'])) {
	$product_id = $_POST['product_id'];
	$db = new Database();
	$db->connect();
	$sql = "UPDATE product SET active=0 WHERE product_id='{$product_id}'";
	$db->set_sql($sql);
	if($db->run_query()) {
		echo "product_removed";
	}else{
		echo "product_not_removed";
	}
}


 ?>