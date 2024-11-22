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
                    <form @if(empty($cmspage['id'])) action="{{ url('admin/add-edit-cms-page') }}" @else action="{{ url('admin/add-edit-cms-page/'.$cmspage['id']) }}" @endif name="cmsForm" id="cmsForm"  method="post">@csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Title*</label>
                                        <input type="text" placeholder="Enter Page Title" class="form-control"
                                            id="title" name="title" @if(!empty($cmspage['title'])) value="{{ $cmspage['title'] }}" @endif>
                                    </div>
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>URL*</label>
                                            <input type="text" placeholder="Enter Page URL" class="form-control"
                                                id="url" name="url" @if(!empty($cmspage['url'])) value="{{ $cmspage['url'] }}" @endif>
                                        </div>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter Description" id="description" name="description">@if(!empty($cmspage['description'])) {{ $cmspage['description'] }} @endif</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Title*</label>
                                        <input type="text" placeholder="Enter Meta Title" class="form-control"
                                            id="meta_title" name="meta_title" @if(!empty($cmspage['meta_title'])) value="{{ $cmspage['meta_title'] }}" @endif>
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Description*</label>
                                        <input type="text" placeholder="Enter Meta Desctiption" class="form-control"
                                            id="meta_description" name="meta_description" @if(!empty($cmspage['meta_description'])) value="{{ $cmspage['meta_description'] }}" @endif>
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Keywords*</label>
                                        <input type="text" placeholder="Enter Meta Keywords" class="form-control"
                                            id="meta_keywords" name="meta_keywords" @if(!empty($cmspage['meta_keywords'])) value="{{ $cmspage['meta_keywords'] }}" @endif>
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
