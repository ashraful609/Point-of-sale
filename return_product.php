<?php
require_once('includes/functions/user_access.php');
require_once('core/database.php');

// $_POST['order_id'] = 1;
// $_POST['product_id'] = 5;
// $_POST['return_qty'] = 1;
// $_POST['remark'] = 'defected product.';

if(isset($_POST['order_id']) && isset($_POST['product_id']) && isset($_POST['return_qty']) && isset($_POST['remark'])) {
    $product = array();
    $err = 0;
    $order_qty = 0;
    $sale_price = 0;
    $buy_price = 0;
    $order_id = (int)$_POST['order_id'];
    $product_id = (int)$_POST['product_id'];
    $return_qty = (int)$_POST['return_qty'];
    $remark = $_POST['remark'];

    $db = new Database();
    $db->connect();

    $sql = "SELECT quantity, sub_total FROM order_detail WHERE order_id='{$order_id}' AND product_id='{$product_id}' LIMIT 1";
    $db->set_sql($sql);
    $db->get_all_rows();
    if($db->get_num_rows() !== 0) {
        $result = $db->get_result();
        $order_qty = $result[0]['quantity'];
        $sale_price = $result[0]['sub_total'];
        $sale_price = (-1)*(float)($sale_price / $order_qty); 
    }

    $sql = "SELECT unit_price FROM product WHERE product_id='{$product_id}' LIMIT 1";
    $db->set_sql($sql);
    $db->get_all_rows();
    if($db->get_num_rows() !== 0) {
        $result = $db->get_result();
        $buy_price = (-1)*$result[0]['unit_price'];
    }
    // Validation
    if($return_qty < 1 || $order_qty < $return_qty) {
        $err = 99;
    }else{
        // 1. insert into returned_order
        $sql = "INSERT INTO returned_order VALUES (null, '{$order_id}', now(), 0, 0, '{$remark}')";
        $db->set_sql($sql);
        if(!$db->run_query()) {
            $err=1;
            // echo $err .'<br>'. $sql .'<br>' ;
        }else{
            // 2. insert into returned_product
            $return_order_id = $db->get_last_insert_id();
            $sql = "INSERT INTO returned_product VALUES('{$product_id}', '{$return_qty}', 0, 0, '{$return_order_id}')";
            $db->set_sql($sql);
            if(!$db->run_query($sql)) {
                $err=2;
                // echo $err .'<br>'. $sql .'<br>' ;

            }else{
                // 3. update order log
                $deduct_sale_price = $sale_price * $return_qty;
                $sql = "UPDATE order_detail SET quantity=quantity-'{$return_qty}', sub_total=sub_total+'{$deduct_sale_price}' WHERE order_id='{$order_id}' AND product_id='{$product_id}'";
                $db->set_sql($sql);
                if(!$db->run_query()) {
                    $err=3;
                }else{
                    $sql = "UPDATE pos.order SET total_amount=total_amount+'{$deduct_sale_price}' WHERE order_id='{$order_id}'";
                    $db->set_sql($sql);
                    if(!$db->run_query()) {
                        $err=4;
                    }else{
                        // 4. update sell log
                        $user_id = $_SESSION['user_id'];
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
                            $sql = "INSERT INTO sell_log VALUES (null, '{$user_first_name}', '{$user_last_name}', '{$user_phone}', now(), '1', '0')";
                            $db->set_sql($sql);
                            $db->run_query();

                            // Insert into sell_log_detail
                            $p_name = 'returned product';
                            $sell_log_id = $db->get_last_insert_id();
                            $sql = "INSERT INTO sell_log_detail VALUES(null, '{$sell_log_id}', '{$p_name}', '{$sale_price}', '{$buy_price}', '{$return_qty}',  '3', '0')";
                            $db->set_sql($sql);
                            $db->run_query();
                        }
                    }
                }

                // 5. update product variant
                $sql = "UPDATE product_variant SET quantity=quantity+'{$return_qty}' WHERE product_id='{$product_id}'";
                $db->set_sql($sql);
                if(!$db->run_query()) {
                    $err=5;
                    // echo $err .'<br>'. $sql .'<br>';
                }else{
                    // 6. set product visibility on
                    $sql = "UPDATE product SET active=1 WHERE product_id='{$product_id}'";
                    $db->set_sql($sql);
                    $db->run_query();
                }
            }
        }
    }

    if($err==99) {
        echo json_encode(array('error'=>'Invalid return quantity'));
    }else if($err) {
        // 7. Rollback database changes
        $sql = "DELETE FROM returned_product WHERE product_id='{$product_id}' AND returned_order_id='{$return_order_id}'";
        $db->set_sql($sql);
        $db->run_query();
        $sql = "DELETE FROM returned_order WHERE returned_order_id='{$return_order_id}'";
        $db->set_sql($sql);
        $db->run_query();
        echo json_encode(array('error'=>'Failed to return the product.'));
    }else{
        // Product return successfull.
        echo json_encode(array('success'=>'product return success.'));
    }

    // switch ($err) {
    //     case 1:
    //         echo json_encode(array('error'=>'return order insert error.'));
    //         break;
    //     case 2:
    //         echo json_encode(array('error'=>'return product insert error.'));
    //         break;
    //     case 3:
    //         echo json_encode(array('error'=>'order detail update error.'));
    //         break;
    //     case 4:
    //         echo json_encode(array('error'=>'sell log update error.'));
    //         break;
    //     case 5:
    //         echo json_encode(array('error'=>'product variant update error.'));
    //         break;
    //     case 99: 
    //         echo json_encode(array('error' => 'invalid quantity.'));
    //         break;
    //     default:
    //         echo json_encode(array('success'=>'product return success.'));
    //         break;
    // }


}else{
    echo json_encode(array('error'=>'Form submit failed.'));
}
?>