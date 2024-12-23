$(document).ready(function(){
    // $("#sort").on('change', function() {
    //     this.form.submit();
    // });

    $(".getPrice").change(function() {
        var size = $(this).val();
        var product_id = $(this).attr("product-id");
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: '/get-attribute-price',
            data: {
                size : size,
                product_id : product_id
            },
            type: 'post',
            success: function(resp){
                // alert(resp);

                if(resp['discount'] > 0){
                    $(".getAttributePrice").html("<span class='pd-detail__price'>Rp."+resp['final_price']+"</span>  <span class='pd-detail__discount'>("+resp['discount_percent']+"% OFF)</span><del class='pd-detail__del'>Rp."+resp['product_price']+"</del></div>")
                } else {
                    $(".getAttributePrice").html("<span class='pd-detail__price'>Rp."+resp['discount']+"</span>");
                }
            },error:function() {
                alert("Error");
            }
        })
    })

    $("#addToCart").submit(function(event){
        event.preventDefault(); // Mencegah form untuk reload halaman
        var formData = $(this).serialize();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url:'/add-to-cart',
            type:'post',
            data:formData,
            success:function(resp){
                if(resp['status'] == true) {
                    $('.print-success-msg').show();
                    $('.print-success-msg').delay(3000).fadeOut('slow');
                    $('.print-success-msg').html("<div class='success'><span class='closebtn' onclick='this.parentElement.style.display='none';'>&times;</span>"+resp['message']+"</div>");
                } else {
                    $('.print-error-msg').show();
                    $('.print-error-msg').delay(3000).fadeOut('slow');
                    $('.print-error-msg').html("<div class='alert'><span class='closebtn' onclick='this.parentElement.style.display='none';'>&times;</span>"+resp['message']+"</div>");
                }
            },
            error: function(xhr, status, error) {
                alert("Error kntl: " + error); // Menampilkan pesan error jika ada masalah di server
            }
        });
    });

    $(document).on('click','.updateCartItem', function(){
        if($(this).hasClass('fa-plus')){
            // get qty
            var quantity = $(this).data('qty');
            // increase the qty by 1
            new_qty = parseInt(quantity) + 1;
        }
        if($(this).hasClass('fa-minus')){
            // get qty
            var quantity = $(this).data('qty');
            // check qty is atleast 1
            if(quantity <= 1) {
                alert("Item Quantity must be 1 or greater!");
                return false;
            }
            // increase the qty by 1
            new_qty = parseInt(quantity) - 1;
        }
        var cartid = $(this).data('cartid');
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                cartid : cartid,
                qty : new_qty
            },
            url:'/update-cart-item-qty',
            type:'post',
            success:function(resp){
                if(resp.status == false) {
                    alert(resp.message)
                }
                $("#appendCartItems").html(resp.view);
            }, error: function(){
                alert("Error");
            }
        });
    });

    // delete cart item
    $(document).on('click', '.deleteCartItem', function() {
        var cartid = $(this).data('cartid');
        var result = confirm("Are you sure want to delete this cart item?");
        if(result) {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    cartid:cartid
                },
                url:'/delete-cart-item',
                type:'post',
                success:function(resp){
                    $("#appendCartItems").html(resp.view);
                },
                error: function() {
                    alert("Error");
                }
            });
        }
    })

});
