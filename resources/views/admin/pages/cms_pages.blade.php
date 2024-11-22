@extends('admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard v2</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v2</li>
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
              @if(Session::has('success_message'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Success:</strong> {{ Session::get('success_message') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">CMS Pages</h3>
                  <a href="{{ url('admin/add-edit-cms-page') }}" style="max-width: 150px; float: right; display: inline-block;" class="btn btn-block btn-primary"><i class="fas fa-plus"></i> Add CMS Page</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="cmspages" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>URL</th>
                      <th>created on</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($CmsPages as $page)
                    <tr>
                      <td>{{ $page['id'] }}</td>
                      <td>{{ $page['title'] }}</td>
                      <td>{{ $page['url'] }}</td>
                      <td>{{ date('F j, Y, g:i a', strtotime($page['created_at'])); }}</td>
                      <td>
                        @if($page['status'] == 1)
                        <a class="updateCmsPageStatus" id="page-{{ $page['id'] }}" page_id={{ $page['id'] }} href="javascript:void(0)"><i class="fas fa-toggle-on" status="Active"></i></a>
                        @else
                        <a class="updateCmsPageStatus" id="page-{{ $page['id'] }}" page_id={{ $page['id'] }} style="color: grey" href="javascript:void(0)"><i class="fas fa-toggle-off" status="Inactive"></i></a>
                        @endif
                        &nbsp; &nbsp;
                        <a href="{{ url('admin/add-edit-cms-page/'.$page['id']) }}"><i class="fas fa-edit"></i></a>
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
