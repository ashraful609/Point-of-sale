<?php 
require_once('includes/header.php');
?>
<div class="container">
	<!--Make sure the form has the autocomplete function switched off:-->
	<form name="frm_search_product" autocomplete="off">
		<div class="autocomplete input-group">
			<input type="text" class="form-control" id="product_search_bar" value="" data-product-id="">
			<div class="input-group-btn">
				<button class="btn btn-default" type="submit">
					<i class="fas fa-plus-circle"></i>
					Add to Order
				</button>
			</div>
		</div>
	</form>
	<p id="test"></p>
</div>


<div class="container mt-5">
	<div class="row">
		<div id="product_cart" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h2>Sale Cart</h2>
			<p class="text-info" id="error_status">adding product for sale</p>	
			<div id="product_cart">
				<div class="table-responsive">
					<form id="cart_data" onsubmit="return print_memo()">
						<table class="table table-striped table-bordered table-hover" id="cart">
							<thead>
								<tr>
									<td>Total</td>
									<td id="total_amount"></td>
									<td>Discount</td>
									<td id="discount_amount"></td>
									<td>Net payable</td>
									<td id="net_amount" colspan="2"></td>
								</tr>
								<tr>
									<td>Discount</td>
									<td><div class="input-group"><input type="text" name="total_discount" value="0"></div></td>
									<td>Paid Amount</td>
									<td><div class="input-group"><input type="number" name="paid_amount" value="0"></div></td>
									<td>Change Amount</td>
									<td id="change_amount" colspan="2"></td>
								</tr>
								<tr>
									<td colspan="7">
										<button type="submit" class="btn btn-primary mr-3"><i class="fas fa-print"></i> Print</button>

										<button type="button" class="btn btn-secondary mr-3" onclick="cancel_cart()"><i class="fas fa-ban"></i> Cancel</button>
									</td>
								</tr>
								<tr>
									<td>SL#</td>
									<td>Product</td>
									<td>Price</td>
									<td>Qty.</td>
									<td>Discount</td>
									<td>Total</td>
									<td>Action</td>
								</tr>
							</thead>
							<tbody id="cart_product">
								<!-- ADD PRODUCT TO THE CART DYNAMICALLY -->
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="cash_memo" class="d-none">
	<h2>Nobabee Style</h2>
	<p>Address</p>
	<table>

	</table>
</div>


