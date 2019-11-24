<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Events\Brand\BrandCreated;
use App\Events\Brand\BrandUpdated;
use App\Events\Brand\BrandRemoved;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brand = Brand::where('brand_code','LIKE','%'.$request->q.'%')
                            ->orWhere('brand_name','LIKE','%'.$request->q.'%')
                            ->get();
        return $brand;
    }

    public function store(BrandRequest $request)
    {
        $validated = $request->validated();

        $brand = new Brand();
        $brand->brand_code = $validated['brand_code'];
        $brand->brand_name = $validated['brand_name'];
        $brand->save();
        broadcast(new BrandCreated($brand));

        return response()->json([
            'message'   => "success",
            'brand'      => $brand
        ]);
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return $brand;
    }

    public function update(BrandRequest $request, $id)
    {
        $validated = $request->validated();

        $brand = Brand::findOrFail($id);
        $brand->brand_code = $validated['brand_code'];
        $brand->brand_name = $validated['brand_name'];
        $brand->save();
        broadcast(new BrandUpdated($brand));

        return response()->json([
            'message'   => "success",
            'brand'      => $brand
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        broadcast(new BrandRemoved('brand removed!'));

        return response()->json([
            'message'   => "success",
            'brand'      => null
        ]);

    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->ids;
        Brand::whereIn('id',explode(",",$ids))->delete();
        broadcast(new BrandRemoved('brand removed!'));

        return response()->json([
            'message'   => "success",
            'brand'      => null
        ]);
    }
}
