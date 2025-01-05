@extends('admin.layout.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Coupons</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Coupons</li>
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
                            @if ($couponsModule['edit_access'] == 1 || $couponsModule['full_access'] == 1)
                                <div class="card-header">
                                    <h3 class="card-title">Coupons</h3>
                                    <a href="{{ url('admin/add-edit-coupon') }}"
                                        style="max-width: 150px; float: right; display: inline-block;"
                                        class="btn btn-block btn-primary"><i class="fas fa-plus"></i> Add Coupons</a>
                                </div>
                            @endif

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="cmspages" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Code</th>
                                            <th>Coupon Type</th>
                                            <th>Amount</th>
                                            <th>Expiry Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <td>{{ $coupon['id'] }}</td>
                                                <td>{{ $coupon['coupon_code'] }}</td>
                                                <td>{{ $coupon['coupon_type'] }}</td>
                                                <td>{{ $coupon['amount'] }}
                                                    @if($coupon['amount'] == "Percentage") % @else INR @endif
                                                </td>
                                                <td>{{ date('F j, Y, g:i a', strtotime($coupon['expiry_date'])) }}</td>
                                                <td>
                                                    @if ($couponsModule['edit_access'] == 1 || $couponsModule['full_access'] == 1)
                                                        @if ($coupon['status'] == 1)
                                                            <a class="updateCouponStatus" id="coupon-{{ $coupon['id'] }}"
                                                                coupon_id={{ $coupon['id'] }} href="javascript:void(0)"><i
                                                                    class="fas fa-toggle-on" status="Active"></i></a>
                                                        @else
                                                            <a class="updateCouponsStatus" id="coupon-{{ $coupon['id'] }}"
                                                                coupon_id={{ $coupon['id'] }} style="color: grey"
                                                                href="javascript:void(0)"><i class="fas fa-toggle-off"
                                                                    status="Inactive"></i></a>
                                                        @endif
                                                    @endif
                                                    @if ($couponsModule['edit_access'] == 1 || $couponsModule['full_access'] == 1)
                                                        &nbsp; &nbsp;
                                                        <a href="{{ url('admin/add-edit-coupon/' . $coupon['id']) }}"><i
                                                                class="fas fa-edit"></i></a>
                                                        &nbsp; &nbsp;
                                                        @if ($couponsModule['full_access'] == 1)
                                                            <a class="confirmDelete" title="Delete Coupons"
                                                                href="javascript:void(0)" record="coupon"
                                                                recordid="{{ $coupon['id'] }}"><i
                                                                    class="fas fa-trash"></i></a>
                                                        @endif
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
