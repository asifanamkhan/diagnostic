@extends('layouts.master')
@section('title', 'Test')

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
                        Tests
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
                {{--<a href="{{ route('test.all.print') }}" title="Print" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('test.all.pdf') }}" title="PDF" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('test.all.excel') }}" title="Excel" target="_blank" class="editable-link">--}}
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
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Cost</th>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Test Details</h5>
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
                                <th width="20%">Code</th>
                                <td id="test_code"></td>
                            </tr>
                            <tr>
                                <th width="20%">Name</th>
                                <td id="test_name"></td>
                            </tr>
                            <tr>
                                <th>Test Category</th>
                                <td id="test_category"></td>
                            </tr>
                            
                            <tr>
                                <th>Cost</th>
                                <td id="test_cost"></td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td id="test_created_by"></td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td id="test_updated_by">--</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td id="test_created_at"></td>
                            </tr>
                            <tr>
                                <th>Last Updated At</th>
                                <td id="test_updated_at"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Description</th>
                                <td id="test_description"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <br>
                <h5>Commission for Doctors</h5>
                <table class="table table-hover table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Commission Type</th>
                            <th>Commission</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody id="records_table">
                    </tbody>
                </table>
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
            "url": "{{ route('getTestList') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "code" },
            { "data": "name" },
            { "data": "test_category_id" },
            { "data": "cost" },
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
            url: url, type: 'GET', dataType: 'json',
            beforeSend: function() {
                $('#spinner').show();
            },
            success: function(response) {
                $("#test_category").html(response.category.name);
                $("#test_name").html(response.name);
                $("#test_code").html(response.code);     
                $("#test_cost").html(response.cost);
                $("#test_description").html(response.description);
                $("#test_created_by").html(response.created_by.name);
                if (response.updated_by) {
                    $("#test_updated_by").html(response.updated_by.name);
                }
                $("#test_created_at").html(response.created_at);
                $("#test_updated_at").html(response.updated_at);
                $('#records_table').html('');
                let trHTML = '';
                $.each(response.commissions, function (i, item) {
                    let type;
                    if (item.pivot.commission_type == 1) {
                        type = "Percentage";
                    } else {
                        type = "Fixed Amount";
                    }
                    trHTML += '<tr><td>' + item.name + '</td><td>' + type + '</td><td>' + item.pivot.commission + '</td><td>' + item.pivot.description + '</td></tr>';
                });
                $('#records_table').append(trHTML);
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