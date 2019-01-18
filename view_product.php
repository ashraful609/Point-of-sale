<?php 
require_once('includes/header.php');
$products = array();
$category = array();
$db = new Database();
$db->connect();
$sql = "SELECT * FROM product WHERE active=1";
if(isset($_POST['product_search_id'])) {
	$id = $_POST['product_search_id'];
	$sql = "SELECT * FROM product WHERE product_id='{$id}' AND active=1 LIMIT 1";
}
$db->set_sql($sql);
if($db->get_all_rows()) {
	$products = $db->get_result();
}

$sql = "SELECT * FROM category";
$db->set_sql($sql);
if($db->get_all_rows()) {
	$categories = $db->get_result();
}

$temp = array();
foreach($products as $p) {
	$product_id = $p['product_id'];
	$category_id = $p['category_id'];
	$variant_sql = "SELECT * FROM product_variant WHERE product_id='{$product_id}' LIMIT 1";
	$category_sql = "SELECT * FROM category WHERE category_id='{$category_id}' LIMIT 1";
	$db->set_sql($variant_sql);
	if($db->get_all_rows() ) {
		$variants = $db->get_result();
		if(!empty($variants)) {$p['variants'] = $variants[0];}
	}
	$db->set_sql($category_sql);
	if($db->get_all_rows()) {
		$product_category = $db->get_result();
		$p['category'] = $product_category[0];
	}
	$temp[] = $p;
 }
$products = $temp;
// print_var($products);
?>
<iframe id="txtArea1" style="display:none"></iframe>
<div class="container">
	<!--Make sure the form has the autocomplete function switched off:-->
	<form action="#" name="search_product_frm" method="POST" autocomplete="off">
		<div class="autocomplete input-group">
			<input type="text" class="form-control" name="product_search_id" id="product_search_bar" value="" data-product-id="">
			<div class="input-group-btn">
				<button class="btn btn-default" id="search_product_btn">
					<i class="fa fa-search"></i> Find Product
				</button>
			</div>
		</div>
	</form>
	<p id="test"></p>
</div>

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h2>View Product</h2>
			<p class="text-info" id="error_status">show products</p>	
			<div class="mt-2 mb-3">
				<button class="btn btn-outline-primary" id="reloader"><i class="fas fa-sync-alt"></i> Reload</button>
				<button class="btn btn-outline-info" id="btnExport" name="export" onclick="fnExcelReport('product_view_table');">Export</button>
			</div>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="product_view_table">
					<thead>
						<tr>
							<td>SL#</td>
							<td>Product Name</td>
							<td>Category</td>
							<td>Qty.</td>
							<td>Buy Price</td>
							<td>Sale Price</td>
							<td>Size</td>
							<td>Color</td>
							<td>Action</td>
						</tr>
					</thead>
					<tbody>
					<?php $itr = 1; foreach($products as $p): ?>
						<tr>
							<td><?php echo $itr++; ?></td>
							<td><?php echo $p['title']; ?></td>
							<td><?php echo $p['category']['category_name']; ?></td>
							<td><?php if(isset($p['variants'])) {echo $p['variants']['quantity'];} ?></td>
							<td><?php echo $p['unit_price']; ?></td>
							<td><?php echo $p['sale_price']; ?></td>
							<td><?php if(isset($p['variants'])) {echo $p['variants']['size'];} ?></td>
							<td><?php if(isset($p['variants'])) {echo $p['variants']['color'];} ?></td>
							<td>
								<button type="button" class="btn btn-primary" data-product-id="<?php echo $p['product_id']; ?>" data-toggle="modal" data-target="#deleteProductModal"><i class="fas fa-trash"></i></button>
								<button type="button" class="btn btn-primary" data-product-id="<?php echo $p['product_id']; ?>" data-toggle="modal" data-target="#editProductModal"><i class="fas fa-edit"></i></button>
								<button type="button" class="btn btn-primary" data-product-id="<?php echo $p['product_id']; ?>" data-toggle="modal" data-target="#getBarcodeModal"><i class="fas fa-barcode"></i></button>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- BARCODE MODAL -->
<div class="modal fade" id="getBarcodeModal" tabindex="-1" role="dialog" aria-labelledby="getBarcodeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="getBarcodeModalLabel">Get Barcode</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Your barcode here</p>
        <canvas id="barcode"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="get_barcode_btn">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- DELETE PRODUCT MODAL -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteProductModalLabel">Delete Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete these Records?</p>
        <p class="warning"><small>This action cannot be undone.</small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="delete_product_button">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- UPDATE PRODUCT MODAL -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST">
        		<input type="hidden" name="product_id" id="product_id">
				<div class="form-group">
					<label for="product_title">Product Title *</label>
					<input type="text" class="form-control" name="product_title" id="product_title">
				</div>
				<div class="form-group">
					<label for="product_quantity">Quantity *</label>
					<input type="number" class="form-control" name="product_quantity" id="product_quantity" required="required">
				</div>
				<div class="form-group">
					<label for="product_unit_price">Buy Price *</label>
					<input type="number" class="form-control" name="product_unit_price" id="product_unit_price">
				</div>
				<div class="form-group">
					<label for="product_sell_price">Sell Price *</label>
					<input type="number" class="form-control" name="product_sell_price" id="product_sell_price">
				</div>
				<div class="form-group">
					<label for="product_category">Category *</label>
					<select class="form-control" name="product_category" id="product_category">
						<?php foreach($categories as $c): ?>
							<option value="<?php echo $c['category_id']; ?>"><?php echo $c['category_name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="product_color">Color</label>
					<input type="text" class="form-control" name="product_color" id="product_color">
				</div>
				<div class="form-group">
					<label for="product_size">Size</label>
					<input type="text" class="form-control" name="product_size" id="product_size">
				</div>
				<div class="form-group">
					<label for="product_description">Product Description</label>
  					<textarea class="form-control" rows="5" name="product_description" id="product_description"></textarea>
				</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="update_product_button">Update Product</button>
      </div>
    </div>
  </div>
</div>

<?php require_once('includes/footer.php'); ?>
<script>
	var search_key = [];
	var detail = [];
	$.ajax({
		url: 'fetch_product_for_search.php',
		success: function(data) {
			var result = JSON.parse(data);
			for(var x in result) {
				search_key.push(result[x].title);
				detail.push(result[x]);
			}
		}
	}); 

	autocomplete(document.getElementById("product_search_bar"), search_key, detail);

    $('#search_product_btn').on('click', function (event) {
    	event.preventDefault();
    	var id = $('#product_search_bar').attr('data-product-id');
    	$('#product_search_bar').val(id);
    	$('form[name=search_product_frm]').submit();
    });

    $('#reloader').on('click', function(event) {
		event.preventDefault();
		window.location.href = 'view_product.php';
	});
</script>