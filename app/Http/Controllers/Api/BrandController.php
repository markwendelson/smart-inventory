<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Events\Brand\BrandCreated;
use App\Events\Brand\BrandUpdated;
use App\Events\Brand\BrandRemoved;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brand = Brand::where('brand_code','LIKE','%'.$request->q.'%')
                        ->orWhere('brand_code','LIKE','%'.$request->q.'%')
                        ->get();
        return $brand;                  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

    }


    public function store(BrandRequest $request)
    {   
        
        $validated = $request->validate();
        
        $brand = new Brand();
        $brand->brand_code = $validated['brand_code'];
        $brand->brand_name = $validated['brand_name'];
        $brand->save();

        broadcast(new BrandCreated($brand));

        return response()->json([
            'message' => "success",
            'brand' => $brand
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return $brand;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        broadcast(new BrandRemoved('category removed'));

        return response()->json([
            'message' => "success",
            'category' => null
        ]);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->ids;
        Brand::whereIn('id',explore(",",$ids))->delete();
        broadcast(new BrandRemoved('brand removed'));

        return response()->json([
            'message' => "success",
            'category' => null
        ]);
    }
}
