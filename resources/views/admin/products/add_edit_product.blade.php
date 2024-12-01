@extends('admin.layout.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add Product</li>
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
                        <h3 class="card-title">Add Product</h3>

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
                        @if (empty($product['id'])) action="{{ url('admin/add-edit-product') }}" @else action="{{ url('admin/add-edit-product/' . $product['id']) }}" @endif
                        name="productForm" id="productForm" method="post" enctype="multipart/form-data">@csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="category_id">Select Category*</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($getCategories as $cat)
                                                <option @if(!empty(@old('category_id')) && $cat['id'] == @old('category_id')) selected @endif value="{{ $cat['id'] }}">{{ $cat['category_name'] }}</option>
                                                @if (!empty($cat['subcategories']))
                                                    @foreach ($cat['subcategories'] as $subcat)
                                                        <option @if(!empty(@old('category_id')) && $subcat['id'] == @old('category_id')) selected @endif  value="{{ $subcat['id'] }}">&nbsp;&nbsp;&raquo;{{ $subcat['category_name'] }}</option>
                                                        @if (!empty($subcat['subcategories']))
                                                            @foreach ($subcat['subcategories'] as $subsubcat)
                                                                <option @if(!empty(@old('category_id')) && $subsubcat['id'] == @old('category_id')) selected @endif  value="{{ $subcat['id'] }}">
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
                                        <label for="product_name">Product Name*</label>
                                        <input type="text" class="form-control" placeholder="Enter Product Name"
                                            id="product_name" name="product_name"
                                            @if(!empty(@old('product_name'))) value="{{ @old('product_name') }}" @endif
                                            >
                                    </div>
                                    <div class="form-group">
                                        <label for="product_code">Product Code*</label>
                                        <input type="text" class="form-control" placeholder="Enter Product Code"
                                            id="product_code" name="product_code" @if(!empty(@old('product_code'))) value="{{ @old('product_code') }}" @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_color">Product Color*</label>
                                        <input type="text" class="form-control" placeholder="Enter Product Color"
                                            id="product_color" name="product_color" @if(!empty(@old('product_color'))) value="{{ @old('product_color') }}" @endif>
                                    </div>
                                    <div class="form-group">
                                        <label for="family_color">Family Color*</label>
                                        <select name="family_color" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Red"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Red") selected @endif>Red</option>
                                            <option value="Green"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Green") selected @endif>Green</option>
                                            <option value="Yellow"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Yellow") selected @endif>Yellow</option>
                                            <option value="Black"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Black") selected @endif>Black</option>
                                            <option value="White"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "White") selected @endif>White</option>
                                            <option value="Blue"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Blue") selected @endif>Blue</option>
                                            <option value="Orange"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Orange") selected @endif>Orange</option>
                                            <option value="Grey"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Grey") selected @endif>Grey</option>
                                            <option value="Silver"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Silver") selected @endif>Silver</option>
                                            <option value="Golden"
                                            @if(!empty(@old('family_color')) && @old('family_color') == "Golden") selected @endif>Golden</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="group_code">Group Code</label>
                                        <input type="text" class="form-control" placeholder="Enter Group Code"
                                            id="group_code" name="group_code">
                                    </div>
                                    <div class="form-group">
                                        <label for="product_price">Product Price*</label>
                                        <input type="text" class="form-control" placeholder="Enter Product Price"
                                            id="product_price" name="product_price"
                                            @if(!empty(@old('product_price'))) value="{{ @old('product_price') }}" @endif required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_discount">Product Discount (%)</label>
                                        <input type="text" class="form-control" placeholder="Enter Product Discount (%)"
                                            id="product_discount" name="product_discount">
                                    </div>
                                    <div class="form-group">
                                        <label for="product_weight">Product Wight</label>
                                        <input type="text" class="form-control" placeholder="Enter Product Weight"
                                            id="product_weight" name="product_weight">
                                    </div>
                                    <div class="form-group">
                                        <label for="product_video">Product Video</label>
                                        <input type="file" class="form-control" id="product_video" name="product_video">
                                    </div>
                                    <div class="form-group">
                                        <label for="fabric">Fabric</label>
                                        <select name="fabric" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($productsFilters['fabricArray'] as $fabric)
                                            <option value="{{ $fabric }}">{{ $fabric }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="sleeve">Sleeve</label>
                                        <select name="sleeve" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($productsFilters['sleeveArray'] as $sleeve)
                                            <option value="{{ $sleeve }}">{{ $sleeve }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="pattern">Pattern</label>
                                        <select name="pattern" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($productsFilters['patternArray'] as $pattern)
                                            <option value="{{ $pattern }}">{{ $pattern }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fit">Fit</label>
                                        <select name="fit" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($productsFilters['fitArray'] as $fit)
                                            <option value="{{ $fit }}">{{ $fit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="occasion">Occasion</label>
                                        <select name="occasion" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($productsFilters['occasionArray'] as $occasion)
                                            <option value="{{ $occasion }}">{{ $occasion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter Product Description" id="description" name="description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="wash_care">Wash Care</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter Wash Care" id="wash_care" name="wash_care"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="search_keywords">Search Keywords</label>
                                        <textarea class="form-control" rows="3" placeholder="Enter Search Keywords" id="search_keywords" name="search_keywords"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" placeholder="Enter Meta Title" class="form-control"
                                            id="meta_title" name="meta_title">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description"> Meta Description</label>
                                        <input type="text" placeholder="Enter Meta Desctiption" class="form-control"
                                            id="meta_description" name="meta_description">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_keywords">Meta Keywords</label>
                                        <input type="text" placeholder="Enter Meta Keywords" class="form-control"
                                            id="meta_keywords" name="meta_keywords">
                                    </div>
                                    <div class="form-group">
                                        <label for="is_featured">Featured Item</label>
                                        <input type="checkbox" id="is_featured" name="is_featured" value="Yes">
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
