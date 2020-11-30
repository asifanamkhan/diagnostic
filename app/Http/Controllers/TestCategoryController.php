<?php

namespace App\Http\Controllers;

use App\TestCategory;
use Illuminate\Http\Request;
use DB;
use Auth;

class TestCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testCategories = TestCategory::orderBy('id','DESC')->get();
        return view('pages.test_category.index', compact('testCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testCategory = null;
        return view('pages.test_category.create', compact('testCategory'));
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
            } while (TestCategory::where('code', $code)->exists());

            TestCategory::create([
                'name' => $request->name,
                'code' => $code,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('test-category.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(TestCategory $testCategory)
    {
        return view('pages.test_category.edit', compact('testCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestCategory $testCategory)
    {
        $messages = array(
            'name.required' => 'Name is Required.'
        );
        $this->validate($request, array(
            'name' => 'required'
        ), $messages);

        DB::transaction(function () use ($request, $testCategory) {
            $testCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('test-category.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestCategory $testCategory)
    {
        $testCategory->delete();
        
        return redirect()
        ->route('test-category.index')
        ->with('success', 'Deleted Successfully');
    }
}
