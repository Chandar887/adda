@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Add /</span> App Banners </h4>

            <div class="row">
                <!-- Form controls -->
                <div class="col-md-5">
                    <div class="card mb-4">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <h5 class="card-header">Add New Banner</h5>
                        <div class="card-body">
                            <form action="{{route('admin.storebanner')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Banner Image</label>
                                    <input type="file" class="form-control" name="image" placeholder="Banner Image" />
                                    @if ($errors->has('image'))
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SrNo.</th>
                                    <th>Banner</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @if (!empty($banner))
                                    @foreach ($banner as $k=> $m)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td><img src="{{ url($m->image) }}" width="100"></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{route('admin.editbanner', $m->id)}}"><i
                                                                class="bx bx-edit-alt me-1"></i> Edit</a>
                                                        <a class="dropdown-item"
                                                            href="#"
                                                            onclick="return confirm('Are you sure you want to delete this item?');">
                                                            <i class="bx bx-trash me-1"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
