@extends('layouts.master')
@section('title', 'Product')

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
                        Products
                        <div class="page-title-subheading">
                            Product is the items used for test.
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
                {{--<a href="{{ route('product.all.print') }}" title="Print" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('product.all.pdf') }}" title="PDF" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('product.all.excel') }}" title="Excel" target="_blank" class="editable-link">--}}
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
                            <th>Image</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Stock</th>
                            <th width="18%">Actions</th>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div id="spinner" class="fa-3x text-center">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="text-center">
                        <a href="" id="img_a">
                            <img id="img" class="img-thumbnail" height="100px" width="120px" src="" alt="image" />
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-hover table-bordered mt-2">
                                <tbody>
                                    <tr>
                                        <th>Product Category</th>
                                        <td id="productCategoryId"></td>
                                    </tr>
                                    <tr>
                                        <th>Stock</th>
                                        <td id="product_stock"></td>
                                    </tr>
                                    <tr>
                                        <th>Used</th>
                                        <td id="product_used"></td>
                                    </tr>
                                    <tr>
                                        <th>Created By</th>
                                        <td id="product_created_by"></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td id="product_created_at"></td>
                                    </tr>
                                    <tr>
                                        <th>Unit</th>
                                        <td id="product_unit"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-hover table-bordered mt-2">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td id="product_name"></td>
                                    </tr>
                                    <tr>
                                        <th>Code</th>
                                        <td id="product_code"></td>
                                    </tr>
                                    <tr>
                                        <th>Purchased</th>
                                        <td id="product_purchased"></td>
                                    </tr>
                                    <tr>
                                        <th>Updated By</th>
                                        <td id="product_updated_by">--</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated At</th>
                                        <td id="product_updated_at"></td>
                                    </tr>
                                    <tr>
                                        <th>Alert Quantity</th>
                                        <td id="product_alert_quantity"></td>
                                    </tr>
                                </tbody>
                           </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-hover table-bordered">
                                <tbody>
                                <tr>
                                    <th>Expire Date</th>
                                    <td id="product_expire_date"></td>
                                </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td id="product_description"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
        },
        "ajax": {
            "url": "{{ route('getProductList') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "image" },
            { "data": "product_category_id" },
            { "data": "name" },
            { "data": "code" },
            { "data": "stock" },
            { "data": "actions" }
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
                $("#img").attr("src", 'images/product/' + response.image);
                $("#img_a").attr("href", 'images/product/' + response.image);
                $("#productCategoryId").html(response.category.name);
                $("#product_name").html(response.name);
                $("#product_code").html(response.code);
                $("#product_purchased").html(response.purchased);
                $("#product_used").html(response.used);
                $("#product_stock").html(response.stock);
                if(response.unit == 1 ){
                    $("#product_unit").html('Piece');
                }else{
                    $("#product_unit").html('Box');
                }
                $("#product_alert_quantity").html(response.alert_quantity);
                $("#product_description").html(response.description);
                $("#product_expire_date").html(response.expire_date);
                $("#product_created_by").html(response.created_by.name);
                if (response.updated_by) {
                    $("#product_updated_by").html(response.updated_by.name);
                }
                $("#product_created_at").html(response.created_at);
                $("#product_updated_at").html(response.updated_at);
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