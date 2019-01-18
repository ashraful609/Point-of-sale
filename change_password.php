<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['user_id'])) {
	require_once('includes/header.php');
}else{ 
	require_once('includes/functions/utils.php');
	require_once('core/database.php');

?>

	<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Page</title>
<link rel="stylesheet" href="css/vendor/bootstrap/bootstrap.min.css">
<script src="js/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="js/vendor/bootstrap/bootstrap.min.js"></script> 
<style type="text/css">
	.login-form {
		width: 340px;
    	margin: 50px auto;
	}
    .login-form form {
    	margin-bottom: 15px;
        background: #f7f7f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .form-control, .btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .btn {        
        font-size: 15px;
        font-weight: bold;
    }
</style>
</head>
<body>
<?php } 
$error = array();
if(isset($_POST['change_pass_submit']) && isset($_POST['email']) && isset($_POST['password'])
  && isset($_POST['confirm_password'])) {
	$user_id = -1;
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if(empty($email) || empty($password) || empty($confirm_password)) { 
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
        $sql = "SELECT user_id FROM user WHERE user_detail_id=(SELECT user_detail_id FROM user_detail WHERE email='{$email}' LIMIT 1) LIMIT 1";
        $db = new Database();
        $db->connect();
        $db->set_sql($sql);
        $db->get_all_rows();
        if($db->get_num_rows() === 1) {
        	$query_result = $db->get_result();
        	$user_id = $query_result[0]['user_id'];
        }else{
        	$error["email_error"] = "Email not registered.";
        }
    }
    // change pass
    if(empty($error)) {
		
		$password = md5($password);

		$sql = "UPDATE user SET `password`='{$password}' WHERE user_id='{$user_id}'";
		$db->set_sql($sql);
		if($db->run_query()) {
				$error['form_success'] = 'Password has been changed successfully.';
				if(!isset($_SESSION['user_id'])) {header('Location: login.php');}
			
		}else{
			$error['form_failed'] = 'Database not responding, contact developer.';
		}
    }
}
?>

<div class="container">
	<div class="row">
		<div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
			<h2>Change Password</h2>
			<p class="text-warning">(*) - Fields are required</p>	
			<form action="#" method="POST" name="change_pass_form" onsubmit="return change_pass_form_validation()">
				<div class="text-danger mt-1 mb-1" id="form-error">
					<?php echo_var($error["form_failed"]); ?>
				</div>
				<div class="text-success mt-1 mb-1" id="form-success">
					<?php echo_var($error["form_success"]); ?>
				</div>
				<div class="form-group">
					<label for="email">Registered Email Address*</label>
					<input type="email" name="email" id="email" class="form-control">
					<div class="text-danger mt-1" id="email_error"><?php echo_var($error["email_error"]); ?></div>
				</div>
				<div class="form-group">
					<label for="password">Password*</label>
					<input type="password" name="password" id="password" class="form-control">
					<div class="text-danger mt-1" id="password_error"><?php echo_var($error["password_error"]); ?></div>
				</div>
				<div class="form-group">
					<label for="confirm_password">Confirm Password*</label>
					<input type="password" name="confirm_password" id="confirm_password" class="form-control">
					<div class="text-danger mt-1" id="confirm_password_error"><?php echo_var($error["confirm_password_error"]); ?></div>
				</div>
				<input class="btn btn-primary" type="submit" name="change_pass_submit" value="Change Password">
			</form>
		</div>
	</div>

</div>
<script>
	function change_pass_form_validation() {
    var err = 0;
    var email = document.change_pass_form.email;
    var password = document.change_pass_form.password;
    var confirm_password = document.change_pass_form.confirm_password;

    var email_error = document.getElementById("email_error");
    var password_error = document.getElementById("password_error");
    var confirm_password_error = document.getElementById("confirm_password_error");

    email_error.innerHTML = "";
    password_error.innerHTML = "";
    confirm_password_error.innerHTML = "";
    
    var char_amp_pos = email.value.lastIndexOf("@");
    var char_dot_pos = email.value.lastIndexOf(".") ;
    if (email.value.length == 0) {
        email_error.innerHTML = "Email is required.";
        err = 1;
    }else if (char_amp_pos < 1 || char_dot_pos < char_amp_pos+2 || email.value.length < char_dot_pos + 2) {
        email_error.innerHTML = "Invalid format of email.";
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
<?php
if(isset($_SESSION['user_id'])) {
	require_once('includes/footer.php');
}else{ ?>
	</body>
	</html>

<?php } ?>