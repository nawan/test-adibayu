<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        return view('items.index');
    }

    public function data()
    {
        return DataTables::of(Item::query())
            ->addColumn('action', function ($item) {
                return '<a href="' . route('items.edit', $item->id) . '" class="btn btn-sm btn-warning">Edit</a> 
                        <form method="POST" action="' . route('items.destroy', $item->id) . '" class="d-inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>';
            })
            ->editColumn('price', function ($item) {
                return 'Rp ' . number_format($item->price, 0, ',', '.');
            })
            ->editColumn('image', function ($item) {
                if ($item->image) {
                    // Gunakan URL yang benar untuk mengakses file
                    $imageUrl = asset('storage/' . $item->image);
                    return '<img src="' . $imageUrl . '" width="50" height="50" class="img-thumbnail rounded">';
                }
                return '<span class="text-muted">No image</span>';
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:items,code',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();

            Storage::makeDirectory('items');

            $manager = new ImageManager(new Driver());
            $img = $manager->read($image);
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $path = 'items/' . $filename;
            Storage::put($path, $img->encode());
            $data['image'] = 'items/' . $filename;
        }

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Item created successfully!');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image) {
                Storage::delete('items/' . $item->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();

            Storage::makeDirectory('items');

            $manager = new ImageManager(new Driver());
            $img = $manager->read($image);
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $path = 'items/' . $filename;
            Storage::put($path, $img->encode());

            $data['image'] = 'items/' . $filename;
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item updated successfully!');
    }

    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::delete('items/' . $item->image);
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully!');
    }

    public function getItemPrice($id)
    {
        $item = Item::findOrFail($id);
        return response()->json(['price' => $item->price]);
    }
}
