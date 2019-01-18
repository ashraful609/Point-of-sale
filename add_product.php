<?php 
require_once('includes/header.php');

$category = array();
$variant = array('size', 'color');
$db = new Database();
$db->connect();
$db->set_sql("SELECT * FROM category ORDER BY category_name ASC");
if($db->get_all_rows()) {
	$category = $db->get_result();
}
// print_var($category);

if(isset($_POST['product_title']) && isset($_POST['product_quantity']) && isset($_POST['product_unit_price']) && isset($_POST['product_sell_price']) && isset($_POST['product_category'])){
	$error=0;
	if(empty($_POST['product_title'])) {
		$error = 1;
		echo "Product title cannot be empty";
	}
	if($_POST['product_quantity']<0){
		$error=1;
		echo "Quantity cannot be a negetive value <br>";
	}

	if($_POST['product_unit_price']<0){
		$error=1;
		echo "Product unit price cannot be a negetive value <br>";
	}

	if($_POST['product_quantity']<0){
		$error=1;
		echo "Product sell price cannot be a negetive value <br>";
	}

	if($error == 0) {

		$title = $_POST['product_title'];
		$description = $_POST['product_description'];
		$quantity = $_POST['product_quantity'];
		$unit_price = $_POST['product_unit_price'];
		$sell_price = $_POST['product_sell_price'];
		$category_id = $_POST['product_category'];
		$image_url = 'images/default_product_image.jpg';
		$color = 'None';
		$size = 'None';

		if(isset($_POST['product_variant'])){
			// print_var($_POST['product_variant']);
			for($i = 0; $i < sizeof($_POST['product_variant']['key']); $i++) {
				if($_POST['product_variant']['key'][$i] == 'color') {
					$color = $_POST['product_variant']['value'][$i];
				}
				if($_POST['product_variant']['key'][$i] == 'size') {
					$size = $_POST['product_variant']['value'][$i];
				}
			}
		}

		// CHECK FOR EXSISTING PRODUCT
		$sql = "SELECT p.product_id FROM product as p
			 LEFT JOIN product_variant as pv ON p.product_id=pv.product_id
		 	 WHERE p.title='{$title}' AND p.category_id='{$category_id}' AND pv.color='{$color}' AND pv.size='{$size}'";
		$db->set_sql($sql);
		$db->get_all_rows();
		if($db->get_num_rows() !== 0) {
			echo 'Product already exsists.';
			$error = 1;
		}
	}

	if($error == 0) {
		// INSERT PRODUCT INTO PRODUCT TABLE
		$sql = "INSERT INTO product VALUES (null, '{$title}', '{$description}', '{$unit_price}', '{$sell_price}', '1', now(), now(), '{$category_id}', '{$image_url}')";
		$db->set_sql($sql);
		if($db->run_query()) {
			// GET PRODUCT ID
			$product_id = $db->get_last_insert_id();

			if($product_id != null) {
				// INSERT VARIANTS INTO PRODUCT VARIANT
				$sql = "INSERT INTO product_variant VALUES (null, '{$size}', '{$color}', '{$quantity}', '{$product_id}')";
				$db->set_sql($sql);
				if($db->run_query()) {
					echo "Product added successfuly.";
				}else{
					echo "Product variant could not inserted.";
				}
			}else{
				echo "Cannot insert product.";
			}
		}else{
			echo "Database is not ready.";
		}
	}
}
?>
<div class="container">
	<div class="row">
		<div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
			<h2>Add Product</h2>
			<p class="text-warning">(*) - Fields are required</p>	
			<form action="#" method="POST">
				<div class="form-group">
					<label for="product_title">Product Title *</label>
					<input type="text" class="form-control" name="product_title" id="product_title" required>
				</div>
				<div class="form-group">
					<label for="product_quantity">Quantity *</label>
					<input type="number" class="form-control" name="product_quantity" id="product_quantity" required>
				</div>
				<div class="form-group">
					<label for="product_unit_price">Buy Price *</label>
					<input type="number" class="form-control" name="product_unit_price" id="product_unit_price" required>
				</div>
				<div class="form-group">
					<label for="product_sell_price">Sell Price *</label>
					<input type="number" class="form-control" name="product_sell_price" id="product_sell_price" required>
				</div>
				<div class="form-group">
					<label for="product_category">Category *</label>

					<select class="form-control" name="product_category" id="product_category">
						<option value="" class="d-none" selected>--- Select a category ---</option>
						<?php foreach ($category as $c): ?>
								<option value="<?php echo $c['category_id']; ?>"><?php echo $c['category_name']; ?></option>
						<?php endforeach; ?>
					</select>
					<p>or, <a href="add_category.php">Add new category</a></p>
				</div>
				<div class="form-group">
					<label for="product_description">Product Description</label>
  					<textarea class="form-control" rows="5" name="product_description" id="product_description"></textarea>
				</div>
				<div class="form-group row">
					<h4 class="text-left">Product variant:</h4>
					<button type="button" class="btn btn-secondary" id="product_variant_adder">Add a variant</button>
				</div>
				<div id="product_variant_section">
					
				</div>
				<button type="submit" class="btn btn-primary mb-2">Add Product</button>
			</form>
		</div>
	</div>

</div>

<script>
var product_variant_section_html = '<div class="form-group row"><select class="form-control col-lg-5 col-md-7 col-sm-7 col-xs-7 product_variant_selector" name="product_variant[key][]"><option value="" class="d-none" selected>Choose a variant</option><?php foreach ($variant as $key): ?><option value="<?php echo $key; ?>"><?php echo $key; ?></option><?php endforeach; ?></select><input class="col-lg-5 col-md-7 col-sm-7 col-xs-7" type="text" class="form-control" name="product_variant[value][]" placeholder="Value"><a class="variant_remover_link" onClick="remove_variant(this)"><i class="fas fa-trash-alt"></i></a></div>';
</script>

<?php require_once('includes/footer.php'); ?>