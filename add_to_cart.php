<?php 
require_once('includes/functions/user_access.php');
require_once('core/database.php');

if(isset($_POST['product'])) {
	$db = new Database();
	$db->connect();
	$err = 0;
	$total_amount_paid = 0;
	$total_discount_offered = 0;
	$total_discount = 0;
	$total_discount_percent_flag = false;

	if(isset($_POST['total_discount'])) {
		$total_discount = $_POST['total_discount'];
		if(is_string($total_discount) && substr($total_discount, -1) == '%') {
			$total_discount = explode('%', $total_discount, 2)[0];
			$total_discount /= 100;
			$total_discount_percent_flag = true;
		}else{
			$total_discount = (float)$_POST['total_discount'];
		}
	}

	$paid_amount = (isset($_POST['paid_amount'])) ? (float)$_POST['paid_amount'] : 0;
	if($total_discount_percent_flag == false && $total_discount < 0) {echo json_encode(array('msg'=>'Discount must be positive'));exit(0);die();}
	$product = $_POST['product'];
	foreach ($product as $p) {
		$discount_percent_flag = false;
		$id = (int)$p['id'];
		$qty = (int)$p['qty'];
		$discount = $p['discount'];
		if(is_string($discount) && substr($discount, -1) == '%') {
			$discount = explode('%', $discount, 2)[0];
			$discount /= 100;
			$discount_percent_flag = true;
		}else{
			$discount = (float)$discount;
		}
		$db_product = array();

		// Validate form data
		if($qty <= 0 || ($discount_percent_flag == false && $discount < 0) || $paid_amount < 0) {
			echo json_encode(array('msg'=>'Check cart entry'));exit(0);die();
		}

		// Check if product exists, retrive product if exists
		$sql = "SELECT * FROM product as p LEFT JOIN product_variant as pv ON p.product_id=pv.product_id WHERE p.product_id='{$id}' LIMIT 1";
		$db->set_sql($sql);
		$db->get_all_rows();
		if($db->get_num_rows() == 1) {
			$db_product = $db->get_result();
			$db_product = $db_product[0];
		}else{
			echo json_encode(array('msg'=>'Invalid product'));exit(0);die();
		}

		// check available product quantity
		if($db_product['quantity'] < $qty ) {echo json_encode(array('msg'=>'Insufficient product'));exit(0);die();}

		// Calculate Product price
		$sub_total = $qty * $db_product['sale_price'];
		if($discount_percent_flag) {
			$discount = $sub_total * $discount;
		}

		if($sub_total < $discount) {
			echo json_encode(array('msg'=>'Discount must be less than product price'));exit(0);die();
		}

		// Calculation to update database
		$total = $sub_total - $discount;
		$qty = $db_product['quantity'] - $qty;
		$sql = "UPDATE product_variant SET quantity='{$qty}' WHERE product_id='{$id}' LIMIT 1";
		$db->set_sql($sql);
		if(!$db->run_query()) {echo json_encode(array('msg'=>'Database not responding, contact developer.'));exit(0);die();}
		// $total_discount_offered += $discount;
		$total_amount_paid += $total;
	} // END FOREACH
	if($total_discount_percent_flag) {
		$total_discount = $total_amount_paid * $total_discount;
	}

	if($total_amount_paid < $total_discount) {
		echo json_encode(array('msg'=>'Discount must be less than product price'));
		$err = 1;
	}else if($paid_amount > 0 && ($total_amount_paid-$total_discount) > $paid_amount) {
		echo json_encode(array('msg'=>'Paid is optional, but cannot be smaller.'));
		$err = 1;
	}
	if($err == 1) {

		// roll back order
		foreach ($product as $p) {
			$id = (int)$p['id'];
			$qty = (int)$p['qty'];
			$sql = "SELECT quantity FROM product_variant WHERE p.product_id='{$id}' LIMIT 1";
			$db->set_sql($sql);
			$db->get_all_rows();
			if($db->get_num_rows() === 1) {
				$db_product = $db->get_result();
				$db_product = $db_product[0];
				$update_qty = $db_product['quantity'];
			}
			$sql = "UPDATE product_variant SET quantity='{$update_qty}' WHERE product_id='{$id}'";
			$db->set_sql($sql);
			$db->run_query();
			exit(0);die();
		}
	}



		// UPDATE ORDER LOG
		$total_amount_paid -= $total_discount;
		$total_discount_offered += $total_discount;
		$user_id = $_SESSION['user_id'];
		$sql = "INSERT INTO `order` (`order_id`, `discount`, `coustomer_id`, `status`, `created`, `total_amount`, `payment_type`, `user_used_id`) VALUES (NULL, '{$total_discount}', '1', '1', NOW(), '{$total_amount_paid}', 'cash', '{$user_id}')";
		$db->set_sql($sql);
		$db->run_query();
		$order_id = $db->get_last_insert_id();

		// UPDATE SELL LOG
		$user_first_name = '';
		$user_last_name = '';
		$user_phone = '';
		$sql = "SELECT ud.first_name, ud.last_name, ud.phone FROM user_detail as ud WHERE ud.user_detail_id=(SELECT u.user_detail_id FROM user as u WHERE u.user_id='{$user_id}' LIMIT 1) LIMIT 1";
		$db->set_sql($sql);
		$db->get_all_rows();
		if($db->get_num_rows() === 1) {
			$result = $db->get_result();
			$result = $result[0];
			$user_first_name = $result['first_name'];
			$user_last_name = $result['last_name'];
			$user_phone = $result['phone'];
			$sql = "INSERT INTO sell_log VALUES (null, '{$user_first_name}', '{$user_last_name}', '{$user_phone}', now(), '1', '{$total_discount}')";
			$db->set_sql($sql);
			$db->run_query();
		}
		$sell_log_id = $db->get_last_insert_id();

		// UPDATE ORDER_LOG_DETAIL, SELL_LOG_DETAIL
		foreach ($product as $p) {
			$discount_percent_flag = false;
			$id = (int)$p['id'];
			$qty = (float)$p['qty'];
			$discount = $p['discount'];
			if(is_string($discount) && substr($discount, -1) == '%') {
				$discount = explode('%', $discount, 2)[0];
				$discount /= 100;
				$discount_percent_flag = true;
			}else{
				$discount = (float)$discount;
			}
			$sql = "SELECT * from product WHERE product_id='{$id}' LIMIT 1";
			$db->set_sql($sql);
			$db->get_all_rows();
			if($db->get_num_rows() === 1) {
				$result = $db->get_result();
				$result = $result[0];
				$p_name = $result['title'];
				$p_sale_price = $result['sale_price'];
				$p_buy_price = $result['unit_price'];
				$p_category_id = $result['category_id'];
				$stotal = $qty * $p_sale_price;
				if($discount_percent_flag) {
					$discount = $stotal * $discount;
				}
				$stotal = $stotal - $discount;

				$sql = "INSERT INTO order_detail VALUES('{$order_id}', '{$id}', '$qty', '{$stotal}', 'None', '{$discount}')";
				$db->set_sql($sql);
				$db->run_query();
				$sql = "INSERT INTO sell_log_detail VALUES(null, '{$sell_log_id}', '{$p_name}', '{$p_sale_price}', '{$p_buy_price}', '{$qty}',  '{$p_category_id}', '{$discount}')";
				$db->set_sql($sql);
				$db->run_query();
			} 
		}
		// RETURN SUCCESS
		echo json_encode(array('order_id'=>$order_id, 'paid'=>$paid_amount));
}else{
	echo json_encode(array('msg'=>'Cart empty'));
}
?>