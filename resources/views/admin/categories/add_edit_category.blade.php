@extends('admin.layout.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
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
                        <h3 class="card-title">{{ $title }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    @if (Session::has('success_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success:</strong> {{ Session::get('success_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

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
                    <form
                        @if (empty($category['id'])) action="{{ url('admin/add-edit-category') }}" @else action="{{ url('admin/add-edit-category/' . $category['id']) }}" @endif
                        name="categoryForm" id="categoryForm" method="post" enctype="multipart/form-data">@csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_name">Category Name*</label>
                                        <input type="text" placeholder="Enter Category Name" class="form-control"
                                            id="category_name" name="category_name"
                                            @if(!empty($category['category_name'])) value="{{ $category['category_name'] }}"
                                            @else value="{{ old('category_name') }}"
                                            @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="category_level">Category Level (Parent Category)*</label>
                                        <select name="parent_id" class="form-control">
                                            <option value="">Select</option>
                                            <option value="0"
                                            @if($category['parent_id'] == 0) selected
                                            @endif
                                            >Main Category</option>
                                            @foreach ($getCategories as $cat)
                                                <option
                                                @if(isset($category['parent_id']) && $category['parent_id'] == $cat['id']) selected
                                                @endif
                                                 value="{{ $cat['id'] }}">{{ $cat['category_name'] }}</option>
                                                @if (!empty($cat['subcategories']))
                                                    @foreach ($cat['subcategories'] as $subcat)
                                                        <option value="{{ $subcat['id'] }}"
                                                        @if(isset($category['parent_id']) && $category['parent_id'] == $subcat['id']) selected
                                                        @endif
                                                        >
                                                            &nbsp;&nbsp;&raquo;{{ $subcat['category_name'] }}</option>
                                                        @if (!empty($subcat['subcategories']))
                                                            @foreach ($subcat['subcategories'] as $subsubcat)
                                                                <option value="{{ $subcat['id'] }}">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo; {{ $subsubcat['category_name'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="category_image">Category Image</label>
                                        <input type="file" class="form-control" id="category_image"
                                            name="category_image">
                                            @if(!empty($category['category_image']))
                                            <a target="_blank" href="{{ url('admin/images/categories/'.$category['category_image']) }}">
                                                <img style="width: 50px; margin:10px;" src="{{ asset('admin/images/categories/'.$category['category_image']) }}" alt="">
                                            </a>
                                            <a class="confirmDelete" title="Delete Category Image"
                                            href="javascript:void(0)" record="category-image"
                                            recordid="{{ $category['id'] }}"><i style="color: white" class="fas fa-trash"></i></a>
                                            @endif

                                    </div>

                                    <div class="form-group">
                                        <label for="category_discount">Category Discount</label>
                                        <input type="text" class="form-control" placeholder="Enter Category Discount"
                                            id="category_discount" name="category_discount"
                                            @if(!empty($category['category_discount'])) value="{{ $category['category_discount'] }}"
                                            @else value="{{ old('category_discount') }}"
                                            @endif>
                                    </div>
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="url">Category URL*</label>
                                            <input type="text" placeholder="Enter Category URL" class="form-control"
                                                id="url" name="url"
                                            @if(!empty($category['url'])) value="{{ $category['url'] }}"
                                            @else value="{{ old('url') }}"
                                            @endif>
                                        </div>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Category Description</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter Description" id="description" name="description">@if(!empty($category['description'])) {{ $category['description'] }} @else {{ @old('description') }} @endif</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Title*</label>
                                        <input type="text" placeholder="Enter Meta Title" class="form-control"
                                            id="meta_title" name="meta_title"
                                            @if(!empty($category['meta_title'])) value="{{ $category['meta_title'] }}"
                                            @else value="{{ old('meta_title') }}"
                                            @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description"> Meta Description*</label>
                                        <input type="text" placeholder="Enter Meta Desctiption" class="form-control"
                                            id="meta_description" name="meta_description"
                                            @if(!empty($category['meta_description'])) value="{{ $category['meta_description'] }}"
                                            @else value="{{ old('meta_description') }}"
                                            @endif>
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Keywords*</label>
                                        <input type="text" placeholder="Enter Meta Keywords" class="form-control"
                                            id="meta_keywords" name="meta_keywords"
                                            @if(!empty($category['meta_keywords'])) value="{{ $category['meta_keywords'] }}"
                                            @else value="{{ old('meta_keywords') }}"
                                            @endif>
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
