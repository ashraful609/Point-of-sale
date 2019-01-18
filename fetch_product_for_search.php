<?php 
require_once('core/database.php');
$products = array();
$db = new Database();
$db->connect();
// $sql = "SELECT p.product_id, p.category_id, p.title, p.unit_price, p.sale_price, pv.size, pv.color, pv.quantity, c.category_name  FROM product as p LEFT JOIN product_variant as pv ON p.product_id=pv.product_id LEFT JOIN category as c ON p.category_id=c.category_id WHERE p.active='1'";
$sql = "SELECT p.product_id, p.title, pv.size, pv.color FROM product as p LEFT JOIN product_variant as pv ON p.product_id=pv.product_id WHERE p.active='1'";
$db->set_sql($sql);
$db->get_all_rows();
if($db->get_num_rows() !== 0) {
	$products = $db->get_result();
	print_r(json_encode($products));
}else{
	echo "empty_product";
}
?>