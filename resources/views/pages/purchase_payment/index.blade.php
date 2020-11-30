@extends('layouts.master')
@section('title', 'Purchase Payment')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-money text-success"></i>
                    </div>
                    <div>
                        Purchase Payments
                        <div class="page-title-subheading"></div>
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
                {{--<a href="{{ route('purchase-payment.all.print') }}" title="Print" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('purchase-payment.all.pdf') }}" title="PDF" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('purchase-payment.all.excel') }}" title="Excel" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-excel">--}}
                        {{--<i class="fa fa-file-excel-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
            {{--</div>--}}

            <div class="card-body">

                <form class="myForm needs-validation" method="POST" action="{{ route('purchase-payment.report') }}" novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="start_date" class="">Start Date</label>
                                <input name="start_date" id="start_date" placeholder="Start Date" type="text" class="datepicker form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                @error('start_date')
                                <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="end_date" class="">End Date</label>
                                <input name="end_date" id="end_date" placeholder="End Date" type="text" class="datepicker form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                @error('end_date')
                                <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="position-relative form-group">
                                <label for="supplier" class="">Supplier</label>
                                <select name="supplier" id="supplier" class="select2 form-control @error('supplier') is-invalid @enderror">
                                    <option value="">Select</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @if (old('supplier') == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier')
                                <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="position-relative form-group">
                                <label for="" class="">.</label>
                                <div>
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                <table style="width: 100%;" id="dataTable" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Invoice</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade view-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div id="spinner" class="fa-3x text-center">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <table class="table table-hover mt-2">
                        <tbody>
                            <tr>
                                <th width="20%">Date</th>
                                <td id="payment_date"></td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td id="payment_supplier"></td>
                            </tr>
                            <tr>
                                <th>Invoice</th>
                                <td id="payment_invoice"></td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td id="payment_amount"></td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td id="payment_created_by"></td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td id="payment_updated_by">--</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td id="payment_created_at"></td>
                            </tr>
                            <tr>
                                <th>Last Updated At</th>
                                <td id="payment_updated_at"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Description</th>
                                <td id="payment_description"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gradient-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#dataTable').DataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "processing": true,
        "serverSide": true,
        "order": [[ 1, "desc" ]],
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
        },
        "ajax": {
            "url": "{{ route('getPurchasePaymentList') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "date" },
            { "data": "supplier_id" },
            { "data": "invoice" },
            { "data": "amount" },
            { "data": "actions" }
        ],
        'columnDefs': [{
            'targets': [5],
            'orderable': false
        }]
    });

    $('#dataTable').on('click', '.btn-show[data-remote]', function(e) {
        e.preventDefault();
        let url = $(this).data('remote');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#spinner').show();
            },
            success: function(response) {
                $("#payment_supplier").html(response.supplier.name);
                $("#payment_date").html(response.date);
                $("#payment_invoice").html(response.invoice);
                $("#payment_amount").html(response.amount);
                $("#payment_description").html(response.description);
                $("#payment_created_by").html(response.created_by.name);
                if (response.updated_by) {
                    $("#payment_updated_by").html(response.updated_by.name);
                }
                $("#payment_created_at").html(response.created_at);
                $("#payment_updated_at").html(response.updated_at);
                $('#spinner').hide();
            }
        });
    });

    $('#dataTable').on('click', '.btn-delete[data-remote]', function (e) {
        e.preventDefault();
        let url = $(this).data('remote');
        if (confirm('are you sure you want to delete this?')) {
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {submit: true, _method: 'delete', _token: "{{ csrf_token() }}"}
            }).always(function (data) {
                $('#dataTable').DataTable().draw(false);
            });
        }
    });
</script>
@endsection