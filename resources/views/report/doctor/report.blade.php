@extends('layouts.master')
@section('title', 'Doctor Report')

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
                        Doctor {{ $doctor->name }}'s Report
                        @if ($start_date && $end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @elseif ($start_date && !$end_date) 
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @else
                            from the beginning to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @endif
                        <div class="page-title-subheading">
                            Report of Doctor.
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
                    <span>Tests</span>
                </a>
            </li>
            <li class="nav-item">
                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                    <span>Payment</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                <div class="main-card mb-3 card">
                    {{--<div class="card-header">--}}
                        {{--<div class="print-pdf-excel">--}}
                            {{--<form method="POST" action="{{ route('doctor.report.print') }}" class="d-inline">--}}
                                {{--@csrf--}}
                                {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                                {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                                {{--<input type="hidden" name="doctor" class="form-control" value="{{ $doctor->id }}">--}}
                                {{--<button type="submit" class="btn btn-info"><i class="fa fa-print" aria-hidden="true"></i></button>--}}
                            {{--</form>--}}

                            {{--<form method="POST" action="{{ route('doctor.report.pdf') }}" class="d-inline">--}}
                                {{--@csrf--}}
                                {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                                {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                                {{--<input type="hidden" name="doctor" class="form-control" value="{{ $doctor->id }}">--}}
                                {{--<button type="submit" class="btn btn-danger"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>--}}
                            {{--</form>--}}

                            {{--<form method="POST" action="{{ route('doctor.report.excel') }}" class="d-inline">--}}
                                {{--@csrf--}}
                                {{--<input type="hidden" name="start_date" class="form-control" value="{{ $start_date }}">--}}
                                {{--<input type="hidden" name="end_date" class="form-control" value="{{ $end_date }}">--}}
                                {{--<input type="hidden" name="doctor" class="form-control" value="{{ $doctor->id }}">--}}
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
                                            <th>Test</th>
                                            <th>Cost</th>
                                            <th>Commission</th>
                                            <th>Payable</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($commissions as $commission)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $commission[0] }}</td>
                                                <td>{{ $commission[1] }}</td>
                                                <td>{{ $commission[2] }}</td>
                                                @if ($commission[3] == 1)
                                                    <td>{{ $commission[4] }} %</td>
                                                @else
                                                    <td>{{ $commission[4] }} <b>&#x9f3</b></td>
                                                @endif
                                                <td>{{ number_format($commission[5], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="report-text-right">Total</th>
                                            <th>{{ number_format($total, 2) }}</th>
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
                    <div class="card-body">
                        <div class="col-md-12">
                            <table style="width: 100%;" id="" class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl#</th>
                                        <th>Date</th>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                        <th width="60%">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($doctor_payments as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->date }}</td>
                                            <td>{{ $data->invoice }}</td>
                                            <td>{{ number_format($data->amount, 2) }}</td>
                                            <td>{{ $data->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="report-text-right">Total</td>
                                        <td>{{ number_format($doctor_total_paid, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection