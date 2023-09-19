@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Edit /</span> App Banners </h4>

            <div class="row">
                <!-- Form controls -->
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <h5 class="card-header">Edit Banner</h5>
                        <div class="card-body">
                            <form action="{{route('admin.updatebanner', $banner->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Banner Image</label>
                                    <input type="file" class="form-control" name="image" placeholder="Banner Image" />
                                    <img src="{{ url($banner->image) }}" width="100" >
                                    @if ($errors->has('image'))
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
