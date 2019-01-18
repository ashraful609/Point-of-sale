<?php 
require_once('includes/header.php');

$error = array();
$users = array();
$db = new Database();
$db->connect();
$sql = "SELECT ud.first_name, ud.last_name, ud.email, ud.registered, ud.phone, ud.address, u.last_login, u.username, u.user_id, u.user_detail_id FROM user as u LEFT JOIN user_detail ud ON u.user_id=ud.user_detail_id WHERE u.user_status='1'";
$db->set_sql($sql);
$db->get_all_rows();
if($db->get_num_rows() !== 0) {
	$users = $db->get_result();
}else{
	$error['no_user_found'] = 1;
}

?>
<div class="container">
	<div class="row">
		<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
			<h2>View User</h2>
			<p class="text-info" id="error_status">show all users</p>	
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="user_view_table">
					<thead>
						<tr>
							<td>SL#</td>
							<td>Name</td>
							<td>Phone</td>
							<td>Address</td>
							<td>Email</td>
							<td>Last Login</td>
							<td>Registered</td>
							<td>Action</td>
						</tr>
					</thead>
					<tbody>
					<?php if(isset($error['no_user_found'])): ?>
						<td colspan="8"><p>No user registered in database. You are in Admin mode.</p></td>
					<?php else: ?>
					<?php $itr = 1; foreach($users as $u): ?>
						<tr>
							<td><?php echo $itr++; ?></td>
							<td><?php echo $u['first_name'] . ' ' . $u['last_name']; ?></td>
							<td><?php echo $u['phone']; ?></td>
							<td><?php echo $u['address']; ?></td>
							<td><?php echo $u['email']; ?></td>
							<td><?php echo date('d M, Y', strtotime($u['last_login'])); ?></td>
							<td><?php echo date('d M, Y', strtotime($u['registered'])); ?></td>
							<td>
								<button type="button" class="btn btn-primary" data-user-id="<?php echo $u['user_id']; ?>" data-toggle="modal" data-target="#deleteUserModal"><i class="fas fa-trash"></i></button>
								<button type="button" class="btn btn-primary" data-user-id="<?php echo $u['user_id']; ?>" data-toggle="modal" data-target="#editUserModal"><i class="fas fa-edit"></i></button>
							</td>
						</tr>
					<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- DELETE PRODUCT MODAL -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
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
        <button type="button" class="btn btn-danger" id="delete_user_button">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- UPDATE PRODUCT MODAL -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST">
        		<input type="hidden" name="user_id" id="user_id">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="firstname">First Name*</label>
						<input type="text" name="firstname" id="firstname" class="form-control">
						<div id="firstname_error"></div>
					</div>
					<div class="form-group col-md-6">
						<label for="lastname">Last Name*</label>
						<input type="text" name="lastname" id="lastname" class="form-control">
						<div id="lastname_error"></div>
					</div>
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" name="email" id="email" class="form-control">
					<div id="email_error"><?php echo_var($error["email_error"]); ?></div>
				</div>
				<div class="form-group">
					<label for="phone">Phone</label>
					<input type="text" name="phone" id="phone" class="form-control">
					<div id="phone_error"></div>
				</div>
				<div class="form-group">
					<label for="address">Address</label>
					<textarea class="form-control" rows="5" name="address" id="address"></textarea>
					<div id="address_error"></div>
				</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="update_user_button">Update User</button>
      </div>
    </div>
  </div>
</div>

<?php require_once('includes/footer.php'); ?>