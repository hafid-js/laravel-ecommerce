$(document).ready(function () {
    $("#current_pwd").keyup(function () {
        var current_pwd = $("#current_pwd").val();
        // alert(current_pwd);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/check-current-password",
            data: { current_pwd: current_pwd },
            success: function (resp) {
                if (resp == "false") {
                    $("#verifyCurrentPwd").html(
                        "Current Password is Incorrect!"
                    );
                } else if (resp == "true") {
                    $("#verifyCurrentPwd").html("Current Password is Correct!");
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    $(document).on("click", ".updateCmsPageStatus", function () {
        var status = $(this).children("i").attr("status");
        var page_id = $(this).attr("page_id");
        // alert(page_id);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-cms-page-status",
            data: {
                status: status,
                page_id: page_id,
            },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#page-" + page_id).html(
                        "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#page-" + page_id).html(
                        "<i class='fas fa-toggle-on' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    $(document).on("click", ".updateCategoryStatus", function () {
        var status = $(this).children("i").attr("status");
        var category_id = $(this).attr("category_id");
        // alert(category_id);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-category-status",
            data: {
                status: status,
                category_id: category_id,
            },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#category-" + category_id).html(
                        "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#category-" + category_id).html(
                        "<i class='fas fa-toggle-on' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    // update product status
    $(document).on("click", ".updateProductStatus", function () {
        var status = $(this).children("i").attr("status");
        var product_id = $(this).attr("product_id");
        // alert(product_id);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-product-status",
            data: {
                status: status,
                product_id: product_id,
            },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#product-" + product_id).html(
                        "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#product-" + product_id).html(
                        "<i class='fas fa-toggle-on' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

 // update attribute status
 $(document).on("click", ".updateAttributeStatus", function () {
    var status = $(this).children("i").attr("status");
    var attribute_id = $(this).attr("attribute_id");
    // alert(attribute_id);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "post",
        url: "/admin/update-attribute-status",
        data: {
            status: status,
            attribute_id: attribute_id,
        },
        success: function (resp) {
            if (resp["status"] == 0) {
                $("#attribute-" + attribute_id).html(
                    "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                );
            } else if (resp["status"] == 1) {
                $("#attribute-" + attribute_id).html(
                    "<i class='fas fa-toggle-on' status='Active'></i>"
                );
            }
        },
        error: function () {
            alert("Error");
        },
    });
});

 // update brand status
 $(document).on("click", ".updateBrandStatus", function () {
    var status = $(this).children("i").attr("status");
    var brand_id = $(this).attr("brand_id");
    // alert(brand_id);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "post",
        url: "/admin/update-brand-status",
        data: {
            status: status,
            brand_id: brand_id,
        },
        success: function (resp) {
            if (resp["status"] == 0) {
                $("#brand-" + brand_id).html(
                    "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                );
            } else if (resp["status"] == 1) {
                $("#brand-" + brand_id).html(
                    "<i class='fas fa-toggle-on' status='Active'></i>"
                );
            }
        },
        error: function () {
            alert("Error");
        },
    });
});

// update coupon status
$(document).on("click", ".updateCouponStatus", function () {
    var status = $(this).children("i").attr("status");
    var coupon_id = $(this).attr("coupon_id");
    // alert(coupon_id);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "post",
        url: "/admin/update-coupon-status",
        data: {
            status: status,
            coupon_id: coupon_id,
        },
        success: function (resp) {
            if (resp["status"] == 0) {
                $("#coupon-" + coupon_id).html(
                    "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                );
            } else if (resp["status"] == 1) {
                $("#coupon-" + coupon_id).html(
                    "<i class='fas fa-toggle-on' status='Active'></i>"
                );
            }
        },
        error: function () {
            alert("Error");
        },
    });
});


 // update banner status
 $(document).on("click", ".updateBannerStatus", function () {
    var status = $(this).children("i").attr("status");
    var banner_id = $(this).attr("banner_id");
    // alert(banner_id);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "post",
        url: "/admin/update-banner-status",
        data: {
            status: status,
            banner_id: banner_id,
        },
        success: function (resp) {
            if (resp["status"] == 0) {
                $("#banner-" + banner_id).html(
                    "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                );
            } else if (resp["status"] == 1) {
                $("#banner-" + banner_id).html(
                    "<i class='fas fa-toggle-on' status='Active'></i>"
                );
            }
        },
        error: function () {
            alert("Error");
        },
    });
});


// update user status
$(document).on("click", ".updateUserStatus", function () {
    var status = $(this).children("i").attr("status");
    var user_id = $(this).attr("user_id");
    // alert(user_id);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "post",
        url: "/admin/update-user-status",
        data: {
            status: status,
            user_id: user_id,
        },
        success: function (resp) {
            if (resp["status"] == 0) {
                $("#user-" + user_id).html(
                    "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                );
            } else if (resp["status"] == 1) {
                $("#user-" + user_id).html(
                    "<i class='fas fa-toggle-on' status='Active'></i>"
                );
            }
        },
        error: function () {
            alert("Error");
        },
    });
});

    // $(document).on("click", ".confirmDelete", function () {

    //     var name = $(this).attr("name");
    //     if (confirm("Are you sure to delete this " + name + "?")) {
    //         return true;
    //     }
    //     return false;
    // });

    $(document).on("click", ".confirmDelete", function () {
        var record = $(this).attr("record");
        var recordid = $(this).attr("recordid");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success",
                })
                window.location.href = "/admin/delete-"+record+"/"+recordid;
            }
        });
    });

    // update subadmin

    $(document).on("click", ".updateSubadminStatus", function () {
        var status = $(this).children("i").attr("status");
        var subadmin_id = $(this).attr("subadmin_id");
        // alert(subadmin_id);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-subadmin-status",
            data: {
                status: status,
                subadmin_id: subadmin_id,
            },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#subadmin-" + subadmin_id).html(
                        "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#subadmin-" + subadmin_id).html(
                        "<i class='fas fa-toggle-on' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    // update shipping status
    $(document).on("click", ".updateShippingStatus", function () {
        var status = $(this).children("i").attr("status");
        var shipping_id = $(this).attr("shipping_id");
        // alert(shipping_id);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-shipping-status",
            data: {
                status: status,
                shipping_id: shipping_id,
            },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("#shipping-" + shipping_id).html(
                        "<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("#shipping-" + shipping_id).html(
                        "<i class='fas fa-toggle-on' status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });


    // Add Product Attribute Script
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div> <input type="text" name="size[]" placeholder="Size" style="width:120px;"/>&nbsp;<input type="text" name="sku[]" placeholder="SKU" style="width:120px;"/>&nbsp;<input type="text" name="price[]" placeholder="Price" style="width:120px;"/>&nbsp;<input type="text" name="stock[]" placeholder="Stock" style="width:120px;"/><a href="javascript:void(0);" class="remove_button">Remove</a></div>'; //New input field html
    var x = 1; //Initial field counter is 1

    // Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){
            x++; //Increase field counter
            $(wrapper).append(fieldHTML); //Add field html
        }else{
            alert('A maximum of '+maxField+' fields are allowed to be added. ');
        }
    });

    // Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrease field counter
    });

    // show/hide coupon field for manual/automatic
    $("#ManualCoupon").click(function() {
        $("#couponField").show();
    });

     $("#AutomaticCoupon").click(function() {
        $("#couponField").hide();
    });

    // show courier name and tracking number in case of shipped order status
    $("#courier_name").hide();
    $("#tracking_number").hide();
    $("#order_status").on("change", function() {
        if(this.value=="Shipped"){
            $("#courier_name").show();
            $("#tracking_number").show();
        } else {
            $("#courier_name").hide();
            $("#tracking_number").hide();
        }
    });

});
