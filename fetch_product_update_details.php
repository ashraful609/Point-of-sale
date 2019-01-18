<?php
require_once('core/database.php');

if(isset($_POST['product_id'])) {
	$product_id = $_POST['product_id'];
	$sql = "SELECT * FROM product as p LEFT JOIN product_variant as pv ON pv.product_id=p.product_id WHERE p.product_id='{$product_id}' LIMIT 1";
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