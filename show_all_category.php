<?php 
require_once('includes/header.php');

$products = array();
$db = new Database();
$db->connect();
$db->set_sql("SELECT * FROM category");
if($db->get_all_rows()) {
	$category = $db->get_result();
}
?>


<div class="container">
	<div class="row">
		<div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
			<h2>View Category</h2>
			<p class="text-info" id="msg">show category</p>
			<div class="table-responsive" id="all_category_table">
				<table class="table table-striped table-bordered table-hover" id="category_view_table">
					<thead>
						<tr>
							<td>SL#</td>
							<td>Category Name</td>
							<td>Description</td>
							<td>Action</td>
						</tr>
					</thead>
					<tbody>
					<?php $itr = 1; foreach($category as $c): ?>
						<tr>
							<td><?php echo $itr++; ?></td>
							<td><?php echo $c['category_name']; ?></td>
							<td><?php echo $c['description']; ?></td>
							<td class="actions">
								<button type="button" class="btn btn-primary" data-category-id="<?php echo $c['category_id']; ?>" data-toggle="modal" data-target="#editCategoryModal"><i class="fas fa-edit"></i></button>
							</td>

						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>



<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="update_category_form">
        	<input type="hidden" name="category_id" id="category_id" value="">
			<div class="form-group">
				<label for="category_name" class="col-form-label">Category Name *</label>
				<input type="text" class="form-control" name="category_name" id="category_name">
			</div>
			<div class="form-group">
				<label for="description" class="col-form-label">Description</label>
				<textarea class="form-control" rows="5" name="description" id="description"></textarea>
			</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="update_category_button">Update Category</button>
      </div>
    </div>
  </div>
</div>
<?php require_once('includes/footer.php'); ?>