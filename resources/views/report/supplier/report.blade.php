@extends('layouts.master')
@section('title', 'Supplier Report')

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
                        Supplier {{ $supplier->name }}'s Report 
                        @if ($start_date && $end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @elseif ($start_date && !$end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @else
                            from the beginning to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @endif
                        <div class="page-title-subheading">
                            Report of Supplier.
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
        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
            <li class="nav-item">
                <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0">
                    <span>Purchases</span>
                </a>
            </li>
            <li class="nav-item">
                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                    <span>Balance Sheet</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                <div class="main-card mb-3 card">
                    {{--<div class="card-header">--}}
                        {{--<div class="print-pdf-excel">--}}
                            {{--<form method="POST" action="{{ route('supplier.report.print') }}" class="d-inline">--}}
                                {{--@csrf--}}
                                {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                                {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                                {{--<input type="hidden" name="supplier" class="form-control" value="{{ $supplier->id }}">--}}
                                {{--<button type="submit" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>--}}
                            {{--</form>--}}

                            {{--<form method="POST" action="{{ route('supplier.report.pdf') }}" class="d-inline">--}}
                                {{--@csrf--}}
                                {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                                {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                                {{--<input type="hidden" name="supplier" class="form-control" value="{{ $supplier->id }}">--}}
                                {{--<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>--}}
                            {{--</form>--}}

                            {{--<form method="POST" action="{{ route('supplier.report.excel') }}" class="d-inline">--}}
                                {{--@csrf--}}
                                {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                                {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                                {{--<input type="hidden" name="supplier" class="form-control" value="{{ $supplier->id }}">--}}
                                {{--<button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>--}}
                            {{--</form>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sl#</th>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Product - Amount</th>
                                            <th>Total</th>
                                            <th>Discount</th>
                                            <th>Final Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchases as $purchase)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $purchase->date }}</td>
                                                <td>{{ $purchase->invoice }}</td>
                                                <td>
                                                    @foreach ($purchase->lists as $list)
                                                        {{ $loop->iteration }}. {{ $list->product->name }} - {{ number_format($list->amount, 2) }}<br>
                                                    @endforeach
                                                </td>

                                                <td>{{ number_format($purchase->total_amount, 2) }}</td>
                                                <td>{{ number_format($purchase->discount, 2) }}</td>
                                                <td>{{number_format(( $purchase->total_amount - $purchase->discount), 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="report-text-right">Total</th>
                                            <th>{{ number_format($purchase_total_amount, 2) }}</th>
                                            <th>{{ number_format($purchase_discount, 2) }}</th>
                                            <th>{{ number_format(($purchase_total_amount - $purchase_discount), 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        <div class="print-pdf-excel">
                            <form method="POST" action="{{ route('supplier.report.print') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">
                                <input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">
                                <input type="hidden" name="supplier" class="form-control" value="{{ $supplier->id }}">
                                <button type="submit" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>
                            </form>

                            <form method="POST" action="{{ route('supplier.report.pdf') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">
                                <input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">
                                <input type="hidden" name="supplier" class="form-control" value="{{ $supplier->id }}">
                                <button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                            </form>

                            <form method="POST" action="{{ route('supplier.report.excel') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">
                                <input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">
                                <input type="hidden" name="supplier" class="form-control" value="{{ $supplier->id }}">
                                <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <table style="width: 100%;" class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl#</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->date }}</td>
                                            @if ($data->getTable() == 'purchases')
                                                <td>Purchase</td>
                                                <td>0</td>
                                                <td>{{ number_format(($data->total_amount - $data->discount), 2) }}</td>
                                                <td>{{ number_format(($balance += ($data->total_amount - $data->discount)), 2) }}</td>
                                            @else
                                                <td>Payment</td>
                                                <td>{{ number_format($data->amount, 2) }}</td>
                                                <td>0</td>
                                                <td>{{ number_format(($balance -= $data->amount), 2) }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection