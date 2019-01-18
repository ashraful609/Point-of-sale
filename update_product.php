<?php
require_once('includes/functions/user_access.php');
require_once('includes/functions/utils.php');
require_once('core/database.php');
if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['buy_price']) && isset($_POST['sale_price']) && isset($_POST['quantity']) && isset($_POST['description']) && isset($_POST['category_id']) && isset($_POST['color']) && isset($_POST['size'])) {
	$id = $_POST['id'];
	$title = $_POST['title'];
	$buy_price = $_POST['buy_price'];
	$sale_price = $_POST['sale_price'];
	$quantity = $_POST['quantity'];
	$description = $_POST['description'];
	$category_id = $_POST['category_id'];
	$color = $_POST['color'];
	$size = $_POST['size'];
	$update_status = 0;

	if($color == '' || $color == 'null') {$color = 'None';}
	if($size == '' || $size == 'null') {$size = 'None';}

	$db = new Database();
	$db->connect();
	
	// UPDATE PRODUCT
	$sql = "UPDATE product SET title='{$title}', description='{$description}', unit_price='{$buy_price}', sale_price='{$sale_price}', edited=now(), category_id='{$category_id}' WHERE product_id='{$id}'";

	$db->set_sql($sql);
	if($db->run_query()){
		$update_status++;
	}


	// UPDATE VARIANT
	$sql = "UPDATE product_variant SET color='{$color}', size='{$size}', quantity='{$quantity}' WHERE product_id='{$id}'";

	$db->set_sql($sql);
	if($db->run_query()){
		$update_status++;
	}
	echo $update_status;

}else{
	echo "no_data_found";
}
?>