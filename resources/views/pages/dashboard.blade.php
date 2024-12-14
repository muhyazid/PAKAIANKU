@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- Total Produk -->
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-light">{{ $totalProducts }}</h3>
                            <h6 class="text-muted font-weight-normal">Total Produk</h6>
                        </div>
                        <div class="icon icon-box-primary">
                            <i class="mdi mdi-package-variant-closed icon-item"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-light">{{ $totalCustomers }}</h3>
                            <h6 class="text-muted font-weight-normal">Total Customer</h6>
                        </div>
                        <div class="icon icon-box-success">
                            <i class="mdi mdi-account-group icon-item"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Suppliers -->
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-light">{{ $totalSuppliers }}</h3>
                            <h6 class="text-muted font-weight-normal">Total Supplier</h6>
                        </div>
                        <div class="icon icon-box-warning">
                            <i class="mdi mdi-truck-delivery icon-item"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Materials -->
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-light">{{ $totalMaterials }}</h3>
                            <h6 class="text-muted font-weight-normal">Total Material</h6>
                        </div>
                        <div class="icon icon-box-info">
                            <i class="mdi mdi-tools icon-item"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .icon-box-primary {
            background-color: #4caf50;
            /* Hijau */
            color: #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-box-success {
            background-color: #2196f3;
            /* Biru */
            color: #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-box-warning {
            background-color: #ff9800;
            /* Oranye */
            color: #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-box-info {
            background-color: #00bcd4;
            /* Biru Muda */
            color: #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection
