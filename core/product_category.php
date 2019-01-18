<?php 
require_once('includes/functions/utils.php');
require_once('includes/header.php');
require_once('core/database.php');
?>



<div class="container">
	<div class="row">
		<div class=""col-lg-6 col-md-8 col-sm-12 col-xs-12"">
			<h2>Add Category</h2>
			<p class="text-warning">(*) - Fields are required</p>
			<form action="#" method="POST">
				<div class="form-group">
					<label for="category_name">Category Name *</label>
					<input type="text" class="form-control" name="category_name" id="category_name">
				</div>
				<div class="form-group">
					<label for="description">Description *</label>
					<input type="text" class="form-control" name="description" id="description">
				</div>
				<div class="form-group">
					<label for="created">Created *</label>
					<input type="date" class="form-control" name="created" id="created">
				</div>
				<button type="submit" class="btn btn-primary mb-2">Add Category</button>

			</form>
			
		</div>

	</div>
	



</div>