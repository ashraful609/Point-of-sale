<?php 
require_once('includes/header.php');
require_once('core/database.php');
$order_list = array();
$db = new Database();
$db->connect();
$sql = "SELECT * FROM `order`";
if(isset($_POST['order_search_id'])) {
	$id=$_POST['order_search_id'];
	$sql = "SELECT * FROM `order` WHERE order_id='{$id}' LIMIT 1";
}
$db->set_sql($sql);
$db->get_all_rows();
if($db->get_num_rows()) {
	$order_list = $db->get_result();
// echo '<pre>';print_r($order_list);echo '</pre>';
}

?>

<style>
    table tr.row-no-style, table tr.row-no-style td{
      background: #FFF;
    }
</style>
<iframe id="txtArea1" style="display:none"></iframe>
<div class="container">
	<!--Make sure the form has the autocomplete function switched off:-->
	<form name="order_search" method="POST" action="#" autocomplete="off">
		<div class="input-group">
			<input type="text" class="form-control" name="order_search_id" value="">
			<div class="input-group-btn">
				<button class="btn btn-default" id="order_search_button" type="submit"><i class="fa fa-search"></i> Find Order</button>
			</div>
		</div>
	</form>
	<p id="test"></p>
</div>


<div class="container mt-5">
	<div class="row">
		<div id="order_list_container" class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
			<h2>Order List</h2>
			<p class="text-info">view ordered products</p>	
			<div class="mt-2 mb-3">
				<button class="btn btn-outline-primary" id="reloader"><i class="fas fa-sync-alt"></i> Reload</button>
				<button class="btn btn-outline-info" id="btnExport" name="export" onclick="fnExcelReport('order_list_table');">Export</button>
			</div>
										
			<div class="table-responsive">
				<form id="cart_data">
					<table class="table table-striped table-bordered table-hover" id="order_list_table">
						<thead>
							<tr>
								<td>SL#</td>
								<td>Order ID</td>
								<td>Date</td>
								<td>Price</td>
								<td>Discount</td>
								<td>Action</td>
							</tr>
						</thead>
						<tbody id="order_list">
							<?php $itr=1; foreach ($order_list as $list) : ?>
								<tr>
									<td><?php echo $itr++; ?></td>
									<td><?php echo $list['order_id']; ?></td>
									<td><?php echo date('d M,Y g:i:s a',strtotime($list['created'])); ?></td>
									<td><?php printf('%.2f', $list['total_amount']); ?></td>
									<td><?php printf('%.2f', $list['discount']); ?></td>
									<td>
										<a href="order_detail.php?id=<?php echo $list['order_id']; ?>" class="btn btn-primary"><i class="fas fa-info-circle"></i></a>
										<a class="btn btn-primary" href="print_memo.php?id=<?php echo $list['order_id']; ?>&paid=0" target="_blank"><i class="fas fa-receipt"></i></a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>

<?php require_once('includes/footer.php'); ?>
<script>

	$('#reloader').on('click', function(event) {
		event.preventDefault();
		window.location.href = 'order_list.php';
	});
	
	function str_to_int(s) {
		s = parseInt(s);
		return (isNaN(s)) ? 0 : s; 
	}
</script>