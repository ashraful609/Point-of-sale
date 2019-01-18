<?php
function print_var($var) {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function echo_var(&$var) {
	if(isset($var)) {
		echo $var . '<br>';
	}
}
 ?>
