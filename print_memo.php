<?php
require_once('core/database.php');

function print_amount($v) {printf('%.2f', $v);}

$order_id = -1;
$username = '';
$order = array();
$order_detail = array();
$company_info = array();
$product = array();
$returned_order = array();

if(isset($_GET['id']) && isset($_GET['paid'])) {
  $order_id = $_GET['id'];
  $paid = (isset($_GET['paid'])) ? (float)$_GET['paid'] : 0;
  $db = new Database();
  $db->connect();

  // GET COMPANY INFO
  $sql = "SELECT * FROM company_info WHERE id=1 LIMIT 1";
  $db->set_sql($sql);
  $db->get_all_rows($sql);
  if($db->get_num_rows() === 1) {
  	$r = $db->get_result();
  	$company_info = $r[0];
  }
  // echo '<pre>'; print_r($company_info); echo '</pre>';

  // GET ORDER
  $sql = "SELECT * FROM pos.order WHERE order_id='{$order_id}' LIMIT 1";
  $db->set_sql($sql);
  $db->get_all_rows($sql);
  if($db->get_num_rows() === 1) {
  	$r = $db->get_result();
  	$order = $r[0];
  }
  $order['sub_total'] = 0;
  // echo '<pre>'; print_r($order); echo '</pre>';

  // GET ORDER DETAILS
  $sql = "SELECT * FROM order_detail WHERE order_id='{$order_id}'";
  $db->set_sql($sql);
  $db->get_all_rows($sql);
  if($db->get_num_rows()) {
  	$order_detail = $db->get_result();
  }
  // echo '<pre>'; print_r($order_detail); echo '</pre>';

  foreach ($order_detail as $key=>$od) {
  	$id = $od['product_id'];
  	$sql = "SELECT title, sale_price FROM product WHERE product_id='{$id}' LIMIT 1";
  	$db->set_sql($sql);
  	$db->get_all_rows();
  	if($db->get_num_rows() === 1) {
  		$r = $db->get_result();
  		$order_detail[$key]['product'] = $r[0];
  		$order['sub_total'] += $od['sub_total'];
  	}
  }
//   echo '<pre>'; print_r($order_detail); echo '</pre>';
//   echo '<pre>'; print_r($order); echo '</pre>';

  // GET RETURNED PRODUCT
  $sql = "SELECT * FROM returned_order as ro LEFT JOIN returned_product as rp ON ro.returned_order_id=rp.returned_order_id WHERE ro.order_id={$order_id}";
  $db->set_sql($sql);
  $db->get_all_rows();
  if($db->get_num_rows() === 1) {
      $r = $db->get_result();
      $returned_order = $r[0];
  }
  
//   echo '<pre>'; print_r($returned_order); echo '</pre>';
//   echo '<hr>';
	if(!empty($returned_order)) {
		foreach ($order_detail as $key=>$od) {
			if($od['product_id'] == $returned_order['product_id']) {
				$order_detail[$key]['returned_product'] = $returned_order;
			}
		}
	}
//   echo '<pre>'; print_r($order_detail); echo '</pre>';

  // GET USERNAME
  $user_id = $order['user_used_id'];
  $sql = "SELECT first_name FROM user_detail WHERE user_detail_id=(SELECT user_detail_id FROM user WHERE user_id='{$user_id}' LIMIT 1) LIMIT 1";
  $db->set_sql($sql);
  $db->get_all_rows($sql);
  if($db->get_num_rows() === 1) {
  	$r = $db->get_result();
  	$username = $r[0]['first_name'];
  }
  // echo '<pre>'; print_r($username); echo '</pre>';


}else{
	header('Location: index.php');
	exit(0); die();
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Invoice</title>
<link rel="stylesheet" href="css/vendor/paper/paper.css">
</head>
<style>
*{
	font-family: monaco;
	font-size: 2.5mm !important;
}
#logo img{
	height: 13mm;
	width: 17mm;
}
.t-center {
	text-align: center;
}

.t-right {
	text-align: right;
}

table {
	border: 0;
	outline: 0;
}

</style>

