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
                    $('.print-success-msg').html("<div class='alert'><span class='closebtn' onclick='this.parentElement.style.display='none';'>&times;</span>"+resp['message']+"</div>");
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

});
