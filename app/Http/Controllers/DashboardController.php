<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();
        $totalSuppliers = Suppliers::count();
        $totalMaterials = Material::count();

        return view('pages.dashboard', compact('totalProducts', 'totalCustomers', 'totalSuppliers', 'totalMaterials'));
    }

}
