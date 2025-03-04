$(document).ready(function () {
  // $("#sort").on('change', function() {
  //     this.form.submit();
  // });

  $(".getPrice").change(function () {
    var size = $(this).val();
    var product_id = $(this).attr("product-id");
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/get-attribute-price",
      data: {
        size: size,
        product_id: product_id,
      },
      type: "post",
      success: function (resp) {
        // alert(resp);

        if (resp["discount"] > 0) {
          $(".getAttributePrice").html(
            "<span class='pd-detail__price'>Rp." +
              resp["final_price"] +
              "</span>  <span class='pd-detail__discount'>(" +
              resp["discount_percent"] +
              "% OFF)</span><del class='pd-detail__del'>Rp." +
              resp["product_price"] +
              "</del></div>"
          );
        } else {
          $(".getAttributePrice").html(
            "<span class='pd-detail__price'>Rp." + resp["discount"] + "</span>"
          );
        }
      },
      error: function () {
        alert("Error");
      },
    });
  });

  $("#addToCart").submit(function (event) {
    $(".loader").show();
    event.preventDefault(); // Mencegah form untuk reload halaman
    var formData = $(this).serialize();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/add-to-cart",
      type: "post",
      data: formData,
      success: function (resp) {
        $(".loader").hide();
        $(".totalCartItems").html(resp["totalCartItems"]);
        $("#appendCartItems").html(resp.view);
        $("#appendMiniCartItems").html(resp.minicartview);
        if (resp["status"] == true) {
          $(".print-success-msg").show();
          $(".print-success-msg").delay(3000).fadeOut("slow");
          $(".print-success-msg").html(
            "<div class='success'><span class='closebtn' onclick='this.parentElement.style.display='none';'>&times;</span>" +
              resp["message"] +
              "</div>"
          );
        } else {
          $(".loader").hide();
          $(".print-error-msg").show();
          $(".print-error-msg").delay(3000).fadeOut("slow");
          $(".print-error-msg").html(
            "<div class='alert'><span class='closebtn' onclick='this.parentElement.style.display='none';'>&times;</span>" +
              resp["message"] +
              "</div>"
          );
        }
      },
      error: function (xhr, status, error) {
        alert("Error kntl: " + error); // Menampilkan pesan error jika ada masalah di server
      },
    });
  });

  $(document).on("click", ".updateCartItem", function () {
    if ($(this).hasClass("fa-plus")) {
      // get qty
      var quantity = $(this).data("qty");
      // increase the qty by 1
      new_qty = parseInt(quantity) + 1;
    }
    if ($(this).hasClass("fa-minus")) {
      // get qty
      var quantity = $(this).data("qty");
      // check qty is atleast 1
      if (quantity <= 1) {
        alert("Item Quantity must be 1 or greater!");
        return false;
      }
      // increase the qty by 1
      new_qty = parseInt(quantity) - 1;
    }
    var cartid = $(this).data("cartid");
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      data: {
        cartid: cartid,
        qty: new_qty,
      },
      url: "/update-cart-item-qty",
      type: "post",
      success: function (resp) {
        $(".totalCartItems").html(resp.totalCartItems);
        if (resp.status == false) {
          alert(resp.message);
        }
        $("#appendCartItems").html(resp.view);
        $("#appendMiniCartItems").html(resp.minicartview);
      },
      error: function () {
        alert("Error");
      },
    });
  });

  // delete cart item
  $(document).on("click", ".deleteCartItem", function () {
    var cartid = $(this).data("cartid");
    var page = $(this).data('page');
    var result = confirm("Are you sure want to delete this cart item?");
    if (result) {
      $.ajax({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
          cartid: cartid,
        },
        url: "/delete-cart-item",
        type: "post",
        success: function (resp) {
          $(".totalCartItems").html(resp.totalCartItems);
          $("#appendCartItems").html(resp.view);
          $("#appendMiniCartItems").html(resp.minicartview);
          if(page=="Checkout"){
            window.location.href="/checkout";
          }
        },
        error: function () {
          alert("Error");
        },
      });
    }
  });

  // empty cart
  $(document).on("click", ".emptyCart", function () {
    var result = confirm("Are you sure want to empty you Cart?");
    if (result) {
      $.ajax({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/empty-cart",
        type: "post",
        success: function (resp) {
          $(".totalCartItems").html(resp.totalCartItems);
          $("#appendCartItems").html(resp.view);
          $("#appendMiniCartItems").html(resp.minicartview);
        },
        error: function () {
          alert("Error");
        },
      });
    }
  });

  // register form validation
  $("#registerForm").submit(function () {
    $(".loader").show();
    var formData = $("#registerForm").serialize();

    $.ajax({
      url: "/user/register",
      type: "post",
      data: formData,
      success: function (data) {
        if (data.type == "validation") {
          $(".loader").hide();
          $.each(data.errors, function (i, error) {
            $("#register-" + i).attr("style", "color:red");
            $("#register-" + i).html(error);
            setTimeout(function () {
              $("#register-" + i).css({
                display: "none",
              });
            }, 4000);
          });
        } else if (data.type == "success") {
          $(".loader").hide();
          $("#register-success").attr("style", "color:green");
          $("#register-success").html(data.message);
        }
      },
      error: function () {
        alert("Error");
      },
    });
  });

  // login form validation
  $("#loginForm").submit(function () {
    var formData = $(this).serialize();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/user/login",
      type: "post",
      data: formData,
      success: function (resp) {
        if (resp.type == "error") {
          $.each(resp.errors, function (i, error) {
            $(".login-" + i).attr("style", "color:red");
            $(".login-" + i).html(error);
            setTimeout(function () {
              $(".login-" + i).css({
                display: "none",
              });
            }, 4000);
          });
        } else if (resp.type == "inactive") {
          $("#login-error").attr("style", "color:red");
          $("#login-error").html(resp.message);
          $("#login-error").attr("style", "color:red");
          $("#login-error").html(error);
          setTimeout(function () {
            $("#login-error").css({
              display: "none",
            });
          }, 4000);
        } else if (resp.type == "incorrect") {
          $("#login-error").attr("style", "color:red");
          $("#login-error").html(resp.message);
          $("#login-error").attr("style", "color:red");
          $("#login-error").html(error);
          setTimeout(function () {
            $("#login-error").css({
              display: "none",
            });
          }, 4000);
        } else if (resp.type == "success") {
          window.location = resp.redirectUrl;
        }
      },
      error: function () {
        alert("Error");
      },
    });
  });

  // forgot form validation
  $("#forgotForm").submit(function () {
    $(".loader").show();
    var formData = $(this).serialize();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/user/forgot-password",
      type: "post",
      data: formData,
      success: function (resp) {
        $(".loader").hide();
        if (resp.type == "error") {
          $.each(resp.errors, function (i, error) {
            $(".forgot-" + i).attr("style", "color:red");
            $(".forgot-" + i).html(error);
            setTimeout(function () {
              $(".forgot-" + i).css({
                display: "none",
              });
            }, 4000);
          });
        } else if (resp.type == "success") {
          $("#forgot-success").attr("style", "color:green");
          $("#forgot-success").html(resp.message);
        }
      },
      error: function () {
        $(".loader").hide();
        alert("Error");
      },
    });
  });

  // account form validation
  $("#account-success");
  $("#accountForm").submit(function () {
    $(".loader").show();
    var formData = $(this).serialize();

    $.ajax({
      url: "/user/account",
      type: "post",
      data: formData,
      success: function (data) {
        if (data.type == "validation") {
          $(".loader").hide();
          $.each(data.errors, function (i, error) {
            $("#account-" + i).attr("style", "color:red");
            $("#account-" + i).html(error);
            setTimeout(function () {
              $("#account-" + i).css({
                display: "none",
              });
            }, 4000);
          });
        } else if (data.type == "success") {
          $(".loader").hide();
          $("#account-success").attr("style", "color:green");
          $("#account-success").html(data.message);
        }
      },
      error: function () {
        alert("Error");
      },
    });
  });

  $("#resetPwdForm").submit(function () {
    $(".loader").show();
    var formData = $(this).serialize();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/user/reset-password",
      type: "post",
      data: formData,
      success: function (resp) {
        $(".loader").hide();
        if (resp.type == "error") {
          $.each(resp.errors, function (i, error) {
            $(".reset-" + i).attr("style", "color:red");
            $(".reset-" + i).html(error);
            setTimeout(function () {
              $(".reset-" + i).css({
                display: "none",
              });
            }, 4000);
          });
        } else if (resp.type == "success") {
          $("#reset-success").attr("style", "color:green");
          $("#reset-success").html(resp.message);
        }
      },
      error: function () {
        $(".loader").hide();
        alert("Error");
      },
    });
  });

  // Apply Coupon
  $(document).on("click", "#applyCoupon", function () {
    var user = $(this).attr("user");
    if (user == 1) {
    } else {
      alert("Please login to apply Coupon");
      return false;
    }
    var code = $("#code").val();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      type: "post",
      data: { code: code },
      url: "/apply-coupon",
      success: function (resp) {
        if (resp.status == false) {
          // alert(resp.message);
          $(".print-error-msg").show();
          $(".print-error-msg").delay(3000).fadeOut("slow");
          $(".print-error-msg").html(
            "<div class='alert'>" + resp["message"] + "</div>"
          );
        } else if (resp.status == true) {
          // alert(resp.message);
          if (resp.couponAmount > 0) {
            $(".couponAmount").text("Rp." + resp.couponAmount);
          } else {
            $(".couponAmount").text("Rp.0");
          }

          if (resp.grand_total > 0) {
            $(".grandTotal").text("Rp." + resp.grandTotal);
          }
          $(".print-success-msg").show();
          $(".print-success-msg").delay(3000).fadeOut("slow");
          $(".print-success-msg").html(
            "<div class='success'>" + resp["message"] + "</div>"
          );

          $(".totalCartItems").html(resp.totalCartItems);
          $("#appendCartItems").html(resp.view);
          $("#appendMiniCartItems").html(resp.minicartview);
        }
      },
      error: function () {
        alert("Error");
      },
    });
  });

  // user update password validation
  $("#password-success").hide();
  $("#password-error").hide();
  $("#passwordForm").submit(function () {
    $(".loader").show();
    var formData = $(this).serialize();

    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/user/update-password",
      type: "post",
      data: formData,
      success: function (data) {
        if (data.type == "error") {
          $(".loader").hide();
          $("#password-success").hide();
          $.each(data.errors, function (i, error) {
            $("#password-" + i).attr("style", "color:red");
            $("#password-" + i).html(error);
            setTimeout(function () {
              $("#password-" + i).css({
                display: "none",
              });
            }, 4000);
          });
        } else if (data.type == "incorrect") {
          $(".loader").hide();
          $("#password-success").hide();
          $("#password-error").attr("style", "color:red");
          $("#password-error").html(data.message);
        } else if (data.type == "success") {
          $(".loader").hide();
          $("#password-error").hide();
          $("#password-success").attr("style", "color:green");
          $("#password-success").html(data.message);
        }
      },
      error: function () {
        $(".loader").hide();
        alert("Error");
      },
    });
  });

  // save delivery address
  $(document).on("click", "#deliveryForm", function () {
    $(".loader").show();
    var formData = $("#deliveryAddressForm").serialize();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "/save-delivery-address",
      type: "post",
      data: formData,
      success: function (resp) {
        // alert(resp);
        if (resp.type == "error") {
          $(".loader").hide();
          $.each(resp.errors, function (i, error) {
            $("#delivery-" + i).attr("style", "color:red");
            $("#delivery-" + i).html(error);
            setTimeout(function () {
              $("#delivery-" + i).css({
                display: "none",
              });
            }, 3000);
          });
        } else {
          $(".loader").hide();
          $("#deliveryAddressForm").trigger("reset");
          $(".deliveryText").text("ADD NEW DELIVERY ADDRESS");
          $("#deliveryAddresses").html(resp.view);
        }
      },
      error: function () {
        $(".loader").hide();
        alert("Error");
      },
    });
  });

  // edit delivery address
  $(document).on("click", ".editAddress", function () {
    var addressid = $(this).data("addressid");
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      data: { addressid: addressid },
      url: "/get-delivery-address",
      type: "post",
      success: function (resp) {
        // alert(resp);
        $(".deliveryText").text("EDIT DELIVERY ADDRESS");
        $("[name=delivery_id]").val(resp.address["id"]);
        $("[name=delivery_name]").val(resp.address["name"]);
        $("[name=delivery_address]").val(resp.address["address"]);
        $("[name=delivery_city]").val(resp.address["city"]);
        $("[name=delivery_state]").val(resp.address["state"]);
        $("[name=delivery_country]").val(resp.address["country"]);
        $("[name=delivery_pincode]").val(resp.address["pincode"]);
        $("[name=delivery_mobile]").val(resp.address["mobile"]);
      },
      error: function () {
        alert("Error");
      },
    });
  });

  // delete delivery address
  $(document).on("click", ".deleteAddress", function () {
    if (confirm("Are you sure to remove this Address?")) {
      var addressid = $(this).data("addressid");
      $.ajax({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: { addressid: addressid },
        url: "/remove-delivery-address",
        type: "post",
        success: function (resp) {
          // alert(resp);
          $("#deliveryAddresses").html(resp.view);
        },
        error: function () {
          alert("Error");
        },
      });
    }
  });
});
