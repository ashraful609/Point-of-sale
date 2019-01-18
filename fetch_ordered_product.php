<?php 
require_once ('includes/functions/user_access.php');
require_once ('core/database.php');
if(isset($_POST['id'])) {
	$order_id = $_POST['id'];
	$order = array();
	$db = new Database();
	$db->connect();
	$sql = "SELECT o.order_id, o.created, o.total_amount, o.discount FROM `order` as o WHERE o.order_id='{$order_id}' LIMIT 1";
	$db->set_sql($sql);
	$db->get_all_rows();
	if($db->get_num_rows() === 1) {
		$r = $db->get_result();
		$order[0] = $r[0]; 
	}else{
		echo json_encode(array('msg'=>'Order does not exists.'));
		exit(0);die();
	}
	$sql = "SELECT p.product_id, p.title, p.unit_price, p.sale_price,(SELECT category_name FROM category as c WHERE p.category_id=c.category_id) as category_name,pv.size, pv.color, pv.quantity, od.order_id, od.quantity as ordered_quantity, od.sub_total, od.remark, od.discount FROM product as p LEFT JOIN product_variant as pv ON p.product_id=pv.product_id RIGHT JOIN order_detail as od ON p.product_id=od.product_id WHERE od.order_id='{$order_id}'";
	$db->set_sql($sql);
	$db->get_all_rows();
	if($db->get_num_rows() > 0) {
		$order[0]['ordered_product'] = $db->get_result();
	}else{
		echo json_encode(array('msg'=>'Empty or broken order.'));
		exit(0);die();
	}

	// RETURN ORDER 
	echo json_encode($order);
}
?>