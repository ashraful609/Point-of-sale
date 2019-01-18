<?php
require_once("core/database.php");
$output="";
$total_selling_price=0;
$total_buying_price=0;

$db = new Database();
$db->connect();

$cat="";
$db->set_sql("SELECT * from category");
if($db->get_all_rows()){
	$cat=$db->get_result();
}

$category=array();

foreach ($cat as $i) {
	$category[$i['category_id']]=$i['category_name'];
}


if(isset($_POST['from']) && isset($_POST['to'])){
	$from=$_POST['from'].":00";
	$to=$_POST['to'].":00";
	$db->set_sql("SELECT sell_log.date,max_sold_products.product_name,max_sold_products.quantity,max_sold_products.buying_price,max_sold_products.selling_price,max_sold_products.product_category from sell_log right join (SELECT product_name, SUM(quantity) AS quantity,sell_log_id,buying_price,selling_price,product_category FROM sell_log_detail GROUP BY product_name ORDER BY product_name DESC) max_sold_products on sell_log.id=max_sold_products.sell_log_id where sell_log.date BETWEEN '$from' AND '$to'");
	if($db->get_all_rows()) {
		$sell_log = $db->get_result();
	}
					$output="<table class='table table-striped table-bordered table-hover' id='sell_ledger_table'>
					<thead>
						<tr>
							<td>SL#</td>
							<td>Product Name</td>
							<td>Quantity Sold</td>
							<td>Category</td>
							<td>Buying Price</td>
							<td>Selling Price</td>
							<td>Profit</td>
						</tr>
					</thead>
					<tbody>";
					$itr = 1; foreach($sell_log as $sl):
						$output.= '<tr>
							<td>'.$itr++.'</td>
							<td>'.$sl['product_name'].'</td>
							<td>'.$sl['quantity'].'</td>	
							<td>'.$category[$sl['product_category']].'</td>
							<td>'.$sl['buying_price']*$sl['quantity'].'</td>
							<td>'.$sl['selling_price']*$sl['quantity'].'</td>
							<td>'.(($sl['selling_price']*$sl['quantity'])-($sl['buying_price']*$sl['quantity'])).'</td>
						</tr>';
						$total_buying_price+=$sl['buying_price']*$sl['quantity']; 
						$total_selling_price+=$sl['selling_price']*$sl['quantity'];

					endforeach;
					$output.="<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><b>Total</b>".$total_buying_price."</td>
						<td><b>Total</b>".$total_selling_price."</td>
						<td><b>Total</b>".($total_selling_price-$total_buying_price)."</td>	
					</tr>";


					$output.="</tbody></table>";
}


echo $output;

?>