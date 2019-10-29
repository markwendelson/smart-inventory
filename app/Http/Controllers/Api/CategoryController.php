<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Events\Category\CategoryCreated;
use App\Events\Category\CategoryRemoved;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('category_code','LIKE','%'.$request->q.'%')
                            ->orWhere('category_name','LIKE','%'.$request->q.'%')
                            ->get();
        return $categories;
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        $category = new Category();
        $category->category_code = $validated['category_code'];
        $category->category_name = $validated['category_name'];
        $category->save();
        broadcast(new CategoryCreated($category));

        return response()->json([
            'message'   => "success",
            'category'      => $category
        ]);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    public function update(CategoryRequest $request, $id)
    {
        $validated = $request->validated();

        $category = Category::findOrFail($id);
        $category->category_code = $validated['category_code'];
        $category->category_name = $validated['category_name'];
        $category->save();
        broadcast(new CategoryUpdated($category));

        return response()->json([
            'message'   => "success",
            'category'      => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        broadcast(new CategoryRemoved('category removed!'));

        return response()->json([
            'message'   => "success",
            'category'      => null
        ]);

    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->ids;
        Category::whereIn('id',explode(",",$ids))->delete();
        broadcast(new CategoryRemoved('category removed!'));

        return response()->json([
            'message'   => "success",
            'category'      => null
        ]);
    }
}
