@extends('layouts.master')
@section('title', 'Stock Adjustment-Create')

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
                        Stock Adjustment
                        <div class="page-title-subheading">
                            Fields marked with asterisk must be filled.
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                        <i class="fa fa-plus"></i>
                    </button>
                    <div class="d-inline-block dropdown"></div>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form class="myForm needs-validation" method="POST" action="{{ route('stock-adjustment.store') }}" novalidate>
                    @csrf
                    @include('pages.stock_adjustment.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $("#adjustment_type").prop("disabled", true);
    $('#adjustment_class').on('change', function () {
        if ($('#adjustment_class').val() == 3 || $('#adjustment_class').val() == 4) {
            $("#adjustment_type").prop("disabled", false);
        } else {
            $("#adjustment_type").prop("disabled", true);
        }
    })
</script>
@endsection