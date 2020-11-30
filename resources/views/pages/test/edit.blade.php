@extends('layouts.master')
@section('title', 'Test-Update')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-gleam text-success"></i>
                    </div>
                    <div>
                        Update Test Information
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
                <form class="myForm needs-validation" method="POST" action="{{ route('test.update', $test->id) }}" novalidate>
                    @csrf
                    @method('PUT')
                    @include('pages.test.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection