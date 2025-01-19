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
                            <li class="breadcrumb-item active">Orders</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- /.card -->
                        @if (Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success:</strong> {{ Session::get('success_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Orders</h3>
                                    </div>
                                </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="cmspages" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Order Date</th>
                                            <th>Cuatomer Name</th>
                                            <th>Customer Email</th>
                                            <th>Ordered Products</th>
                                            <th>Order Amount</th>
                                            <th>Order Status</th>
                                            <th>Payment Method</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>{{ $order['id'] }}</td>
                                                <td>{{ date('F j, Y, g:i a', strtotime($order['created_at'])) }}</td>
                                                <td>{{ $order['user']['name'] }}</td>
                                                <td>{{ $order['user']['email'] }}</td>
                                                <td>
                                                    @foreach($order['orders_products'] as $product)
                                                    {{ $product['product_code'] }}({{$product['product_qty']}})
                                                    @endforeach
                                                </td>
                                                <td>{{ $order['grand_total'] }}</td>
                                                <td>{{ $order['order_status'] }}</td>
                                                <td>{{ $order['payment_method'] }}</td>
                                                <td>
                                                    <a title="View Order Details" href="{{ url('admin/orders/'.$order['id']) }}" style="color: #3f6ed3"><i class="fas fa-file"></i></a>
                                                    @if($order['order_status'] == "Shipped" || $order['order_status'] == "Delivered")
                                                    &nbsp;&nbsp;
                                                    <a target="_blank" title="Print Order Invoice" href="{{ url('admin/print-order-invoice/'.$order['id']) }}" style="color: #3f6ed3"><i class="fas fa-print"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
