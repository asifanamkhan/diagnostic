@extends('layouts.master')
@section('title', 'Doctor Commission Report')

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
                        Doctor {{ $doctor->name }}'s Commission Report
                        <div class="page-title-subheading">
                            Commission Report
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
                    {{--<a href="{{ route('commission.doctor.print', $doctor->id) }}" title="Print" target="_blank">--}}
                        {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                            {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                        {{--</button>--}}
                    {{--</a>--}}

                    {{--<a href="{{ route('commission.doctor.pdf', $doctor->id) }}" title="PDF" target="_blank" class="editable-link">--}}
                        {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                            {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                        {{--</button>--}}
                    {{--</a>--}}
                    {{--<a href="{{ route('commission.doctor.excel', $doctor->id) }}" title="Excel" target="_blank" class="editable-link">--}}
                        {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-excel">--}}
                            {{--<i class="fa fa-file-excel-o btn-icon-wrapper"></i>--}}
                        {{--</button>--}}
                    {{--</a>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="card-body">
                <table style="width: 100%;" id="" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Test</th>
                            <th>Commission Type</th>
                            <th>Commission</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commissions as $commission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $commission->name }}</td>
                                @if ($commission->pivot->commission_type == 1)
                                     <td>Percent</td>
                                @else
                                    <td>Amount</td>
                                @endif
                                <td>{{ $commission->pivot->commission }}</td>
                                <td>{{ date('d M, Y', strtotime($commission->pivot->created_at)) }}</td>
                                <td>{{ date('d M, Y', strtotime($commission->pivot->updated_at)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection