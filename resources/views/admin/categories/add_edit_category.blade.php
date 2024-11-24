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
                    <form @if(empty($category['id'])) action="{{ url('admin/add-edit-category') }}" @else action="{{ url('admin/add-edit-category/'.$category['id']) }}" @endif name="categoryForm" id="categoryForm"  method="post" enctype="multipart/form-data">@csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category_name">Category Name*</label>
                                        <input type="text" placeholder="Enter Category Name" class="form-control"
                                            id="category_name" name="category_name">
                                    </div>
                                    <div class="form-group">
                                        <label for="category_image">Category Image*</label>
                                        <input type="file" class="form-control"
                                            id="category_image" name="category_image">
                                    </div>

                                    <div class="form-group">
                                        <label for="category_discount">Category Discount*</label>
                                        <input type="text" class="form-control" placeholder="Enter Category Discount"
                                            id="category_discount" name="category_discount">
                                    </div>
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="url">Category URL*</label>
                                            <input type="text" placeholder="Enter Category URL" class="form-control"
                                                id="url" name="url" @if(!empty($category['url'])) value="{{ $category['url'] }}" @endif>
                                        </div>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Category Description</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter Description" id="description" name="description">@if(!empty($category['description'])) {{ $category['description'] }} @endif</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Title*</label>
                                        <input type="text" placeholder="Enter Meta Title" class="form-control"
                                            id="meta_title" name="meta_title">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description"> Meta Description*</label>
                                        <input type="text" placeholder="Enter Meta Desctiption" class="form-control"
                                            id="meta_description" name="meta_description">
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Keywords*</label>
                                        <input type="text" placeholder="Enter Meta Keywords" class="form-control"
                                            id="meta_keywords" name="meta_keywords">
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
