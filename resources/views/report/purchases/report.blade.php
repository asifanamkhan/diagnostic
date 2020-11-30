@extends('layouts.master')
@section('title', 'Purchase Report')

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
                        Purchase Report
                        @if ($prod)
                        of Product {{ $prod->name }}
                        @endif

                        @if ($start_date && $end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @elseif ($start_date && !$end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @else
                            from the beginning to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @endif
                        <div class="page-title-subheading">
                            Report of Purchases.
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
                    {{--<form method="POST" action="{{ route('purchase.all.print') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="product" class="form-control" value="{{ $product }}">--}}
                        {{--<button type="submit" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}

                    {{--<form method="POST" action="{{ route('purchase.all.pdf') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="product" class="form-control" value="{{ $product }}">--}}
                        {{--<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}

                    {{--<form method="POST" action="{{ route('purchase.all.excel') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="product" class="form-control" value="{{ $product }}">--}}
                        {{--<button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="card-body">
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Invoice</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Shipping Cost</th>
                            <th>Final Amount</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $purchase->date }}</td>
                                <td>{{ $purchase->supplier->name }}</td>
                                <td>{{ $purchase->invoice }}</td>
                                <td>{{ number_format($purchase->total_amount, 2) }}</td>
                                <td>{{ number_format($purchase->discount, 2) }}%</td>
                                <td>{{ number_format($purchase->shipping_cost, 2) }}</td>
                                <td>{{ number_format($purchase->final_amount, 2) }}</td>
                                <td>{{ $purchase->createdBy->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="report-text-right">Total</th>
                            <th>{{ number_format($total_amount, 2) }}</th>
                            <th></th>
                            {{--<th>{{ number_format($discount, 2) }}</th>--}}
                            <th>{{ number_format($shipping_cost, 2) }}</th>
                            <th>{{ number_format($final_amount, 2) }}</th>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection