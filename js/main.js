$(document).ready(function () {
    // AUTOCOMPLETE
    $('form[name=frm_search_product]').submit(function(event) {
        event.preventDefault();
        // $('#product_cart').removeClass('d-none');
        var ip_product = $('#product_search_bar');
        var product_title = ip_product.val();
        var product_id = ip_product.attr('data-product-id');
        $.ajax({
        url: 'fetch_product_on_order.php',
        method: 'POST',
        data: {product_id:product_id},
        dataType: 'json',
        success: function(data) {
                var product = data[0];
                var cart_row = $('#cart_product');
                var cart_sl = parseInt($('#cart tbody tr:last td:first').text());
                if(isNaN(cart_sl)) {cart_sl=1;}else{cart_sl += 1;}
                var p = '<tr>';
                p += '<td><input type="hidden" name="product['+cart_sl+'][id]"value="'+ product.product_id +'">' + cart_sl + '</td>';
                p += '<td><strong>'+ product.title +'</strong><br><span class="small">'+ product.category_name +'<br>Color: '+ product.color +' | Size: '+ product.size +' | Avl: '+product.quantity+' </span></td>';
                p += '<td>' + product.sale_price + '</td>';
                p += '<td><div class="input-group"><input type="number" name="product['+cart_sl+'][qty]" class="form-control" onchange="update_cart_quantity(this)"  value="1"></div></td>';
                p += '<td><div class="input-group"><input type="text" name="product['+cart_sl+'][discount]" class="form-control" onchange="update_cart_discount(this)" value="0"></div></td>';
                p += '<td class="subtotal">'+ product.sale_price +'</td>';
                p += '<td><button type="button" class="btn btn-primary" name="product['+cart_sl+']" onclick="remove_cart_product(this)"><i class="fas fa-trash"></i></button></td>';
                p += '</tr>';
                cart_row.append(p);
                ip_product.val('');
                product.order_quantity = 1;
                product.discount = 0;
                var sub_total_amount = product.order_quantity*product.sale_price-product.discount;
                update_total_amount(sub_total_amount);
                update_total_discount(product.discount);
                update_netpayable();
                cart_product.push(product);
            }
               
        });
    });

    // DYNAMICALLY ADD VARIANT
    $('#product_variant_adder').on('click', function () {
        $('#product_variant_section').append(product_variant_section_html);
    });

    //SELL LEDGER
    $('#update_log').on('click', function(event) {
            event.preventDefault();
            var from =$("#from").val();
            var to = $("#to").val();

            $.ajax({
                url: "show_sell_ledger.php",
                method: "POST",
                data: {from:from,to:to},
                dataType: "html",
                success: function(data) {
                    $("#sell_ledger").html(data);
                }
            });
        });


    //reports
    $('#update_report').on('click', function(event) {
        event.preventDefault();
        var from1 =$("#from").val();
        var to1 = $("#to").val();

        $.ajax({
            url: "report_update.php",
            method: "POST",
            data: {from:from1,to:to1},
            dataType: "html",
            success: function(data) {
                console.log(data);
                $("#view_report").html(data);
            }
        });
    });


    /*
        EDIT CATEGORY MODAL
        
    */
    $('#editCategoryModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('category-id'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        $.ajax({
            url: "fetch_category_update_details.php",
            method: "POST",
            data: {category_id:recipient},
            dataType: "json",
            success: function(data) {
                var c = data[0];
                modal.find('#category_name').val(c.category_name);
                modal.find('#description').val(c.description);
                modal.find('#category_id').val(c.category_id);
            }
        });


        var update_category_button = modal.find('#update_category_button');
        $(update_category_button).on('click', function(event) {
            event.preventDefault();
            var category_id = modal.find('#category_id').val();
            var category_name = modal.find('#category_name').val();
            var description = modal.find('#description').val();

            $.ajax({
                url: "update_category.php",
                method: "POST",
                data: {category_id:category_id,category_name:category_name,description:description},
                dataType: "html",
                success: function(data) {
                    $('#msg').text(data);
                    $("#editCategoryModal").modal("hide");
                    $("#category_view_table").load('show_all_category.php #category_view_table');
                }
            });
        });
    });

    /*
        EDIT PRODUCT MODAL
        
    */
    $('#editProductModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('product-id'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        $.ajax({
            url: "fetch_product_update_details.php",
            method: "POST",
            data: {product_id:recipient},
            dataType: "json",
            success: function(data) {
                var p = data[0];
                if(p.color == 'null') {p.color = 'None';}
                if(p.size == 'null') {p.size = 'None';}
                modal.find('#product_id').val(p.product_id);
                modal.find('#product_title').val(p.title);
                modal.find('#product_unit_price').val(p.unit_price);
                modal.find('#product_sell_price').val(p.sale_price);
                modal.find('#product_quantity').val(p.quantity);
                modal.find('#product_description').val(p.description);
                modal.find('#product_category option[value='+p.category_id+']').attr('selected', 'selected');
                modal.find('#product_color').val(p.color);
                modal.find('#product_size').val(p.size);
            }
        });


        // UPDATE PRODUCT
        var update_button = modal.find('#update_product_button');
        $(update_button).on('click', function(event) {
            event.preventDefault();
            var id = modal.find('#product_id').val();
            var title = modal.find('#product_title').val();
            var buy_price = modal.find('#product_unit_price').val();
            var sale_price = modal.find('#product_sell_price').val();
            var quantity = modal.find('#product_quantity').val();
            var description = modal.find('#product_description').val();
            var category_id = modal.find('#product_category').val();
            var color = modal.find('#product_color').val();
            var size = modal.find('#product_size').val();
            var update_product_form_data = {
                id:id,
                title:title,
                buy_price:buy_price,
                sale_price:sale_price,
                quantity:quantity,
                description:description,
                category_id:category_id,
                color:color,
                size:size
            };

            $.ajax({
                url: 'update_product.php',
                method: 'POST',
                data: update_product_form_data,
                dataType: 'html',
                success: function(data) {
                    if(data == 2) {
                        $('#error_status').html('Product <strong>'+title+'</strong> has been updated.');
                        $('#error_status').removeClass().addClass('text-success');
                        $('#editProductModal').modal('hide');
                        $('#product_view_table').load('view_product.php #product_view_table');
                    }else{
                        $('#error_status').text('Product <strong>'+title+'</strong> update failed.');
                        $('#error_status').removeClass().addClass('text-danger');
                        $('#editProductModal').modal('hide');
                    }
                }
            });
        });
    });

    /*
        EDIT USER MODAL

    */
    $('#editUserModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('user-id'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        $.ajax({
            url: "fetch_user_update_details.php",
            method: "POST",
            data: {user_id:recipient},
            dataType: "json",
            success: function(data) {
                var u = data[0];
                modal.find('#user_id').val(u.user_id);
                modal.find('#firstname').val(u.first_name);
                modal.find('#lastname').val(u.last_name);
                modal.find('#email').val(u.email);
                modal.find('#phone').val(u.phone);
                modal.find('#address').val(u.address);
            }
        });


        // UPDATE PRODUCT
        var update_button = modal.find('#update_user_button');
        $(update_button).on('click', function(event) {
            event.preventDefault();
            var id = modal.find('#user_id').val();
            var firstname = modal.find('#firstname').val();
            var lastname = modal.find('#lastname').val();
            var email = modal.find('#email').val();
            var phone = modal.find('#phone').val();
            var address = modal.find('#address').val();
            var update_user_form_data = {
            	id:id,
            	firstname:firstname,
            	lastname:lastname,
            	email:email,
            	phone:phone,
            	address:address
            };

            $.ajax({
                url: 'update_user.php',
                method: 'POST',
                data: update_user_form_data,
                dataType: 'html',
                success: function(data) {
                    if(data == 'user_updated') {
                        $('#error_status').html('User  has been updated.');
                        $('#error_status').removeClass('text-info').addClass('text-success');
                        $('#editUserModal').modal('hide');
                        $('#user_view_table').load('view_user.php #user_view_table');
                    }else{
                        $('#error_status').text('User  update failed.');
                        $('#error_status').removeClass('text-info').addClass('text-danger');
                        $('#editUserModal').modal('hide');
                    }
                }
            });
        });
    });


    /*
        DELETE USER MODAL

    */
    $('#deleteUserModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var recipient = button.data('user-id');
        var delete_button = modal.find('#delete_user_button');
        $(delete_button).on('click', function (event) {
            $.ajax({
                url: 'delete_user.php',
                method: 'POST',
                data: {user_id:recipient},
                dataType: 'html',
                success: function(data) {
                    if(data == "user_removed") {
                        $('#error_status').html('User has been removed.');
                        $('#error_status').removeClass('text-info').addClass('text-success');
                        $('#deleteUserModal').modal('hide');
                        $('#user_view_table').load('view_user.php #user_view_table');
                    }else{
                        $('#error_status').html('User remove failed.');
                        $('#deleteUserModal').modal('hide');
                        $('#error_status').removeClass('text-info').addClass('text-danger');
                    }
                }
            });
        });
    });

    /*
        DELETE PRODUCT MODAL

    */
    $('#deleteProductModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var recipient = button.data('product-id');
        var delete_button = modal.find('#delete_product_button');
        $(delete_button).on('click', function (event) {
            $.ajax({
                url: 'delete_product.php',
                method: 'POST',
                data: {product_id:recipient},
                dataType: 'html',
                success: function(data) {
                    if(data == "product_removed") {
                        $('#error_status').html('Product has been removed.');
                        $('#error_status').removeClass('text-info').addClass('text-success');
                        $('#deleteProductModal').modal('hide');
                        $('#product_view_table').load('view_product.php #product_view_table');
                    }else{
                        $('#error_status').html('Product remove failed.');
                        $('#deleteProductModal').modal('hide');
                        $('#error_status').removeClass('text-info').addClass('text-danger');
                    }
                }
            });
        });
    });

    /*
        GET BARCODE MODAL

    */
    $('#getBarcodeModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var button = $(event.relatedTarget);
        var recipient = button.data('product-id');
        var save_button = modal.find('#get_barcode_btn');
        JsBarcode('#barcode', Math.floor(100000)+recipient, {format: "CODE128",displayValue:true,fontSize:20,height:25});
        $(save_button).on('click', function (event) {
            var img = $('#barcode')[0].toDataURL('image/png');
            document.write('<a href="'+img+'" download="product_barcode_'+recipient+'"><img src="'+img+'"/></a>');
            
        });
    });
    
});

function remove_variant(e) {
    e.closest(".row").remove();
}

function remove_table_row(e) {
    e.closest('tr').remove();
}


