@extends('layouts.master')
@section('title', 'Service Payment-Create')

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
                        Create New Service Payment
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
                <h5>Due Amount: {{ $service->total_amount - ($service->paid_amount + (($service->total_amount/100)*$service->discount)) }}</h5>
            </div>
            <div class="card-body">
                <form class="myForm needs-validation" method="POST" action="{{ route('service-payment.store') }}" novalidate>
                    @csrf
                    @include('pages.service_payment.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
