@extends('layouts.master')
@section('title', 'Stock Adjustments Report')

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
                            Stock Adjustments Report 
                            @if ($start_date && $end_date) 
                                from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                            @elseif ($start_date && !$end_date) 
                                from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                            @else
                                from the beginning to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                            @endif
                            <div class="page-title-subheading">
                                Report of Stock Adjustments.
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
                        {{--<form method="POST" action="{{ route('stock-adjustment.all.print') }}" class="d-inline">--}}
                            {{--@csrf--}}
                            {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                            {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                            {{--<input type="hidden" name="product" class="form-control" value="{{ $product }}">--}}
                            {{--<button type="submit" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>--}}
                        {{--</form>--}}

                        {{--<form method="POST" action="{{ route('stock-adjustment.all.pdf') }}" class="d-inline">--}}
                            {{--@csrf--}}
                            {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                            {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                            {{--<input type="hidden" name="product" class="form-control" value="{{ $product }}">--}}
                            {{--<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>--}}
                        {{--</form>--}}

                        {{--<form method="POST" action="{{ route('stock-adjustment.all.excel') }}" class="d-inline">--}}
                            {{--@csrf--}}
                            {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                            {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                            {{--<input type="hidden" name="product" class="form-control" value="{{ $product }}">--}}
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
                                <th>Product</th>
                                <th>Adjustment Class</th>
                                <th>Adjustment Type</th>
                                <th>Pre Quantity</th>
                                <th>Adjusted Quantity</th>
                                <th>After Quantity</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock_adjustments as $stock_adjustment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $stock_adjustment->date }}</td>
                                    <td>{{ $stock_adjustment->product->name }}</td>
                                    <td>
                                        @if ($stock_adjustment->adjustment_class == 1)
                                            Uses
                                        @elseif ($stock_adjustment->adjustment_class == 2)
                                            Wastage
                                        @elseif ($stock_adjustment->adjustment_class == 3)
                                            Adjustment
                                        @else
                                            Others
                                        @endif
                                    </td>
                                    <td>
                                        @if ($stock_adjustment->adjustment_type == 1)
                                            Addition
                                        @elseif ($stock_adjustment->adjustment_type == 2)
                                            Subtraction
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{ $stock_adjustment->prev_quantity }}</td>
                                    <td>{{ $stock_adjustment->adjusted_quantity }}</td>
                                    <td>{{ $stock_adjustment->after_quantity }}</td>
                                    <td>{{ $stock_adjustment->createdBy->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection