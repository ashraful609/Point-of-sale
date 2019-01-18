<?php
require_once("core/database.php");
$output="";
$total_selling_price=0;
$total_buying_price=0;
$reports=array();

$db = new Database();
$db->connect();
if(isset($_POST['from']) && isset($_POST['to'])){
	$from=$_POST['from'].":00";
	$to=$_POST['to'].":00";
	$db->set_sql("SELECT * FROM (SELECT date(date) AS date,SUM(sell_log_details.selling_price-discount) AS selling_price,SUM(sell_log_details.buying_price) AS buying_price,(SUM(sell_log_details.selling_price-discount)-SUM(sell_log_details.buying_price)) AS profit FROM sell_log RIGHT JOIN (SELECT sell_log_id,SUM(selling_price*quantity-discount) AS selling_price,SUM(buying_price) AS buying_price FROM `sell_log_detail` GROUP BY sell_log_id) sell_log_details on sell_log.id=sell_log_details.sell_log_id GROUP BY date(date)) sell_log_data where date BETWEEN '$from' AND '$to'");
	if($db->get_all_rows()) {
		$reports = $db->get_result();
	}
					$output="<table class='table table-striped table-bordered table-hover' id='sell_ledger_table'>
					<thead>
						<tr>
							<th>SL#</th>
							<td>Date</td>
							<th>Selling Price</th>
							<th>Buying Price</th>
							<th>Profit</th>
						</tr>
					</thead>
					<tbody>";
					$itr = 1; foreach($reports as $r):
						$output.= '<tr>
							<td>'.$itr++.'</td>
							<td>'.$r['date'].'</td>
							<td>'.$r['selling_price'].'</td>
							<td>'.$r['buying_price'].'</td>
							<td>'.$r['profit'].'</td>
							
						</tr>';
						$total_buying_price+=$r['buying_price']; 
						$total_selling_price+=$r['selling_price'];

					endforeach;
					$output.="<tr>
						<td></td>
						<td></td>
						<td><b>Total: </b>".$total_buying_price."</td>
						<td><b>Total: </b>".$total_selling_price."</td>
						<td><b>Total: </b>".($total_selling_price-$total_buying_price)."</td>	
					</tr>";


					$output.="</tbody></table>";
}


echo $output;

?>