<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Events\Item\ItemCreated;
use App\Events\Item\ItemUpdated;
use App\Events\Item\ItemRemoved;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $item = Item::where('item_code','LIKE','%'.$request->q.'%')
                            ->orWhere('item_name','LIKE','%'.$request->q.'%')
                            ->get();
        return $item;
    }

    public function store(ItemRequest $request)
    {
        $validated = $request->validated();

        $item = new Item();
        $item->item_code = $validated['item_code'];
        $item->item_name = $validated['item_name'];
        $item->save();
        broadcast(new ItemCreated($item));

        return response()->json([
            'message'   => "success",
            'item'      => $item
        ]);
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return $item;
    }

    public function update(ItemRequest $request, $id)
    {
        $validated = $request->validated();

        $item = Item::findOrFail($id);
        $item->item_code = $validated['item_code'];
        $item->item_name = $validated['item_name'];
        $item->save();
        broadcast(new ItemUpdated($item));

        return response()->json([
            'message'   => "success",
            'item'      => $item
        ]);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        broadcast(new ItemRemoved('item removed!'));

        return response()->json([
            'message'   => "success",
            'item'      => null
        ]);

    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->ids;
        Item::whereIn('id',explode(",",$ids))->delete();
        broadcast(new ItemRemoved('item removed!'));

        return response()->json([
            'message'   => "success",
            'item'      => null
        ]);
    }
}
