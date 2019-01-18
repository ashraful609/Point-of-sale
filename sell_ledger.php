<?php 
require_once('includes/header.php');

$db = new Database();
$db->connect();
$db->set_sql("SELECT max_sold_products.discount,max_sold_products.product_name,max_sold_products.quantity,max_sold_products.buying_price,max_sold_products.product_category,max_sold_products.selling_price from sell_log right join (SELECT product_name, SUM(quantity) AS quantity,sell_log_id,buying_price,selling_price,product_category,SUM(discount) as discount FROM sell_log_detail GROUP BY product_name, product_category ORDER BY quantity DESC) max_sold_products on sell_log.id=max_sold_products.sell_log_id");
if($db->get_all_rows()) {
	$sell_log = $db->get_result();
}
?>

<?php
$cat="";
$db->set_sql("SELECT * from category");
if($db->get_all_rows()){
	$cat=$db->get_result();
}

$category=array();

foreach ($cat as $i) {
	$category[$i['category_id']]=$i['category_name'];
}


$total_selling_price=0;
$total_buying_price=0;
?>
<iframe id="txtArea1" style="display:none"></iframe>

<div class="container">
    <div class="row">
        <div class='col-12'>
            <form>
			  <div class="row">
			    <div class="col-row mr-2">
			      <!-- <label>From:</label> -->
			      <input type="text" class="form-control" id="from" placeholder="From">
			    </div>
			    <div class="col-row mr-2">
			    	<!-- <label>To:</label> -->
			      <input type="text" class="form-control" id="to" placeholder="To">
			    </div>
			    <div class="col-row">
			    	<input class="btn btn-primary mr-2" type="submit" id="update_log" name="update" value="Update">
			    </div>
			    <div class="col-row">
			    	<button class="btn btn-outline-info" id="btnExport" name="export" onclick="fnExcelReport('sell_ledger_table');">Export</button>
			    </div>
			  </div>
			</form>
        </div>


        <div>

        <script type="text/javascript">
                $("#from").datetimepicker();
                $("#to").datetimepicker();
        </script>
    	</div>
    </div>
</div>




<div class="container mt-4">
	<div class="row">
		<div class="col-12">
			<h2>Sell Ledger</h2>
			<form>
			</form>
			<div class="table-responsive" id="sell_ledger">
				<table class="table table-striped table-bordered table-hover" id="sell_ledger_table">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Product Name</th>
							<th>Quantity Sold</th>
							<th>Category</th>
							<th>Buying Price</th>
							<th>Selling Price</th>
							<th>Profit</th>
						</tr>
					</thead>
					<tbody>
					<?php $itr = 1; foreach($sell_log as $sl): ?>
						<tr>
							<td><?php echo $itr++; ?></td>
							<td><?php echo $sl['product_name']; ?></td>
							<td><?php echo $sl['quantity']; ?></td>	
							<td><?php echo $category[$sl['product_category']]; ?></td>
							<td><?php echo $sl['buying_price']*$sl['quantity']; ?></td>
							<td><?php echo ($sl['selling_price']*$sl['quantity']-$sl['discount']); ?></td>
							<td><?php echo ($sl['selling_price']*$sl['quantity']-$sl['discount'])-$sl['buying_price']*$sl['quantity']; ?></td>
						</tr>
					<?php
					$total_buying_price+=$sl['buying_price']*$sl['quantity']; 
					$total_selling_price+=($sl['selling_price']*$sl['quantity']-$sl['discount']);
					?>	
					<?php endforeach; ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><?php echo "<b>Total</b>:".$total_buying_price; ?></td>
						<td><?php echo "<b>Total</b>:".$total_selling_price; ?></td>
						<td><?php echo "<b>Total</b>:".($total_selling_price-$total_buying_price); ?></td>

						
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>


<?php require_once('includes/footer.php'); ?>