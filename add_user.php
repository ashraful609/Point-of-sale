<?php 
require_once('includes/header.php');
$error = array();
if(isset($_POST['signup_submit']) && isset($_POST['firstname']) && isset($_POST['lastname'])
 && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])
  && isset($_POST['confirm_password'])) {
    
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
	$phone = 'None';
	$address = 'None';
	if(isset($_POST['phone'])) {$phone = $_POST['phone'];}
	if(isset($_POST['address'])) {$address = $_POST['address'];}
    
    if(empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) { 
        $error["form_failed"] = "(*) fields are required";
    }else{
        if(strlen($password) < 6) { $error["password_error"] = "Password should be at least 6 characters."; }
        if(strcmp($password, $confirm_password) != 0) { $error["confirm_password_error"] = "Both password should be same"; }

        // email validation
        $pos_amp_email = strrpos($email, '@');
        $pos_dot_email = strrpos($email, ".");
        if($pos_amp_email < 1 || $pos_dot_email < $pos_amp_email + 2 || strlen($email) < $pos_dot_email + 2) {
            $error["email_error"] = "Invalid format of email.";
        }

        // check existing username
        $sql = "SELECT username FROM user WHERE username='{$username}' LIMIT 1";
        $db = new Database();
        $db->connect();
        $db->set_sql($sql);
        $db->get_all_rows();
        $query_result = $db->get_result();
        if(!empty($query_result) && $query_result[0]['username'] == $username) {        
            $error["username_error"] = "Username already exists.";
        }
    }
    // add to db
    if(empty($error)) {
		$user_detail_id = 0;
		$password = md5($password);

		$sql = "INSERT INTO user_detail VALUES (null, 'None', '{$firstname}', '{$lastname}', '{$email}', now(), '{$phone}', '{$address}')";
		$db->set_sql($sql);
		if($db->run_query()) {
			$user_detail_id = $db->get_last_insert_id();
			$sql = "INSERT INTO user VALUES (null, '{$username}', '{$password}', '1', '0000-00-00 00:00:00', '{$user_detail_id}')";
			$db->set_sql($sql);
			if($db->run_query()) {
				$error['form_failed'] = 'User <strong>' . $username . '</strong> added successfully.';
			}else{
				$sql = "DELETE FROM user_detail WHERE user_detail_id='{$user_detail_id}'";
				$error['form_failed'] = 'User <strong>' . $username . '</strong> add failed.';
			}
		}else{
			$error['form_failed'] = 'Database not responding, contact developer.';
		}
    }
}
?>
<div class="container">
	<div class="row">
		<div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
			<h2>Add User</h2>
			<p class="text-warning">(*) - Fields are required</p>	
			<form action="#" method="POST" name="signup_form" onsubmit="return signup_form_validation()">
				<div id="form-error">
					<?php echo_var($error["form_failed"]); ?>
				</div>
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
					<label for="username">Username*</label>
					<input type="text" name="username" id="username" class="form-control">
					<div id="username_error"><?php echo_var($error["username_error"]); ?></div>
				</div>
				<div class="form-group">
					<label for="password">Password*</label>
					<input type="password" name="password" id="password" class="form-control">
					<div id="password_error"><?php echo_var($error["password_error"]); ?></div>
				</div>
				<div class="form-group">
					<label for="confirm_password">Confirm Password*</label>
					<input type="password" name="confirm_password" id="confirm_password" class="form-control">
					<div id="confirm_password_error"><?php echo_var($error["confirm_password_error"]); ?></div>
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
				<input class="btn btn-primary" type="submit" name="signup_submit" value="Add User">
			</form>
		</div>
	</div>

</div>

<script>
	function signup_form_validation() {
    var err = 0;
    var firstname = document.signup_form.firstname;
    var lastname = document.signup_form.lastname;
    var email = document.signup_form.email;
    var username = document.signup_form.username;
    var password = document.signup_form.password;
    var confirm_password = document.signup_form.confirm_password;
    var phone = document.phone;
    var address = document.signup_form.address;

    var firstname_error = document.getElementById("firstname_error");
    var lastname_error = document.getElementById("lastname_error");
    var email_error = document.getElementById("email_error");
    var username_error = document.getElementById("username_error");
    var password_error = document.getElementById("password_error");
    var confirm_password_error = document.getElementById("confirm_password_error");
    var phone_error = document.getElementById("phone_error");
    var address_error = document.getElementById("address_error");

    firstname_error.innerHTML = "";
    lastname_error.innerHTML = "";
    email_error.innerHTML = "";
    username_error.innerHTML = "";
    password_error.innerHTML = "";
    confirm_password_error.innerHTML = "";
    phone_error.innerHTML = "";
    address_error.innerHTML = "";

    if (firstname.value.length == 0) {
        firstname_error.innerHTML = "Firstname is required.";
        err = 1;
    }
    if (lastname.value.length == 0) {
        lastname_error.innerHTML = "Lastname is required.";
        err = 1;
    }
    
    var char_amp_pos = email.value.lastIndexOf("@");
    var char_dot_pos = email.value.lastIndexOf(".") ;
    if (email.value.length == 0) {
        email_error.innerHTML = "Email is required.";
        err = 1;
    }else if (char_amp_pos < 1 || char_dot_pos < char_amp_pos+2 || email.value.length < char_dot_pos + 2) {
        email_error.innerHTML = "Invalid format of email.";
        err = 1;
    }
    if (username.value.length == 0) {
        username_error.innerHTML = "Username is required.";
        err = 1;
    }
    if (password.value.length == 0) {
        password_error.innerHTML = "Password is required.";
        err = 1;
    }else if (password.value.length < 6) {
        password_error.innerHTML = "Password should be at least 6 characters.";
        err = 1;
    }
    if (confirm_password.value.length == 0) {
        confirm_password_error.innerHTML = "Confirm Password is required.";
        err = 1;
    }else if (password.value != confirm_password.value) {
        confirm_password_error.innerHTML = "Both password should be same.";
        err = 1;
    }

    return (err == 0) ? true : false;
}
</script>
<?php require_once('includes/footer.php'); ?>