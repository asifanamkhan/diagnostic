<?php

namespace App\Http\Controllers;

use App\{ExpenseCategory, Expense};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Auth;


class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenseCategories = ExpenseCategory::orderBy('id','DESC')->get();
        return view('pages.expense_category.index', compact('expenseCategories'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expenseCategory = null;
        return view('pages.expense_category.create', compact('expenseCategory'));
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
            } while (ExpenseCategory::where('code', $code)->exists());

            ExpenseCategory::create([
                'name' => $request->name,
                'code' => $code,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('expense-category.index')
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
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('pages.expense_category.edit', compact('expenseCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $messages = array(
            'name.required' => 'Name is Required.'
        );
        $this->validate($request, array(
            'name' => 'required'
        ), $messages);

        DB::transaction(function () use ($request, $expenseCategory) {
            $expenseCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('expense-category.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        
        return redirect()
        ->route('expense-category.index')
        ->with('success', 'Deleted Successfully');
    }

    public function returnPageView()
    {
        $expense_categories = ExpenseCategory::all('id','name');
        return view('report.view',compact('expense_categories'));
    }

    public function categorizedExpensesReport(Request $request)
    {
        $messages = array(
            'expense_category.required' => 'Doctor is Required.',
            'end_date.required' => 'End Date is Required.'
        );
        $this->validate($request, array(
            'expense_category' => 'required',
            'end_date' => Rule::requiredIf($request->start_date != '', Rule::notIn(['','0'])),
        ), $messages);


        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if($start_date != ''){

            $expense_category = ExpenseCategory::findOrfail($request->expense_category);

            $expenses = $expense_category->expenses()->whereBetween('date', [$start_date, $end_date])
                ->orderBy('date', 'asc')
                ->get();

            $total = $expense_category->expenses()->whereBetween('date', [$start_date, $end_date])
                ->sum('amount');
        }
        else{
            $expense_category = ExpenseCategory::findOrfail($request->expense_category);

            $expenses = $expense_category->expenses()->orderBy('date', 'asc')
                ->get();

            $total = $expense_category->expenses()->sum('amount');
        }

        return view('report.expense_category.report',
            compact('start_date', 'end_date','expenses','total'));
    }
}
