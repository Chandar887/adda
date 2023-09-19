@extends('layouts.app')
@section('content')

<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-lg-12 col-md-12 order-1">
        <div class="row">
          <div class="col-lg-4 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <span class="fw-semibold d-block mb-1">Total Menu</span>
                <h3 class="card-title mb-2">30</h3>
                {{-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> --}}
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <span>Total Order</span>
                <h3 class="card-title text-nowrap mb-1">20</h3>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <span>Today Order</span>
                <h3 class="card-title text-nowrap mb-1">10</h3>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <span>Total Revenue</span>
                <h3 class="card-title text-nowrap mb-1">20</h3>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <span>Recent Order</span>
                <h3 class="card-title text-nowrap mb-1">20</h3>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <!-- / Content -->

@endsection