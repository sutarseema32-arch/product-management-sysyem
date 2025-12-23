<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('product.list');
    }


    public function fetch_product()
    {

        //return "ds";


        $products = Product::with('images')->latest()->get();
        

        return DataTables::of($products)

        ->addIndexColumn()

        ->addColumn('image_column', function($product) {


                if ($product->images->count() > 0) {
                    $html = '';

                    foreach ($product->images as $img) {
                        $html .= '<img src="'.asset('storage/products/'.$img->image_path).'"
                                   width="50" height="50"
                                   style="margin-right:5px;border-radius:4px;">';
                    }

                    return $html;
                }

                return '-';
       })

        // Action column
        ->addColumn('action', function($row) {
            return ' <div style="display:flex; gap:6px; align-items:center;"><a href="'.route('product.edit', $row->id).'" class="btn btn-sm btn-primary">Edit</a>  
            <button type="button" class="btn btn-danger btn-sm delete-confirm delete-product" data-id="'.$row->id.'">
                Delete
            </button></div>';
        })

        // ->addColumn('department_name', function ($row) {
        //     return $row->department->dep_name ?? '-';
        // })

        ->rawColumns(['image_column', 'action'])  // <-- THIS IS THE KEY
        ->make(true);

        //dd($query->sql, $query->bindings);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('product.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
        'product_name' => 'required|string|max:255',
        'product_price' => 'required|numeric|min:1',
        'product_desc' => 'required|string',
        'image_path' => 'required|array',
        'image_path.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ],
        [
        'product_name.required'=>'Product Name field is required.',    
        'product_price.required'=>'Product Price field is required.',
        'product_price.min' => 'The Product Price must be at least 1.',
        'product_desc.required'=>'Product Description field is required.',
        'image_path.required' => 'Please upload at least one file.',
        'image_path.*.mimes' => 'Only images, Word, PDF, and Excel files are allowed.',
        'image_path.*.max' => 'Each file cannot be larger than 20MB.'
        ]
    );

       
        $product = Product::create([
        'product_name' => $request->product_name,
        'product_price' => $request->product_price,
        'product_desc' => $request->product_desc
        ]);


        if ($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $image) {
                $filename = time().'_'.$image->getClientOriginalName();
                $image->storeAs('products', $filename, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename
                ]);
            }
        }

        return response()->json([
        'status' => true,
        'message' => 'Product added successfully'
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('product.edit',compact('product'));
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
        $product = Product::findOrFail($id);
        $request->validate([
        'product_name' => 'required|string|max:255',
        'product_price' => 'required|numeric|min:1',
        'product_desc' => 'required|string',
        'image_path.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ],
        [
        'product_name.required'=>'Product Name field is required.',    
        'product_price.required'=>'Product Price field is required.',
        'product_price.min' => 'The Product Price must be at least 1.',
        'product_desc.required'=>'Product Description field is required.',
        'image_path.required' => 'Please upload at least one file.',
        'image_path.*.mimes' => 'Only images, Word, PDF, and Excel files are allowed.',
        'image_path.*.max' => 'Each file cannot be larger than 20MB.'
        ]
        );

        // Update product details
        $product->update([
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_desc' => $request->product_desc,
        ]);

         if ($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $image) {
                $filename = time().'_'.$image->getClientOriginalName();
                $image->storeAs('products', $filename, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $filename
                ]);
            }
        }

        $images = $product->images->map(function($img){
            return [
                'id' => $img->id,
                'url' => asset('storage/products/' . $img->image_path)
            ];
        });


        return response()->json([
        'status' => true,
        'message' => 'Product updated successfully',
        'product' => [
        'name' => $product->product_name,
        'price' => $product->product_price,
        'desc' => $product->product_desc,
       
        ],
         'images' => $images
    ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
    $product = Product::with('images')->findOrFail($id);

    // Delete all related images from storage
    foreach ($product->images as $img) {
        if (Storage::disk('public')->exists($img->image_path)) {
            Storage::disk('public')->delete($img->image_path);
        }
    }

    $product->images()->delete();
    $product->delete();

    return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }
}
