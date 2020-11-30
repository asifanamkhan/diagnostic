@extends('layouts.master')
@section('title', 'Setting')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-hammer text-success"></i>
                    </div>
                    <div>
                        Settings
                        <div class="page-title-subheading">
                            Fields marked with asterisk must be filled.
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
        <div class="row">
            <div class="col-md-6">
                <div class="main-card card">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="" id="img_a">
                                <img src="{{ asset('images/setting/'.$settings->logo) }}" id="img" class="img-thumbnail" height="300px" width="300px"  alt="image" />
                            </a>
                        </div>
                        <table style="width: 100%;" id="example" class="table table-hover mt-3 table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $settings->name }}</td>
                                </tr>
                                <tr>
                                    <th>Updated By</th>
                                    <td>
                                        @if ($settings->updated_by)
                                            {{ $settings->updatedBy->name }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Updated At</th>
                                    <td>{{ $settings->updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="main-card card">
                    <div class="card-body">
                        <form class="myForm needs-validation" method="POST" action="{{ route('setting.update', 1) }}" novalidate enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('pages.settings.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    //image preview
    function loadPreview(input, id) {
        $('#upload').show();
        id = id || '#upload';
        if (input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = function (e) {
                $(id).attr('src', e.target.result)
                .width(170)
                .height(160);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection