@extends('admin.layout.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add CMS Page</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add CMS Page</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- SELECT2 EXAMPLE -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Add CMS Page</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- /.card-header -->
                    <form @if(empty($subadmindata['id'])) action="{{ url('admin/add-edit-subadmin') }}" @else action="{{ url('admin/add-edit-subadmin/'.$subadmindata['id']) }}" @endif name="subadminForm" id="subadminForm"  method="post" enctype="multipart/form-data">@csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group col-md-6">
                                        <label>Name*</label>
                                        <input type="text" placeholder="Enter Name" class="form-control"
                                            id="name" name="name" @if(!empty($subadmindata['name'])) value="{{ $subadmindata['name'] }}" @endif>
                                    </div>
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                        <div class="form-group col-md-6">
                                            <label>Mobile*</label>
                                            <input type="text" placeholder="Enter Mobile" class="form-control"
                                                id="mobile" name="mobile" @if(!empty($subadmindata['mobile'])) value="{{ $subadmindata['mobile'] }}" @endif>
                                        </div>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email*</label>
                                        <input type="email" placeholder="Enter Email" class="form-control"
                                            id="email" name="email" @if(!empty($subadmindata['email'])) value="{{ $subadmindata['email'] }}" @endif>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Password*</label>
                                        <input @if($subadmindata['id'] != "") disabled @else required @endif type="password" placeholder="Enter Password" class="form-control"
                                            id="password" name="password" @if(!empty($subadmindata['password'])) value="{{ $subadmindata['password'] }}" @endif>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="image">Photo</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        @if(!empty($subadmindata['image']))
                                        <a target="_blank" href="{{  url('admin/images/photos/'.$subadmindata['image']) }}">View Photo</a>
                                        <input type="hidden" name="current_image" value="{{ $subadmindata['image'] }}">
                                        @endif
                                      </div>
                                </div>
                                <!-- /.form-group -->
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </div>
                </form>
            </div>
    </div>
    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
@endsection
