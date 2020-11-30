@extends('layouts.master')
@section('title', 'Doctor')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-user-md text-success"></i>
                    </div>
                    <div>
                        Doctors
                        <div class="page-title-subheading">
                            A doctor is someone who maintains or restores human health through the practice of medicine.
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
                {{--<a href="{{ route('doctor.all.print') }}" title="Print" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('doctor.all.pdf') }}" title="PDF" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('doctor.all.excel') }}" title="Excel" target="_blank" class="editable-link">--}}
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
                            <th>Code</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Specialty</th>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Doctor Details</h5>
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
                    <table class="table table-hover table-bordered mt-2">
                        <tbody>
                            <tr>
                                <th>Code</th>
                                <td id="doctor_code"></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td id="doctor_name"></td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td id="doctor_mobile"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="doctor_email"></td>
                            </tr>
                            <tr>
                                <th>Specialty</th>
                                <td id="doctor_sprciality"></td>
                            </tr>
                            <tr>
                                <th>Qualification</th>
                                <td id="doctor_qualification"></td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td id="doctor_created_by"></td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td id="doctor_updated_by">--</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td id="doctor_created_at"></td>
                            </tr>
                            <tr>
                                <th>Last Updated At</th>
                                <td id="doctor_updated_at"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Address</th>
                                <td id="doctor_address"></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td id="doctor_description"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br>
                <h5>Commission for Tests</h5>
                <table class="table table-hover table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>Test</th>
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
            "url": "{{ route('getDoctorList') }}",
            "dataType": "json",
            "type": "POST",
            "data": {
                _token: "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "image" },
            { "data": "code" },
            { "data": "name" },
            { "data": "mobile" },
            { "data": "specialty" },
            { "data": "actions" }
        ],
        'columnDefs': [{
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
                $("#img").attr("src", 'images/doctor/' + response.image);
                $("#img_a").attr("href", 'images/doctor/' + response.image);
                $("#doctor_name").html(response.name);
                $("#doctor_code").html(response.code);
                $("#doctor_mobile").html(response.mobile);
                $("#doctor_email").html(response.email);
                $("#doctor_address").html(response.address);
                $("#doctor_sprciality").html(response.specialty);
                $("#doctor_qualification").html(response.qualification);
                $("#doctor_description").html(response.description);
                $("#doctor_created_by").html(response.created_by.name);
                if (response.updated_by) {
                    $("#doctor_updated_by").html(response.updated_by.name);
                }
                $("#doctor_created_at").html(response.created_at);
                $("#doctor_updated_at").html(response.updated_at);
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