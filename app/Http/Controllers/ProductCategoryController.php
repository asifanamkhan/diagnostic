<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;
use DB;
use Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_categories = ProductCategory::orderBy('id','DESC')->get();
        return view('pages.product_category.index', compact('product_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productCategory = null;
        return view('pages.product_category.create', compact('productCategory'));
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
            'name.required' => 'Name is Required.'
        );
        $this->validate($request, array(
            'name' => 'required'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $code = mt_rand(100000, 999999);
            } while (ProductCategory::where('code', $code)->exists());

            ProductCategory::create([
                'name' => $request->name,
                'code' => $code,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('product-category.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategory $productCategory)
    {
        return view('pages.product_category.edit', compact('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $messages = array(
            'name.required' => 'Name is Required.'
        );
        $this->validate($request, array(
            'name' => 'required'
        ), $messages);

        DB::transaction(function () use ($request, $productCategory) {
            $productCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('product-category.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect()
        ->route('product-category.index')
        ->with('success', 'Deleted Successfully');
    }
}
