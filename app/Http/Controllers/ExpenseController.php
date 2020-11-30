<?php

namespace App\Http\Controllers;

use App\{ExpenseCategory, Expense};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Exports\ExpenseExport;
use Carbon\Carbon;
use DB;
use Auth;
use PDF;
use Excel;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expense_categories = ExpenseCategory::all();
        return view('pages.expense.index',compact('expense_categories'));
    }

    public function getExpenseList(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'expense_category_id',
            3 => 'invoice',
            4 => 'amount',
            5 => 'actions'
        );

        $totalData = $totalFiltered = Expense::count();
        if ($request->input('length') == -1) {
            $limit = $totalData;
        } else {
            $limit = $request->input('length');
        }
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $expenses = Expense::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->latest()
            ->get();
        } else {
            $search = $request->input('search.value');

            $expenses = Expense::with('category')
            ->where('amount', 'LIKE', "%{$search}%")
            ->orWhere('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhereHas('category', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Expense::with('category')
            ->where('amount', 'LIKE', "%{$search}%")
            ->orWhere('invoice', 'LIKE', "%{$search}%")
            ->orWhere('date', 'LIKE', "%{$search}%")
            ->orWhereHas('category', function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->count();
        }

        $data = array();
        if (!empty($expenses)) {
            foreach ($expenses as $key => $value) {
                $nestedData['id'] = $key + 1;
                $nestedData['date'] = date("d M, Y", strtotime($value->date));
                $nestedData['expense_category_id'] = $value->category->name;
                $nestedData['invoice'] = $value->invoice;
                $nestedData['amount'] = $value->amount;
                $nestedData['actions'] = '<div class="">
                    <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" '.route('expense.show', $value->id).' " data-toggle="modal" data-target=".view-modal" title="View">
                        <i class="pe-7s-note2 btn-icon-wrapper"></i>
                    </button> 
                    <a href=" '.route('expense.edit', $value->id).' " title="Edit">
                        <button class="mr-2 btn-icon btn-icon-only btn btn-success">
                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                        </button>
                    </a>
                    <button class="mr-2 btn-icon btn-icon-only btn btn-danger btn-delete" data-remote=" '.route('expense.destroy', $value->id).' " title="Delete">
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
        $expense_categories = ExpenseCategory::all('id','name');
        $expense = null;
        return view('pages.expense.create', compact('expense', 'expense_categories'));
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
            'date.required' => 'Date is Required.',
            'expense_category_id.required' => 'Category is Required.',
            'amount.required' => 'Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'expense_category_id' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request) {
            do {
                $code = mt_rand(100000, 999999);
            } while (ExpenseCategory::where('code', $code)->exists());

            Expense::create([
                'date' => $request->date,
                'expense_category_id' => $request->expense_category_id,
                'invoice' => $code,
                'quantity' => $request->quantity,
                'rate' => $request->rate,
                'amount' => $request->amount,
                'description' => $request->description,
                'created_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('expense.index')
        ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $expense->load('createdBy', 'category');
        if ($expense->updated_by) {
            $expense->load('updatedBy');
        }
        return response()->json($expense);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $expense_categories = ExpenseCategory::all('id','name');
        return view('pages.expense.edit', compact('expense', 'expense_categories'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $messages = array(
            'date.required' => 'Date is Required.',
            'expense_category_id.required' => 'Category is Required.',
            'amount.required' => 'Amount is Required.'
        );
        $this->validate($request, array(
            'date' => 'required|date|date_format:Y-m-d',
            'expense_category_id' => ['required', Rule::notIn(['','0'])],
            'amount' => 'required|numeric|min:0'
        ), $messages);

        DB::transaction(function () use ($request, $expense) {
            $expense->update([
                'date' => $request->date,
                'expense_category_id' => $request->expense_category_id,
                'quantity' => $request->quantity,
                'rate' => $request->rate,
                'amount' => $request->amount,
                'description' => $request->description,
                'updated_by' => Auth::id()
            ]);
        });

        return redirect()
        ->route('expense.index')
        ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json("Deleted Successfully");
    }

    public function returnPageView()
    {
        $expense_categories = ExpenseCategory::all('id','name');
        return view('report.view', compact('expense_categories'));
    }

    public function expensesReport(Request $request)
    {
        $this->validate($request, array(
            'expense_category' => ['nullable', Rule::notIn(['0'])],
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d'
        ));

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $expense_category = $request->expense_category;

        $expenses = Expense::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->expense_category != '', function ($q) use ($request) {
            return $q->where('expense_category_id', $request->expense_category);
        })
        ->orderBy('date', 'asc')
        ->get();
        $total = Expense::when($request->start_date == '' && $request->end_date != '', function ($q) use ($request) {
            return $q->where('date', '<=', $request->end_date);
        })
        ->when($request->start_date != '' && $request->end_date == '', function ($q) use ($request) {
            return $q->where('date', '>=', $request->start_date);
        })
        ->when($request->start_date != '' && $request->end_date != '', function ($q) use ($request) {
            return $q->whereBetween('date', [$request->start_date, $request->end_date]);
        })
        ->when($request->expense_category != '', function ($q) use ($request) {
            return $q->where('expense_category_id', $request->expense_category);
        })
        ->sum('amount');

        return view('report.expense.report', compact('expenses', 'total', 'start_date', 'end_date', 'expense_category'));
    }

    public function printAllExpenses(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $expenses = Expense::whereBetween('date', [$start_date, $end_date])
        ->orderBy('date', 'asc')
        ->get();
        $total = Expense::whereBetween('date', [$start_date, $end_date])->sum('amount');
        $date = Carbon::now()->toDayDateTimeString();

        return view('report.expense.print', compact('expenses', 'total', 'start_date', 'end_date', 'date'));
    }

    public function pdfAllExpenses(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $expenses = Expense::whereBetween('date', [$start_date, $end_date])
        ->orderBy('date', 'asc')
        ->get();
        $total = Expense::whereBetween('date', [$start_date, $end_date])->sum('amount');
        $date = Carbon::now()->toDayDateTimeString();

        $pdf = PDF::loadView('report.expense.pdf', compact('expenses', 'total', 'start_date', 'end_date', 'date'));
        return $pdf->download('expense report.pdf');
    }

    public function excelAllExpenses(Request $request)
    {
        return Excel::download(new ExpenseExport($request), 'expense report.xlsx');
    }
}
