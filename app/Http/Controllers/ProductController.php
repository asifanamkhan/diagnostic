<?php

namespace App\Http\Controllers;

use App\{ProductCategory, Product};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use DB;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.product.index');
    }

    public function getProductList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'image',
            2 => 'product_category_id',
            3 => 'name',
            4 => 'code',
            5 => 'stock',
            6 => 'actions'
        );

        $totalData = $totalFiltered = Product::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $products = Product::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $products =  Product::with('category')
            ->where('code', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('stock', 'LIKE', "%{$search}%")
            ->orWhereHas('category', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Product::with('category')
            ->where('code', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('stock', 'LIKE', "%{$search}%")
            ->orWhereHas('category', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->count();
        }

        $data = array();
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['image'] = '<a target="_blank" class="" title="Click to View" href=" '.url("images/product/$value->image").' "><img class="card-img" style="width: 45px; height: 40px" src=" '.url("images/product/$value->image").' " alt=""></a>';
                $nestedData['product_category_id'] = $value->category->name;
                $nestedData['name'] = $value->name;
                $nestedData['code'] = $value->code;
                $nestedData['stock'] = $value->stock;
                $nestedData['actions'] = '<div class="">
                    <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" '.route('product.show', $value->id).' " data-toggle="modal" data-target=".view-modal" title="View">
                        <i class="pe-7s-note2 btn-icon-wrapper"></i>
                    </button> 
                    <a href=" '.route('product.edit', $value->id).' " title="Edit">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                        </button>
                    </a>
                    <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('product.destroy', $value->id).' " title="Delete">
                        <i class="fa fa-trash btn-icon-wrapper"></i>
                    </button>
                </div>';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product_categories = ProductCategory::all('id','name');
        $product = null;
        return view('pages.product.create', compact('product_categories', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $messages = array(
            'name.required' => 'Name is Required.',
            'unit.required' => 'Unit is Required.',
            'product_category_id.required' => 'Product Category is Required.'
        );
        $this->validate($request, array(
            'product_category_id' => ['required', Rule::notIn(['','0'])],
            'unit' => 'required'
        ), $messages);

        $result = DB::transaction(function () use ($request) {
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $attached = mt_rand(111, 999) . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path().'/images/product';
                $image->move($destinationPath, $attached);
            }

            do {
                $code = mt_rand(100000, 999999);
            } while (Product::where('code', $code)->exists());

            Product::create([
                'product_category_id' => $request->product_category_id,
                'name' => $request->name,
                'code' => $code,
                'unit' => $request->unit,
                'expire_date' => $request->expire_date,
                'alert_quantity' => $request->alert_quantity ?? 0,
                'image' => $attached ?? 'no_image.png',
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
        });

        if ($request->wantsJson()) {
            $products = Product::all();
            return response()->json([$result, $products]);
        }

        return redirect()
        ->route('product.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('createdBy', 'category');
        if ($product->updated_by) {
            $product->load('updatedBy');
        }
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product_categories = ProductCategory::all('id','name');
        return view('pages.product.edit', compact('product', 'product_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $messages = array(
            'name.required' => 'Name is Required.',
            'unit.required' => 'Unit is Required.',
            'product_category_id.required' => 'Product Category is Required.'
        );
        $this->validate($request, array(
            'product_category_id' => ['required', Rule::notIn(['','0'])],
            'name' => 'required',
            'unit' => 'required'
        ), $messages);

        DB::transaction(function () use ($request, $product) {
            if ($request->hasfile('image')) {
                $images = $request->file('image');
                $attached = mt_rand(111, 999) . time() . '.' . $images->getClientOriginalExtension();
                $destinationPath = public_path().'/images/product';
                $images->move($destinationPath, $attached);
            }

            $product->update([
                'product_category_id' => $request->product_category_id,
                'name' => $request->name,
                'unit' => $request->unit,
                'expire_date' => $request->expire_date,
                'alert_quantity' => $request->alert_quantity ?? 0,
                'image' => $attached ?? $request->oldimage ?? 'no_image.png',
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('product.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image != 'no_image.png') {
            File::delete("images/product/".$product->image);
            $product->update([
                'image' => 'no_image.png'
            ]);
        }
        
        $product->delete();

        return response()->json("Deleted Successfully");
    }
}
