@extends('layouts.master')
@section('title', 'Suppliers')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-users text-success"></i>
                    </div>
                    <div>
                        Suppliers
                        <div class="page-title-subheading">
                            Supplier is a person or entity that is the source for goods or services.
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
                {{--<a href="{{ route('supplier.all.print') }}" title="Print" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('supplier.all.pdf') }}" title="PDF" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('supplier.all.excel') }}" title="Excel" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-excel">--}}
                        {{--<i class="fa fa-file-excel-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
            {{--</div>--}}

            <div class="card-body">
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th width="25%">Name</th>
                            <th width="10%">Code</th>
                            <th width="20%">Mobile</th>
                            <th width="25%">Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td >{{ $supplier->name }}</td>
                                <td>{{ $supplier->code }}</td>
                                <td >{{ $supplier->mobile }}</td>
                                <td >{{ $supplier->email }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="mr-2 btn-icon btn-icon-only btn btn-primary btn-show" data-remote=" {{ route('supplier.show', $supplier->id) }}" data-toggle="modal" data-target=".view-modal" title="View">
                                            <i class="pe-7s-note2 btn-icon-wrapper"></i>
                                        </button>
                                        <a href="{{ route('supplier.edit', $supplier->id) }}" title="Edit">
                                            <button class=" mr-2 btn-icon btn-icon-only btn btn-success">
                                                <i class="pe-7s-tools btn-icon-wrapper"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="delete_form">
                                            @csrf
                                            @method('DELETE')
                                            <button class=" mr-2 btn-icon btn-icon-only btn btn-danger">
                                                <i class="pe-7s-trash btn-icon-wrapper"></i>
                                            </button>
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

@section('modal')
<div class="modal fade view-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Supplier Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <table class="table table-hover mt-2">
                        <tbody>
                            <tr>
                                <th width="20%">Code</th>
                                <td id="supplier_code"></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td id="supplier_name"></td>
                            </tr>
                            <tr>
                                <th>Company</th>
                                <td id="supplier_company"></td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td id="supplier_mobile"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="supplier_email"></td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td id="supplier_address"></td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td id="supplier_created_by"></td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td id="supplier_updated_by">--</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td id="supplier_created_at"></td>
                            </tr>
                            <tr>
                                <th>Last Updated At</th>
                                <td id="supplier_updated_at"></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td id="supplier_description"></td>
                            </tr>
                        </tbody>
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
    $('#example').on('click', '.btn-show[data-remote]', function(e) {
        e.preventDefault();
        let url = $(this).data('remote');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {},
            success: function(response) {
                $("#supplier_name").html(response.name);
                $("#supplier_company").html(response.company);
                $("#supplier_code").html(response.code);
                $("#supplier_mobile").html(response.mobile);
                $("#supplier_email").html(response.email);
                $("#supplier_address").html(response.address);
                $("#supplier_description").html(response.description);
                $("#supplier_created_by").html(response.created_by.name);
                if (response.updated_by) {
                    $("#supplier_updated_by").html(response.updated_by.name);
                }
                $("#supplier_created_at").html(response.created_at);
                $("#supplier_updated_at").html(response.updated_at);
            }
        });
    });
</script>
@endsection