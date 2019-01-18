<?php
    require_once('includes/functions/user_access.php');
    require_once('includes/functions/utils.php');
    require_once('core/database.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Document</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="css/vendor/bootstrap/bootstrap-reboot.min.css">
    <link rel="stylesheet" href="css/vendor/fontawesome/all.css">
	<link rel="stylesheet" href="css/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/vendor/bootstrap/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/vendor/navbars/sidenav.css">
    <link rel="stylesheet" href="css/vendor/autocomplete/autocomplete.css">
    <link rel="stylesheet" href="css/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/vendor/datetime/jquery.js"></script>
    <script src="js/vendor/datetime/jquery.datetimepicker.full.js"></script>
    
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="index.php">
                    
                    <img id="sidebar-brand-logo" src="images/logo.jpg" width="130"  class="d-inline-block align-top" alt="">
                </a>
            </div>
            <button type="button" id="sidebarCollapse" class="btn btn-info">
                <i class="fas fa-align-left"></i>
                <span>Toggle</span>
            </button>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" >
                        <a href="index.php">
                            <i class="fas fa-home"></i>
                            Home
                        </a>
                    </a>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-copy"></i>
                        Product
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="add_product.php">Add Product</a>
                        </li>
                        <li>
                            <a href="view_product.php">Show Product</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-boxes"></i>
                        Category
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubmenu2">
                        <li>
                            <a href="add_category.php">Add Category</a>
                        </li>
                        <li>
                            <a href="show_all_category.php">Show Category</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="order_list.php"><i class="fas fa-clipboard-list"></i> Order List</a>
                </li>
                <li>
                    <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-user"></i>
                        User
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubmenu3">
                        <li>
                            <a href="add_user.php">Add User</a>
                        </li>
                        <li>
                            <a href="view_user.php">Show User</a>
                        </li>
                        <li>
                            <a href="change_password.php">Change Password</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#pageSubmenu4" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="far fa-list-alt"></i>
                        Reports
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubmenu4">
                        <li>
                            <a href="sell_ledger.php">Most Sells</a>
                        </li>
                        <li>
                            <a href="reports.php">Reports</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">

                                <a class="nav-link" ><b> <?php if(isset($_SESSION['username'])) {echo $_SESSION['username'];} ?> </b></a>

                            </li>
                            <div style="border-left:1px solid #000;height:20px;margin-top: 8px"></div>
                            <li class="nav-item active">
                                <a class="nav-link" href="logout.php"><b>Logout</b></a>
                            </li>
                            <li class="nav-item active">
                                <!-- <a class="nav-link" href="#">Page</a> -->
                            </li>
              <!--               <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </nav>


            
