<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit(0);
    die();
}
require_once('core/database.php');

if(isset($_POST['username']) && isset($_POST['password'])){

	$username=$_POST['username'];
	$password=$_POST['password'];
	$user_id="";
	if($username=="" && $password==""){

		echo "<p class='text-center'>Username and Password must be provided</p>";

	}
	else{
		$db=new Database;
		$db->connect();
		$sql="SELECT user_id FROM user WHERE username='$username' AND password=md5('$password') LIMIT 1";
		$db->set_sql($sql);
		$db->get_all_rows();
		$data=$db->get_result();

		if($db->get_num_rows()>0){
            $user_id=$data[0]['user_id'];
			$sql="UPDATE user SET last_login=now() WHERE user_id='$user_id'";
			$db->set_sql($sql);
			if($db->run_query()){
				$_SESSION['user_id']=$user_id;
				$_SESSION['username']=$username;

				if(!empty($_POST["remember_me"]))   
			   	{  
			    	setcookie ("username",$username,time()+ (3600*6*1000));  
			    	setcookie("user_id",$user_id,time()+ (3600*6*1000));
			   	}  
			   	else  
			   	{  
			    	if(isset($_COOKIE["username"]))
			    	{  
			     		setcookie ("username","",time()- (3600*6*1000));  
			    	}  
			    	if(isset($_COOKIE["usr_id"]))   
			    	{  
			     		setcookie ("user_id","",time()- (3600*6*1000));  
			    	}  
			   	}

				header("Location: index.php");

			}
		}
		else{
			echo "<p class='text-center'>Username and Password didn't match</p>";
		}

	}
}
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
<div class="login-form">
    <form action="#" method="post">
        <h2 class="text-center">Log in</h2>       
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Username" required="required">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required="required">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Log in</button>
        </div>
        <div class="clearfix">
            <label class="pull-left checkbox-inline"><input type="checkbox" name="remember_me"> Remember me</label>
            <a href="change_password.php" class="pull-right">Forgot Password?</a>
        </div>        
    </form>
</div>
</body>
</html>