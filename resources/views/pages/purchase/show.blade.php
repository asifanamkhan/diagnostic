@extends('layouts.master')
@section('title', 'Purchase-View')

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
                        View Purchase Detail
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
                <a href="{{ route('purchase.edit', $purchase->id) }}" title="Edit">
                    <button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-primary">
                        <i class="pe-7s-tools btn-icon-wrapper"></i>
                    </button>
                </a>
                {{--<form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST" class="delete_form">--}}
                    {{--@csrf--}}
                    {{--@method('DELETE')--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-danger"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>--}}
                {{--</form>--}}
                {{--<a href="{{ route('purchase.print', $purchase->id) }}" title="Print" target="_blank">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('purchase.pdf', $purchase->id) }}" title="PDF" target="_blank">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a> --}}
                {{--<a href="{{ route('purchase.excel', $purchase->id) }}" title="Excel" target="_blank">--}}
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
                            <td>{{ $purchase->date }}</td>
                            <td>Invoice</td>
                            <td>{{ $purchase->invoice }}</td>
                        </tr>
                		<tr>
                            <td>Supplier</td>
                			<td>{{ $purchase->supplier->name }}</td>
                            <td>Amount</td>
                            <td>{{ $purchase->total_amount }}</td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td>{{ $purchase->discount }}%</td>
                            <td>Shipping Cost</td>
                            <td>{{ $purchase->shipping_cost }}</td>
                        </tr>
                        <tr>
                            <td>Final Amount</td>
                            <td>{{ $purchase->final_amount }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Created By</td>
                            <td>{{ $purchase->createdBy->name }}</td>
                            @if($purchase->updated_by)
                                <td>Updated By</td>
                                <td>{{ $purchase->updatedBy->name }}</td>
                            @else
                                <td></td>
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Created At</td>
                            <td>{{ $purchase->created_at }}</td>
                            <td>Last Updated At</td>
                            <td>{{ $purchase->updated_at }}</td>
                        </tr>
                	</tbody>
                </table>
                <table style="width: 100%;" class="table table-hover table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>Remarks</td>
                            <td>{{ $purchase->description }}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <h3>Products</h3>
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->lists as $list)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $list->product->name }}</td>
                                <td>{{ $list->quantity }}</td>
                                @if($list->product->unit == 1)
                                    <td>Piece</td>
                                @else
                                    <td>Box</td>
                                @endif
                                <td>{{ $list->rate }}</td>
                                <td>{{ $list->amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <h3>Payment History</h3>
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Sl#</th>
                        <th>Date</th>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchase_payment as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->date }}</td>
                            <td>{{ $payment->invoice }}</td>
                            <td>{{ $payment->amount }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('purchasePayment.edit', $payment->id) }}" title="Edit">
                                        <button class=" mr-2 btn-icon btn-icon-only btn btn-success">
                                            <i class="pe-7s-tools btn-icon-wrapper"></i>
                                        </button>
                                    </a>
                                    <a href="{{ route('purchasePayment.destroy', $payment->id) }}">
                                        <button class=" mr-2 btn-icon btn-icon-only btn btn-danger"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                    </a>
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