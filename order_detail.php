<?php
require_once('includes/header.php');
if(!isset($_GET['id'])) {
    echo '<h3>No order selected!</h3>';
    echo '<a href="order_list.php" style="text-decoration: underline;">Go to order list</a>';
    exit(0);
    die();
}
$error = array();
$order = array();
$db = new Database();
$db->connect();
$order_id = $_GET['id'];

$sql = "SELECT o.order_id, o.created, o.total_amount, o.discount FROM `order` as o WHERE o.order_id='{$order_id}' LIMIT 1";
	$db->set_sql($sql);
	$db->get_all_rows();
if($db->get_num_rows() === 1) {
    $r = $db->get_result();
    $order[0] = $r[0];
}else{
    $error['msg'] = 'Order does not exists.';
}
$sql = "SELECT p.product_id, p.title, p.unit_price, p.sale_price,(SELECT category_name FROM category as c WHERE p.category_id=c.category_id) as category_name,pv.size, pv.color, pv.quantity, od.order_id, od.quantity as ordered_quantity, od.sub_total, od.remark, od.discount FROM product as p LEFT JOIN product_variant as pv ON p.product_id=pv.product_id RIGHT JOIN order_detail as od ON p.product_id=od.product_id WHERE od.order_id='{$order_id}'";
$db->set_sql($sql);
$db->get_all_rows();
if($db->get_num_rows() > 0) {
    $order[0]['ordered_product'] = $db->get_result();
}else{
    $error['msg'] = 'Order does not exists.';
}
// echo '<pre>'; print_r($order); echo '</pre>';

?>
<div class="container mt-5">
	<div class="row">
		<div id="ordered_product_list_container" class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <p><a href="order_list.php" style="text-decoration: underline;">Back to order list</a></p>
			<h2>Order Detail</h2>
            <?php if(!empty($error)): ?>
                <p class="text-danger"><?php echo $error['msg']; ?></p>
            <?php else: ?>
                <p class="text-dark">Order ID# <?php echo $order[0]['order_id']; ?></p>
                <p class="text-dark">Date: <?php echo date('d M, Y g:i:s a',strtotime($order[0]['created'])); ?></p>
                <p class="text-dark">Net Amount: BDT <?php echo $order[0]['total_amount']; ?></p>
                <p class="text-dark">Discount: BDT <?php echo $order[0]['discount']; ?></p>
                <div class="p-3 mb-2 bg-success text-white d-none" id="msg"></div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="order_list_table">
                        <thead>
                            <tr>
                                <td>SL#</td>
                                <td>Product</td>
                                <td>Qty</td>
                                <td>Discount(BDT)</td>
                                <td>Subtotal(BDT)</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody id="ordered_product_list">
                            <?php $itr = 1; foreach($order[0]['ordered_product'] as $p): ?>
                                <tr>
                                    <td><?php echo $itr++; ?></td>
                                    <td>
                                        <p><strong><?php echo $p['title']; ?></strong></p>
                                        <sup><?php echo $p['category_name']; ?> | Color: <?php echo $p['color']; ?>, Size: <?php echo $p['size']; ?><br></sup>
                                    </td>
                                    <td><?php echo $p['ordered_quantity']; ?></td>
                                    <td><?php echo $p['discount']; ?></td>
                                    <td><?php echo $p['sub_total']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary"
                                         data-product-id="<?php echo $p['product_id']; ?>"
                                         data-product-quantity="<?php echo $p['ordered_quantity']; ?>"
                                         data-toggle="modal"
                                         data-target=".returnProductModal"
                                         <?php if($p['ordered_quantity'] < 1) echo 'disabled'; ?>
                                         >Return Product</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
		</div>
	</div>
</div>
<!-- RETURN_PRODUCT_MODAL -->
<div class="modal fade returnProductModal" tabindex="-1" role="dialog" aria-labelledby="returnProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="returnProductModalLabel">Return Product Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="error_msg" class="text-danger"></div>
        <form action="#" method="POST">
          <div class="form-group">
            <label for="return_quantity" class="col-form-label">Return quantity:</label>
            <input type="number" class="form-control" id="return_quantity" min="1" value="1">
          </div>
          <div class="form-group">
            <label for="return_remark" class="col-form-label">Cause of return:</label>
            <textarea class="form-control" id="return_remark"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="submit_return_product" class="btn btn-primary">Return</button>
      </div>
    </div>
  </div>
</div>
<?php require_once('includes/footer.php'); ?>
<script>
$(document).ready(function () {
    var order_id = <?php echo $order_id; ?>;
    var total_amount = <?php echo $order[0]['total_amount'] ?>;
    $('.returnProductModal').on('show.bs.modal', function (e) {
        var modal = $(this);
        var button = $(e.relatedTarget); // Button that triggered the modal
        var product_id = button.data('product-id'); // Extract info from data-*
        var submit_btn = modal.find('#submit_return_product');
        

        // FORM SUBMIT TRIGGER
        $(submit_btn).on('click', function (e) {
            e.preventDefault();
            var return_qty = modal.find('#return_quantity').val();
            var remark = modal.find('#return_remark').val();
            var form_data = {
                order_id:order_id,
                product_id:product_id,
                return_qty:return_qty,
                remark:remark
            };
            $.ajax({
                url: 'return_product.php',
                method: 'POST',
                data: form_data,
                dataType: 'json',
                success: function(data) {
                    if('error' in data) {
                        modal.find('#error_msg').text(data.error);
                    }else{
                        $('#msg').removeClass('d-none');
                        $('#msg').text(data.success);
                        $('#msg').append('<a href="return_print_memo.php?id='+order_id+'&paid='+ total_amount +'" style="text-decoration:underline" target="blank">Re-print recipt.</a>');
                        $('.returnProductModal').modal('hide');
                    }
                }
            });
        });
    });
});
</script>