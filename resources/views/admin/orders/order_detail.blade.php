<?php use App\Models\Product; ?>
@extends('admin.layout.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Orders</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Order #{{$orderDetails['id']}} Detail</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order Details</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td>Order ID</td>
                      <td>{{$orderDetails['id']}}</td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td>{{$orderDetails['order_status']}}</td>
                    </tr>
                    <tr>
                      <td>Grand Total</td>
                      <td>Rp.{{$orderDetails['grand_total']}}</td>
                    </tr>
                    <tr>
                      <td>Shipping Charges</td>
                      <td>{{$orderDetails['shipping_charges']}}</td>
                    </tr>
                    <tr>
                      <td>Coupon Code</td>
                      <td>{{$orderDetails['coupon_code']}}</td>
                    </tr>
                    <tr>
                      <td>Coupon Amount</td>
                      <td>{{$orderDetails['coupon_amount']}}</td>
                    </tr>
                    <tr>
                      <td>Payment Method</td>
                      <td>{{$orderDetails['payment_method']}}</td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Customer Details</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td>Name</td>
                      <td>{{$orderDetails['user']['name']}}</td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td>{{$orderDetails['user']['email']}}</td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
        </div>
        <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Billing Address</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td>Name</td>
                        <td>{{$orderDetails['user']['name']}}</td>
                      </tr>
                      <tr>
                        <td>Address</td>
                        <td>{{$orderDetails['user']['address']}}</td>
                      </tr>
                      <tr>
                        <td>City</td>
                        <td>{{$orderDetails['user']['city']}}</td>
                      </tr>
                      <tr>
                        <td>State</td>
                        <td>{{$orderDetails['user']['state']}}</td>
                      </tr>
                      <tr>
                        <td>Country</td>
                        <td>{{$orderDetails['user']['country']}}</td>
                      </tr>
                      <tr>
                        <td>Pincode</td>
                        <td>{{$orderDetails['user']['pincode']}}</td>
                      </tr>
                      <tr>
                        <td>Mobile</td>
                        <td>{{$orderDetails['user']['mobile']}}</td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card -->
              <!-- /.card -->
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Delivery Address</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{$orderDetails['name']}}</td>
                          </tr>
                          <tr>
                            <td>Address</td>
                            <td>{{$orderDetails['address']}}</td>
                          </tr>
                          <tr>
                            <td>City</td>
                            <td>{{$orderDetails['city']}}</td>
                          </tr>
                          <tr>
                            <td>State</td>
                            <td>{{$orderDetails['state']}}</td>
                          </tr>
                          <tr>
                            <td>Country</td>
                            <td>{{$orderDetails['country']}}</td>
                          </tr>
                          <tr>
                            <td>Pincode</td>
                            <td>{{$orderDetails['pincode']}}</td>
                          </tr>
                          <tr>
                            <td>Mobile</td>
                            <td>{{$orderDetails['mobile']}}</td>
                          </tr>

                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card -->
              <!-- /.card -->
            </div>
            <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Update Order Status</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <tbody>
                         <td>Select Status</td>
                         <td><button>Update</button></td>

                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.card -->
                <!-- /.card -->
              </div>
          </div>
        <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Ordered Products</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Product Image</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Product Size</th>
                        <th>Product Color</th>
                        <th>Product Qty</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($orderDetails['orders_products'] as $product)
                        @php $getProductImage = Product::getProductImage($product['product_id']) @endphp
                      <tr>
                        <td>
                            @if($getProductImage != "")
                            <a target="_blank" href="{{ url('product/'.$product['product_id']) }}">
                                <img style="width:80px;" src="{{ url('front/images/products/small/' .$getProductImage) }}" alt="">
                            </a>
                            @else
                            <a target="_blank" href="{{ url('product/'.$product['product_id']) }}">
                            <img style="width:80px;" src="{{ asset('front/images/product/sitemakers-tshirt.png') }}" alt="">
                            </a>
                            @endif
                        </td>
                        <td>{{ $product['product_code'] }}</td>
                        <td>{{ $product['product_name'] }}</td>
                        <td>{{ $product['product_size'] }}</td>
                        <td>{{ $product['product_color'] }}</td>
                        <td>{{ $product['product_qty'] }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
        <!-- /.content -->
    </div>
@endsection
