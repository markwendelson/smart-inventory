<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Http\Requests\BrandRequest;


class BrandController extends Controller
{
    public function index()
    {
        return view('management.brand.index');
    }

    public function create()
    {
        $brand = new Brand();
        return view('management.brand.form', compact('brand'));
    }
}
