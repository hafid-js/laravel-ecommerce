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
                        @if (empty($banner['id'])) action="{{ url('admin/add-edit-banner') }}" @else action="{{ url('admin/add-edit-banner/' . $banner['id']) }}" @endif
                        name="bannerForm" id="bannerForm" method="post" enctype="multipart/form-data">@csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="type">Banner Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Select</option>
                                        <option
                                        @if(!empty($banner['type'] == "Slider")) selected @endif
                                         value="Slider">Slider</option>
                                        <option @if(!empty($banner['type'] == "1")) selected @endif value="Fix 1">Fix 1</option>
                                        <option @if(!empty($banner['type'] == "2")) selected @endif value="Fix 2">Fix 2</option>
                                        <option @if(!empty($banner['type'] == "3")) selected @endif value="Fix 3">Fix 3</option>
                                        <option @if(!empty($banner['type'] == "4")) selected @endif value="Fix 4">Fix 4</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="image">Banner Image</label>
                                    <input type="file" class="form-control" id="image"
                                        name="image">
                                        @if(!empty($banner['image']))
                                        <a target="_blank" href="{{ url('admin/images/banners/'.$banner['image']) }}">
                                            <img style="width: 50px; margin:10px;" src="{{ asset('admin/images/banners/'.$banner['image']) }}" alt="">
                                        </a>
                                        <a class="confirmDelete" title="Delete Banner Image"
                                        href="javascript:void(0)" record="banner-image"
                                        recordid="{{ $banner['id'] }}"><i style="color: white" class="fas fa-trash"></i></a>
                                        @endif

                                </div>
                                    <div class="form-group">
                                        <label for="title">Banner Title*</label>
                                        <input type="text" placeholder="Enter banner Name" class="form-control"
                                            id="title" name="title"
                                            @if(!empty($banner['title'])) value="{{ $banner['title'] }}"
                                            @else value="{{ old('title') }}"
                                            @endif>
                                    </div>

                                    <div class="form-group">
                                        <label for="alt">Banner Alt*</label>
                                        <input type="text" placeholder="Enter banner Name" class="form-control"
                                            id="alt" name="alt"
                                            @if(!empty($banner['alt'])) value="{{ $banner['alt'] }}"
                                            @else value="{{ old('alt') }}"
                                            @endif>
                                    </div>

                                    <div class="form-group">
                                        <label for="link">Banner Link</label>
                                        <input type="text" class="form-control" placeholder="Enter Banner Discount"
                                            id="link" name="link"
                                            @if(!empty($banner['link'])) value="{{ $banner['link'] }}"
                                            @else value="{{ old('link') }}"
                                            @endif>
                                    </div>


                                    <div class="form-group">
                                        <label for="sort">Banner Sort</label>
                                        <input type="text" class="form-control" placeholder="Enter Banner Sort"
                                            id="sort" name="sort"
                                            @if(!empty($banner['sort'])) value="{{ $banner['sort'] }}"
                                            @else value="{{ old('sort') }}"
                                            @endif>
                                    </div>
                                    <!-- /.form-group -->
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