<body class="receipt padding-10mm" onLoad="window.print()">
<div class="sheet memo">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <table>
  	<tr>
  		<td id="logo" class="t-center" colspan="4"><img src="images/memo-logo.png" alt=""></td>
  	</tr>
  	<tr>
  		<td class="t-center" colspan="4"><?php echo $company_info['address']; ?></td>
  	</tr>
  	<tr>
  		<td class="t-center" colspan="4"><?php echo $company_info['city'] ." - ". $company_info['zipcode'];?></td>
  	</tr>
  	<tr>
  		<td class="t-center" colspan="4"><?php echo $company_info['phone'];?></td>
  	</tr>
  	<tr>
  		<td colspan="4">Date#<?php echo date('d M,Y g:i:s a',strtotime($order['created'])); ?></td>
  	</tr>
  	<tr>
  		<td colspan="2">Invoice#<?php echo $order['order_id'];?></td>
  		<td class="t-right" colspan="2">ServiceBy:<?php echo $username;?></td>
  	</tr>
  	<tr><td colspan="4"><br></td></tr>

  	<?php foreach ($order_detail as $od): ?>
    <?php if(isset($od['returned_product'])) {continue;} ?>
  		<tr>
  			<td style="width: 50%"><?php echo $od['product']['title']; ?></td>
  			<td><?php print_amount($od['product']['sale_price']);?></td>
  			<td class="t-center">x<?php echo $od['quantity'];?></td>
  			<td class="t-right"><?php if($od['discount'] > 0) {print_amount($od['quantity'] * $od['product']['sale_price']);}else{print_amount($od['sub_total']);} ?></td>
  		</tr>
  		<?php if($od['discount'] > 0): ?>
  			<tr>
  				<td class="t-right" colspan="2">dis: - <?php print_amount($od['discount']);?></td>
  				<td class="t-right" colspan="2">sub:<?php print_amount($od['sub_total']);?></td>
  			</tr>
  		<?php endif; ?>
	<?php endforeach; ?>
    <?php foreach ($order_detail as $od): ?>
    <?php if(isset($od['returned_product'])): ?>
        <tr>
            <td colspan="4">--Returned Product--</td>
        </tr>
  		<tr>
  			<td style="width: 50%"><?php echo $od['product']['title']; ?></td>
  			<td><?php print_amount($od['product']['sale_price']);?></td>
  			<td class="t-center">x<?php echo $od['quantity'];?></td>
  			<td class="t-right"><?php if($od['discount'] > 0) {print_amount($od['quantity'] * $od['product']['sale_price']);}else{print_amount($od['sub_total']);} ?></td>
  		</tr>
  		<?php if($od['discount'] > 0): ?>
  			<tr>
  				<td class="t-right" colspan="2">dis: - <?php print_amount($od['discount']);?></td>
  				<td class="t-right" colspan="2">sub:<?php print_amount($od['sub_total']);?></td>
  			</tr>
  		<?php endif; ?>
    <?php endif; ?>
	<?php endforeach; ?>
	<tr><td colspan="4"><br></td></tr>
	<tr>
		<td class="t-right" colspan="2">Subtotal:</td>
		<td class="t-right" colspan="2">BDT <?php print_amount($order['sub_total']);?></td>
	</tr>
	<tr>
		<td class="t-right" colspan="2">Discount:</td>
		<td class="t-right" colspan="2">BDT -<?php print_amount($order['discount']);?></td>
	</tr>
	<tr>
		<td class="t-right" colspan="2">Net Amount:</td>
		<td class="t-right"  colspan="2">BDT <?php print_amount($order['total_amount']);?></td>
	</tr>
	<tr>
		<td class="t-right" colspan="2">Previous Amount:</td>
		<td class="t-right"  colspan="2">BDT <?php print_amount($paid);?></td>
	</tr>
	<tr>
		<td class="t-right" colspan="2">Change Amount:</td>
		<td class="t-right" colspan="2">BDT <?php if($paid>0) {print_amount($paid - $order['total_amount']);}else{echo print_amount(0);}?></td>
	</tr>
	<tr>
  		<td class="t-center" colspan="4"><hr></td>
  	</tr>
  	<tr>
  		<td class="t-center" colspan="4">Thank you for shopping at Nobabee Style</td>
  	</tr>
  	<tr>
  		<td class="t-center" colspan="4">*** No Cash Refund</td>
  	</tr>
  </table>
</div>
</body>
</html>