@extends('layouts.master')
@section('title', 'Product Update')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-diamond text-success"></i>
                    </div>
                    <div>
                        Update Product Information
                        <div class="page-title-subheading">
                            Fields marked with asterisk must be filled.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                        <i class="fa fa-pencil-square-o"></i>
                    </button>
                    <div class="d-inline-block dropdown"></div>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form class="myForm needs-validation" method="POST" action="{{ route('product.update',  $product->id) }}" novalidate enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('pages.product.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#upload').hide();
    function loadPreview(input, id) {
        $('#upload').show();
        $('#showImage').hide();
        id = id || '#upload';
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $(id).attr('src', e.target.result)
                .width(200)
                .height(160);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection