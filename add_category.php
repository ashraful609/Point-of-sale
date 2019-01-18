<?php 
require_once('includes/functions/utils.php');
require_once('includes/header.php');
require_once('core/database.php');

if( isset($_POST['category_name'])){
	$category_name=$_POST['category_name'];
	$description=$_POST['description'];

	$db=new Database;
	$db->connect();
	$sql = "SELECT category_id FROM category WHERE category_name='{$category_name}' LIMIT 1";
	$db->set_sql($sql);
	$db->get_all_rows();
	if($db->get_num_rows() === 1) {
		$error['msg'] = "Category name already exists.";
	}
	if(empty($error)) {
		$sql = "INSERT INTO category VALUES (null, '{$category_name}', '{$description}', now())";
	    $db->set_sql($sql);
	    if($db->run_query()) {
	        $error['msg'] = "CATEGORY ADDED";
	    }else{
	        $error['msg'] = "CAN NOT ADD CATEGORY";
	    }
	}
}
?>
<div class="container" id="add_category_container">
	<div class="row">
		<div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
			<h2>Add Category</h2>
			<p class="text-info">
				<?php if(isset($error['msg'])) {echo $error['msg'];}else{ ?>
				(*) - Fields are required
				<?php } ?>
			</p>
			<form action="#" method="POST">
				<div class="form-group">
					<label for="category_name">Category Name *</label>
					<input type="text" class="form-control" name="category_name" id="category_name">
				</div>
				<div class="form-group">
					<label for="description">Description </label>
					<textarea class="form-control" rows="5" name="description" id="description"></textarea>
				</div>

				<button type="submit" class="btn btn-primary mb-2">Add Category</button>

			</form>
			
		</div>

	</div>
	



</div>



<?php require_once('includes/footer.php'); ?>