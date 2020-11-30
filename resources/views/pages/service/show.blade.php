@extends('layouts.master')
@section('title', 'Service-View')

@section('content')
<div class="app-main__outer">
	<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-graph text-success">
						</i>
					</div>
					<div>
                        View Service Detail
						<div class="page-title-subheading">
                            Description
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
            <div class="card-header">
                @if ((($service->total_amount - ($service->discount + $service->paid_amount)) > 0))
                    <a href="{{ route('service-payment.createFromService', $service->id) }}" title="Make Payment">
                        <button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-info">
                            <i class="pe-7s-piggy btn-icon-wrapper"></i>
                        </button>
                    </a>
                @endif
                <a href="{{ route('service.edit', $service->id) }}" title="Edit">
                    <button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary">
                        <i class="pe-7s-tools btn-icon-wrapper"></i>
                    </button>
                </a>
                {{--<form action="{{ route('service.destroy', $service->id) }}" method="POST" class="delete_form">--}}
                    {{--@csrf--}}
                    {{--@method('DELETE')--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-danger"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>--}}
                {{--</form>--}}
                <a href="{{ route('service.invoice.print', $service->id) }}" title="Print" target="_blank">
                    <button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">
                        <i class="pe-7s-print btn-icon-wrapper"></i>
                    </button>
                </a>
                {{--<a href="{{ route('service.pdf', $service->id) }}" title="PDF" target="_blank">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a> --}}
                {{--<a href="{{ route('service.excel', $service->id) }}" title="Excel" target="_blank">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-excel">--}}
                        {{--<i class="fa fa-file-excel-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>        --}}
            </div>
            <div class="card-body">
                <table style="width: 100%;" class="table table-hover table-striped table-bordered">
                	<tbody>
                        <tr>
                            <td>Date</td>
                            <td>{{ $service->date }}</td>
                            <td>Invoice</td>
                            <td>{{ $service->invoice }}</td>
                        </tr>
                		<tr>
                            <td>Patient</td>
                			<td>{{ $service->patient->name }}</td>
                            <td>Referred By Doctor</td>
                            <td>{{ $service->doctor_id ? $service->doctor->name : '--' }}</td>
                        </tr>
                        <tr>
                            <td>Total Amount</td>
                            <td>{{ $service->total_amount }}</td>
                            <td>Discount</td>
                            <td>{{ $service->discount }} %</td>
                        </tr>
                        <tr>
                            <td>Paid Amount</td>
                            <td>{{ $service->paid_amount }}</td>
                            <td>Delivery Date</td>
                            <td>{{ $service->delivery_date }}</td>
                        </tr>
                        <tr>
                            <td>Created By</td>
                            <td>{{ $service->createdBy->name }}</td>
                            @if($service->updated_by)
                                <td>Updated By</td>
                                <td>{{ $service->updatedBy->name }}</td>
                            @else
                                <td></td>
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Created At</td>
                            <td>{{ $service->created_at }}</td>
                            <td>Last Updated At</td>
                            <td>{{ $service->updated_at }}</td>
                        </tr>
                	</tbody>
                </table>
                <table style="width: 100%;" class="table table-hover table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>Remarks</td>
                            <td>{{ $service->description }}</td>
                        </tr>
                    </tbody>
                </table>

                <br>
                <h3>Tests</h3>
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Test</th>
                            <th>Cost</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($service->lists as $list)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $list->test->name }}</td>
                                <td>{{ $list->cost }}</td>
                                <td>{{ $list->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <br>
                <h3>Payments</h3>
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($service->payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->date }}</td>
                                <td>{{ $payment->invoice }}</td>
                                @if ($payment->payment_type == 1)
                                    <td>Initial</td>
                                @elseif ($payment->payment_type == 2)
                                    <td>Intermediate</td>
                                @else
                                    <td>Final</td>
                                @endif
                                <td>{{ $payment->amount }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('service-payment.edit', $payment->id) }}" title="Edit">
                                            <button class=" mr-2 btn-icon btn-icon-only btn btn-success">
                                                <i class="pe-7s-tools btn-icon-wrapper"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('service-payment.destroy', $payment->id) }}" method="POST" class="delete_form">
                                            @csrf
                                            @method('DELETE')
                                            <button class=" mr-2 btn-icon btn-icon-only btn btn-danger"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
         	</div>
		</div>
	</div>
</div>
@endsection