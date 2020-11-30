@extends('layouts.master')
@section('title', 'Service Report')

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
                        Service Report
                        @if ($prod)
                        of Test {{ $prod->name }}
                        @endif

                        @if ($start_date && $end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @elseif ($start_date && !$end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @else
                            from the beginning to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @endif
                        <div class="page-title-subheading">
                            Report of Services.
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
                    {{--<form method="POST" action="{{ route('service.all.print') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="test" class="form-control" value="{{ $test }}">--}}
                        {{--<button type="submit" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}

                    {{--<form method="POST" action="{{ route('service.all.pdf') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="test" class="form-control" value="{{ $test }}">--}}
                        {{--<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}

                    {{--<form method="POST" action="{{ route('service.all.excel') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="test" class="form-control" value="{{ $test }}">--}}
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
                            <th>Patient</th>
                            <th>Invoice</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Paid Amount</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $service->date }}</td>
                                <td>{{ $service->patient->name }}</td>
                                <td>{{ $service->invoice }}</td>
                                <td>{{ number_format($service->total_amount, 2) }}</td>
                                <td>{{ number_format($service->discount, 2) }}%</td>
                                <td>{{ number_format($service->paid_amount, 2) }}</td>
                                <td>{{ $service->createdBy->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="report-text-right">Total</th>
                            <th>{{ number_format($total_amount, 2) }}</th>
                            <th></th>
                            {{--<th>{{ number_format($discount, 2) }}</th>--}}
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