<?php require_once('includes/footer.php'); ?>
<script>
	$('#product_search_bar').scannerDetection({
		timeBeforeScanTest: 200, // wait for the next character for upto 200ms
		startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
		endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
		avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
		onComplete: function(barcode, qty){ 
			// console.log(barcode);
			barcode = str_to_int(barcode) - 100000;
			$('#product_search_bar').attr('data-product-id', barcode);
			$('form[name=frm_search_product]').submit();
		} // main callback function	
	});
	$('[name=total_discount]').on('keyup keypress blur change', function(e) {
		give_total_discount(this);
	});
	$('[name=paid_amount]').on('keyup keypress blur change', function(e) {
		change_amount_cal(this);
	});

	var search_key = [];
	var detail = [];
	var cart_product = [];
	var net_amount = 0;
	var discount_amount = 0;
	var total_discount = 0;
	var total_amount = 0;
	var paid = 0;
	var typed_into=false;
	$.ajax({
		url: 'fetch_product_for_search.php',
		success: function(data) {
			var result = JSON.parse(data);
			for(var x in result) {
				search_key.push(result[x].title);
				detail.push(result[x]);
			}
		}
	}); 
	autocomplete(document.getElementById("product_search_bar"), search_key, detail);


	function cancel_cart() {
		location.reload();
	}

	function change_amount_cal(e) {
		paid = str_to_int($(e).val());
		var change_amount = paid - net_amount;
		$('#change_amount').text(change_amount);
	}

	function print_memo() {
		var cash_memo = $('#cash_memo');
		var cart_data = {};
		var product = {};
		var form = $('form#cart_data');
		$.ajax({
			url: 'add_to_cart.php',
			method: 'POST',
			data: form.serialize(),
			dataType: 'json',
			success: function(data) {
				console.log(data);
				if('order_id' in data) {

                	$('#error_status').text('Order placed successfully.');
                	$('#error_status').removeClass().addClass('text-success');
                	$('#cart tbody').remove();
                	do_print(data.order_id, data.paid);
                	cancel_cart();
				}else{
					$('#error_status').text(data.msg);
                	$('#error_status').removeClass().addClass('text-danger');					
				}
			}
		});
		return false;
	}

	function do_print(id, paid) {
		window.open('print_memo.php?id='+id+'&paid='+paid);
	}

	function str_to_int(s) {
		s = parseInt(s);
		return (isNaN(s)) ? 0 : s; 
	}

	function remove_cart_product(e){
		var idx = e.name.match(/\[(\d+)\]/)[1];
		var qty = cart_product[idx-1].order_quantity;
		var price = cart_product[idx-1].sale_price;
		var discount = cart_product[idx-1].discount;
		var sub_total_amount = qty * price - discount;
		update_total_amount(-sub_total_amount);
		update_total_discount(-discount);
		update_netpayable();
		delete cart_product[idx-1];
		e.closest('tr').remove();
	}

	function update_cart_quantity(e) {
		var cart_qty = str_to_int($(e).val());
		var idx = e.name.match(/\[(\d+)\]/)[1];
		var prev_subtotal = get_subtotal_amount(idx);
		cart_product[idx-1].order_quantity = cart_qty;
		var subtotal = get_subtotal_amount(idx);
		// console.log($(e).closest('td').siblings('.subtotal').text());
		var cart_subtotal_field = $(e).closest('td').siblings('.subtotal');
		cart_subtotal_field.text(subtotal);
		update_total_amount(-prev_subtotal);
		update_total_amount(subtotal);
		update_netpayable();
	}

	function update_cart_discount(e) {
		
		var idx = e.name.match(/\[(\d+)\]/)[1];
		temp_discount=$(e).val();
		if(temp_discount.indexOf('%')>-1){
			temp_discount=temp_discount.substr(0,temp_discount.length-1);
			temp_discount=cart_product[idx-1].sale_price*(str_to_int(temp_discount)/100);
			temp_discount *= cart_product[idx-1].order_quantity;
		}
		
		var cart_discount = temp_discount;
		var prev_cart_discount = str_to_int(cart_product[idx-1].discount);
		var prev_subtotal = get_subtotal_amount(idx);
		cart_product[idx-1].discount=cart_discount;
		var subtotal = get_subtotal_amount(idx);
		update_total_discount(-prev_cart_discount);
		update_total_amount(-prev_subtotal);
		update_total_discount(cart_discount);
		update_total_amount(subtotal);
		var cart_subtotal_field = $(e).closest('td').siblings('.subtotal');
		cart_subtotal_field.text(subtotal);
		update_netpayable();
	}

	function update_total_amount(e) {
		var total_amount_field = $('#total_amount');
		total_amount = str_to_int(total_amount_field.text());
		total_amount += e;
		total_amount_field.text(total_amount);
	}

	function update_netpayable() {
		var netpayable_field = $('#net_amount');
		net_amount = str_to_int(netpayable_field.text());
		net_amount = total_amount - total_discount;
		netpayable_field.text(net_amount);
	}

	function give_total_discount(e) {		
		var prev_total_discount = total_discount;
		var temp_discount = $(e).val();
		if(temp_discount.indexOf('%')>-1){
			temp_discount=temp_discount.substr(0,temp_discount.length-1);
			temp_discount=total_amount*(str_to_int(temp_discount)/100);
				
		}
		total_discount = temp_discount;
		update_total_discount(-prev_total_discount);
		update_total_discount(total_discount);
		update_netpayable();
	}

	function update_total_discount(e) {
		var total_discount_field = $('#discount_amount');
		discount_amount = str_to_int(total_discount_field.text());
		discount_amount += str_to_int(e);
		total_discount_field.text(discount_amount);
	}

	function get_subtotal_amount(idx) {
		var qty = cart_product[idx-1].order_quantity;
		var price = cart_product[idx-1].sale_price;
		var discount = cart_product[idx-1].discount;
		return qty * price - discount;
	}
</script>
