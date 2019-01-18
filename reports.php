<?php 
require_once('includes/header.php');

$db = new Database();
$db->connect();
$db->set_sql("SELECT date(date) AS date,SUM(sell_log_details.selling_price) AS selling_price,SUM(sell_log_details.buying_price) AS buying_price,(SUM(sell_log_details.selling_price-discount)-SUM(sell_log_details.buying_price)) AS profit FROM sell_log RIGHT JOIN (SELECT sell_log_id,SUM(selling_price*quantity-discount) AS selling_price,SUM(buying_price*quantity) AS buying_price FROM `sell_log_detail` GROUP BY sell_log_id) sell_log_details on sell_log.id=sell_log_details.sell_log_id GROUP BY date(date)");
if($db->get_all_rows()) {
	$reports = $db->get_result();
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
			    <div class="col-row mr-2">
			    	<input class="btn btn-primary" type="submit" id="update_report" name="update" value="Update Reports">
			    </div>
			    <div class="col-row">
			    	<button class="btn btn-outline-info" id="btnExport" name="export" onclick="fnExcelReport('report_view_table');">Export</button>
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
			<h2>Reports</h2>

			<div class="table-responsive" id="view_report">
				<table class="table table-striped table-bordered table-hover" id="report_view_table">
					<thead>
						<tr>
							<th>SL#</th>
							<th>Date</th>
							<th>Selling Price</th>
							<th>Buying Price</th>
							<th>Profit</th>
						</tr>
					</thead>
					<tbody>
					<?php $itr = 1; foreach($reports as $r): ?>
						<tr>
							<td><?php echo $itr++; ?></td>
							<td><?php echo $r['date']; ?></td>
							<td><?php echo $r['selling_price']; ?></td>	
							<td><?php echo $r['buying_price']; ?></td>
							<td><?php echo $r['profit']; ?></td>
						</tr>
					<?php
					$total_buying_price+=$r['buying_price']; 
					$total_selling_price+=$r['selling_price'];
					?>	
					<?php endforeach; ?>
					<tr>
						<td></td>
						<td></td>
						<td><?php echo "<b>Total</b>:".$total_selling_price; ?></td>
						<td><?php echo "<b>Total</b>:".$total_buying_price; ?></td>
						<td><?php echo "<b>Total</b>:".($total_selling_price-$total_buying_price); ?></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>


<?php require_once('includes/footer.php'); ?>