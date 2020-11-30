@extends('layouts.master')
@section('title', 'Expense-Category Report')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-gleam text-success"></i>
                    </div>
                    <div>
                        Expense-Category Report from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        <div class="page-title-subheading">
                            Report of Expense-Category.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                        <i class="fa fa-star"></i>
                    </button>
                    <div class="d-inline-block dropdown"></div>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            {{--<div class="card-header">--}}
                {{--<div class="print-pdf-excel">--}}
                    {{--<form method="POST" action="{{ route('expense.all.print') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" id="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<button type="submit" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}

                    {{--<form method="POST" action="{{ route('expense.all.pdf') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" id="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}

                    {{--<form method="POST" action="{{ route('expense.all.excel') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" id="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="card-body">
                <table style="width: 100%;" id="" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $expense->date }}</td>
                                <td>{{ $expense->invoice }}</td>
                                <td>{{ $expense->quantity }}</td>
                                <td>{{ $expense->rate }}</td>
                                <td>{{ $expense->amount }}</td>
                                <td>{{ $expense->createdBy->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Total</th>
                            <th>{{ number_format($total, 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection