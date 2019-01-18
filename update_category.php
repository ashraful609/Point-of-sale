<?php
require_once('includes/functions/user_access.php');
require_once('core/database.php');
$error = 0;
if(isset($_POST['category_id']) && isset($_POST['category_name'])) {
	$db = new Database();
	$db->connect();
	$category_id = $_POST['category_id'];
	$category_name=$_POST['category_name'];
	$description=$_POST['description'];
	$sql = "SELECT category_id FROM category WHERE category_name='{$category_name}' AND category_id='{$category_id}' LIMIT 1";
	$db->set_sql($sql);
	$db->get_all_rows();
	if($db->get_num_rows() === 0) {
		echo "Category name already exists.";
		$error = 1;
	}
	if(!$error) {
		$sql = "UPDATE category SET category_name='{$category_name}',description='{$description}' WHERE category_id='{$category_id}'";
		$db->set_sql($sql);
		if($db->run_query()){
			echo "category updated";
		}
	}
}

?>