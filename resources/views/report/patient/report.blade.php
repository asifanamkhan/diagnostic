@extends('layouts.master')
@section('title', 'Patient Report')

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
                        Patient {{ $patient->name }}'s Report
                        @if ($start_date && $end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @elseif ($start_date && !$end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @else
                            from the beginning to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @endif
                        <div class="page-title-subheading">
                            Report of Patient.
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
                    {{--<form method="POST" action="{{ route('patient.report.print') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="patient" class="form-control" value="{{ $patient->id }}">--}}
                        {{--<button type="submit" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}

                    {{--<form method="POST" action="{{ route('patient.report.pdf') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="patient" class="form-control" value="{{ $patient->id }}">--}}
                        {{--<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>--}}
                    {{--</form>--}}

                    {{--<form method="POST" action="{{ route('patient.report.excel') }}" class="d-inline">--}}
                        {{--@csrf--}}
                        {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                        {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                        {{--<input type="hidden" name="patient" class="form-control" value="{{ $patient->id }}">--}}
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
                            <th>Doctor</th>
                            <th>Tests</th>
                            <th>Total Amount</th>
                            <th>Discount</th>
                            <th>Paid Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $service->date }}</td>
                                <td><a target="_blank" title="click to view" href="{{route('service.show',$service->id)}}">{{ $service->invoice }}</a></td>
                                <td>{{ $service->doctor->name ?? '--' }}</td>
                                <td>
                                    @foreach ($service->lists as $list)
                                    {{ $loop->iteration }}. {{ $list->test->name }} <br>
                                    @endforeach
                                </td>
                                <td>{{ number_format($service->total_amount, 2) }}</td>
                                <td>{{ number_format($service->discount, 2) }}</td>
                                <td>{{ number_format($service->paid_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="report-text-right">Total</th>
                            <th>{{ number_format($total_amount, 2) }}</th>
                            <th>{{ number_format($discount, 2) }}</th>
                            <th>{{ number_format($paid_amount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection