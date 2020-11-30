@extends('layouts.master')
@section('title', 'Purchase')

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
                        Purchases
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
                {{--<a href="{{ route('purchase.all.print') }}" title="Print" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('purchase.all.pdf') }}" title="PDF" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('purchase.all.excel') }}" title="Excel" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-excel">--}}
                        {{--<i class="fa fa-file-excel-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
            {{--</div>--}}

            <div class="card-body">

                <form class="myForm needs-validation" method="POST" action="{{ route('purchase.report') }}" novalidate>
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
                                <label for="product" class="">Product</label>
                                <select name="product" id="product" class="select2 form-control @error('product') is-invalid @enderror">
                                    <option value="">Select</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @if (old('product') == $product->id) selected @endif>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('product')
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
                                <label for="test" class="">.</label>
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
                            <th>Invoice</th>   
                            <th>Purchase Status</th>
                            <th>Supplier</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#spinner').hide();
    $('#dataTable').DataTable({
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "processing": true,
        "serverSide": true,
        "order": [[ 1, "desc" ]],
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
        },
        "ajax": {
            "url": "{{ route('getPurchaseList') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "date" },
            { "data": "invoice" },
            { "data": "purchase_status" },
            { "data": "supplier_id" },
            { "data": "final_amount" },
            { "data": "paid_amount" },
            { "data": "due_amount" },
            { "data": "actions" }
        ],
        'columnDefs': [{
            'targets': [5],
            'orderable': false
        }]
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