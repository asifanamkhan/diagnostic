<?php

namespace App\Exports;

use App\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class ExpenseExport implements FromView, ShouldAutoSize
{
    protected $request;

	public function __construct($request) {
	    $this->request = $request;
	}

    public function view(): View
    {
    	$start_date = $this->request->start_date;
        $end_date = $this->request->end_date;
    	$expenses = Expense::whereBetween('date', [$start_date, $end_date])
        ->orderBy('date', 'asc')
        ->get();
        $total = Expense::whereBetween('date', [$start_date, $end_date])->sum('amount');
        $date = Carbon::now()->toDayDateTimeString();

        return view('report.expense.excel', compact('expenses', 'total', 'start_date', 'end_date', 'date'));
    }
}
