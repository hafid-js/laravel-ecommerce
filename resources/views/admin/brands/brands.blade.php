@extends('admin.layout.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Brands</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Brands</li>
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
                            @if ($brandsModule['edit_access'] == 1 || $brandsModule['full_access'] == 1)
                                <div class="card-header">
                                    <h3 class="card-title">brands</h3>
                                    <a href="{{ url('admin/add-edit-brand') }}"
                                        style="max-width: 150px; float: right; display: inline-block;"
                                        class="btn btn-block btn-primary"><i class="fas fa-plus"></i> Add Brand</a>
                                </div>
                            @endif

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="cmspages" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>URL</th>
                                            <th>created on</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($brands as $brand)
                                            <tr>
                                                <td>{{ $brand['id'] }}</td>
                                                <td>{{ $brand['brand_name'] }}</td>
                                                <td>{{ $brand['url'] }}</td>
                                                <td>{{ date('F j, Y, g:i a', strtotime($brand['created_at'])) }}</td>
                                                <td>
                                                    @if ($brandsModule['edit_access'] == 1 || $brandsModule['full_access'] == 1)
                                                        @if ($brand['status'] == 1)
                                                            <a class="updateBrandStatus" id="brand-{{ $brand['id'] }}"
                                                                brand_id={{ $brand['id'] }} href="javascript:void(0)"><i
                                                                    class="fas fa-toggle-on" status="Active"></i></a>
                                                        @else
                                                            <a class="updateBrandStatus" id="brand-{{ $brand['id'] }}"
                                                                brand_id={{ $brand['id'] }} style="color: grey"
                                                                href="javascript:void(0)"><i class="fas fa-toggle-off"
                                                                    status="Inactive"></i></a>
                                                        @endif
                                                    @endif
                                                    @if ($brandsModule['edit_access'] == 1 || $brandsModule['full_access'] == 1)
                                                        &nbsp; &nbsp;
                                                        <a href="{{ url('admin/add-edit-brand/' . $brand['id']) }}"><i
                                                                class="fas fa-edit"></i></a>
                                                        &nbsp; &nbsp;
                                                        @if ($brandsModule['full_access'] == 1)
                                                            <a class="confirmDelete" title="Delete Brand"
                                                                href="javascript:void(0)" record="brand"
                                                                recordid="{{ $brand['id'] }}"><i
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
