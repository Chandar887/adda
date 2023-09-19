@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Create /</span> App Menu </h4>

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
                        <h5 class="card-header">Add Menu</h5>
                        <div class="card-body">
                            <form action="{{route('admin.categories.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Menu Name" />
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Parent Menu (optional)</label>
                                    <select class="form-control" name="parent">
                                        <option value="">None</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Menu Image</label>
                                    <input type="file" class="form-control" name="image" placeholder="Menu Image" />
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
