<?php 
require_once('core/database.php');
if(isset($_POST['product_id'])) {
	$product_id = $_POST['product_id'];
	$products = array();
	$db = new Database();
	$db->connect();
	$sql = "SELECT * FROM product as p LEFT JOIN product_variant as pv ON p.product_id=pv.product_id LEFT JOIN category as c ON p.category_id=c.category_id WHERE p.active='1' AND p.product_id='{$product_id}' LIMIT 1";
	$db->set_sql($sql);
	$db->get_all_rows();
	if($db->get_num_rows() !== 0) {
		$products = $db->get_result();
		print_r(json_encode($products));
	}else{
		echo "empty_product";
	}
	
}

?>