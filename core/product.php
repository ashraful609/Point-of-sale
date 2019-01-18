<?php
require_once('database.php');

class Product{
	private $product_id;
	private $title;
	private $description;
	private $unit_price;
	private $sale_price;
	private $active_status;
	private $created_date;
	private $edited_date;
	private $category_id;
	private $image_url;
	private $product_variant_id;

	public function __construct(){
		$this->product_id = null;
		$this->title = '';
		$this->description = '';
		$this->unit_price = 0.0;
		$this->sale_price = 0.0;
		$this->active_status = false;
		$this->created_date = null;
		$this->edited_date = null;
		$this->image_url = null;
		$this->category_id = null;
		$this->product_variant_id = null;
	}

	// SETTERS AND GETTERS 
	public function get_product_id() {return $this->product_id;}

	public function set_product_id($id) {$this->product_id = $id;}

	public function set_title($title) {$this->title = $title;}

	public function get_title() {return $this->title;}

	public function set_description($description) {$this->description = $description;}

	public function get_description() {return $this->description;}

	public function set_unit_price($unit_price) {$this->unit_price = $unit_price;}

	public function get_unit_price() {return $this->unit_price;}

	public function set_sale_price($sale_price) {$this->sale_price = $sale_price;}

	public function get_sale_price() {return $this->sale_price;}

	public function set_active_status($active_status) {$this->active_status = $active_status;}

	public function get_active_status() {return $this->active_status;}

	public function set_created_date($created_date) {$this->created_date = $created_date;}

	public function get_created_date() {return $this->created_date;}

	public function set_edited_date($edited_date) {$this->edited_date = $edited_date;}

	public function get_unit_edited_date() {return $this->edited_date;}

	public function get_image_url() { return $this->image_url;}

	public function set_image_url($url) { $this->image_url = $url;}

	public function get_category_id() {return $this->category_id;}

	public function set_category_id($id) {$this->category_id = $id;}

	public function get_product_variant_id() {return $this->product_variant_id;}

	public function set_product_variant_id($id) {$this->product_variant_id = $id;}

	public function retrive_product($sql) {
		$db = new Database();
		if(!$db->connect()) {
			return false; // Database connection error
		}
		$db->set_sql($sql);
		if(!$db->get_all_rows()) {
			return false; // Wrong query
		}
		$result = array();
		if(!$db->get_num_rows()) {return false;} // Match not found
		$results = $db->get_result();
		echo "<pre>";print_r($result);echo "</pre>";
		foreach ($results as $result) {
			$product = new Product();
			$product->product_id = $result['product_id'];
			$product->title = $result['title'];
			$product->description = $result['description'];
			$product->unit_price = $result['unit_price'];
			$product->sale_price = $result['sale_price'];
			$product->active_status = $result['active'];
			$product->created_date = $result['created'];
			$product->edited_date = $result['edited'];
			$product->image_url = $result['image_url'];
			$product->category_id = $result['category_id'];
			$product->product_variant_id = $result['product_variant_id'];
			$this->all_product[] = $product;
		}
		echo '<pre>';print_r($this->all_product);echo '</pre>';
		
		return true; // Match found
		 
	}


	public function retrive_product_by_name($product_title) {
		$sql = "SELECT * FROM {$this->product_table_name} WHERE title='{$product_title}' LIMIT 1";
		if($this->retrive_product($sql)) {
			return true; // Match found
		}
		return false; // Match not found
	}

	public function retrive_product_by_id($product_id) {
		$sql = "SELECT * FROM {$this->product_table_name} WHERE id='{$product_id}' LIMIT 1";
		if($this->retrive_product($sql)) {
			return true; // Match found
		}
		return false; // Match not found
	}

	public function insert_product() {
		$sql = "INSERT INTO $this->product_table_name VALUES (null, $this->title, $this->description, $this->unit_price, $this->sale_price, $this->active_status, $this->created_date, $this->edited_date, $this->category_id, $this->product_variant_id, $this->image_url)";
		$db = new Database();
		if(!$db->connect()) {
			return false; // Database connection error
		}
		$db->set_sql($sql);
		if($db->run_query()) {
			return true; // Inserted
		}
		return false; // Insertion failed
	}

	public function update_product() {
		$sql = "UPDATE $this->product_table_name SET title='{$this->title}', description='{$this->description}', unit_price='{$this->unit_price}', sale_price='{$this->sale_price}', active='{$this->active_status}', created='{$this->created_date}', edited='{$this->edited_date}', category_id='{$this->category_id}', product_variant_id='{$this->product_variant_id}', image_url='{$this->image_url}' WHERE product_id='{$this->product_id}'";
		$db = new Database();
		if(!$db->connect()) {
			return false; // Database connection error
		}
		$db->set_sql($sql);
		if($db->run_query()) {
			return true; // Updated
		}
		return false; // Update failed

	}

	public function delete_product() {
		$sql = "DELETE FROM $this->product_table_name WHERE product_id=$this->product_id";
		$db = new Database();
		if(!$db->connect()) {
			return false; // Database connection error
		}
		$db->set_sql($sql);
		if($db->run_query()) {
			return true; // Deleted
		}
		return false; // Delete failed
	}
}


class All_Product extends Product {
	protected $all_product = array();
	protected $total_product_count;

	public function __construct() {
		$this->total_product = 0;
	}

	public function get_total_product_count() {
		$db = new Database();
		if(!$db->connect()) {return false;} // Connection error
		$db->set_sql("SELECT count(product_id) as total FROM product");
		if(!$db->get_all_rows()) {
			return false; // Wrong query
		}
		$result = array();
		if($db->get_num_rows()) {
			$result = $db->get_result();
			$this->total_product_count = $result['total'];
		}
		return false; // no result found
	}

	public function get_all_product() {
		if(get_total_product_count() === false) {
			return false; // No product
		}
		$all_product = array();
		$product_count = $this->total_product_count;
		while($product_count--) {
			$product = new Product();
		
		}
		

	}
}

?>