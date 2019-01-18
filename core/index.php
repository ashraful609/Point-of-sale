<?php
require_once ('product.php');
$product = new Product();
$product->retrive_product_by_name('Asif');
echo $product->get_title();
?>