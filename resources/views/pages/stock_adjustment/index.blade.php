@extends('layouts.master')
@section('title', 'Stock Adjustment')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-server text-success"></i>
                    </div>
                    <div>
                        Stock Adjustments
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
                {{--<a href="{{ route('stock-adjustment.all.print') }}" title="Print" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('stock-adjustment.all.pdf') }}" title="PDF" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('stock-adjustment.all.excel') }}" title="Excel" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-excel">--}}
                        {{--<i class="fa fa-file-excel-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
            {{--</div>--}}

            <div class="card-body">
                <table style="width: 100%;" id="dataTable" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Adjustment Class</th>
                            <th>Adjustment Type</th>
                            <th>Adjusted Quantity</th>
                            <th width="10%">Actions</th>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Stock Adjustment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div id="spinner" class="fa-3x text-center">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <table class="table table-hover table-bordered mt-2">
                        <tbody>
                            <tr>
                                <th>Date</th>
                                <td id="adjustment_date"></td>
                            </tr>
                            <tr>
                                <th>Product</th>
                                <td id="adjustment_product"></td>
                            </tr>
                            <tr>
                                <th>Adjustment Class</th>
                                <td id="adjustment_class"></td>
                            </tr>
                            <tr>
                                <th>Adjustment Type</th>
                                <td id="adjustment_type"></td>
                            </tr>
                            <tr>
                                <th>Previous Quantity</th>
                                <td id="adjustment_prev_quantity"></td>
                            </tr>
                            <tr>
                                <th>Adjusted Quantity</th>
                                <td id="adjusted_quantity"></td>
                            </tr>
                            <tr>
                                <th>After Quantity</th>
                                <td id="adjustment_after_quantity"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Created By</th>
                                <td id="adjustment_created_by"></td>
                            </tr>

                            <tr>
                                <th>Updated By</th>
                                <td id="adjustment_updated_by">--</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td id="adjustment_created_at"></td>
                            </tr>

                            <tr>
                                <th>Updated At</th>
                                <td id="adjustment_updated_at"></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td id="adjustment_description"></td>
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
            "url": "{{ route('getAdjustmentList') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "date" },
            { "data": "product_id" },
            { "data": "adjustment_class" },
            { "data": "adjustment_type" },
            { "data": "adjusted_quantity" },
            { "data": "actions" },
        ],
        'columnDefs': [ {
            'targets': [6],
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
                $("#adjustment_product").html(response.product.name);
                $("#adjustment_date").html(response.date);
                if (response.adjustment_class == 1) {
                    $("#adjustment_class").html("Uses");
                } else if (response.adjustment_class == 2) {
                    $("#adjustment_class").html("Wastage");
                } else if (response.adjustment_class == 3) {
                    $("#adjustment_class").html("Adjustment");
                } else {
                    $("#adjustment_class").html("Others");
                }

                if (response.adjustment_type == 1) {
                    $("#adjustment_type").html("Addition");
                } else if (response.adjustment_type == 2) {
                    $("#adjustment_type").html("Subtraction");
                } else{
                    $("#adjustment_type").html("--");
                }

                $("#adjustment_prev_quantity").html(response.prev_quantity);
                $("#adjusted_quantity").html(response.adjusted_quantity);
                $("#adjustment_after_quantity").html(response.after_quantity);
                $("#adjustment_description").html(response.description);
                $("#adjustment_created_by").html(response.created_by.name);
                if (response.updated_by) {
                    $("#adjustment_updated_by").html(response.updated_by.name);
                }
                $("#adjustment_created_at").html(response.created_at);
                $("#adjustment_updated_at").html(response.updated_at);
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