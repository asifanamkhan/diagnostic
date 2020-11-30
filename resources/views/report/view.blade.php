@extends('layouts.master')
@section('title', 'Report')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-money text-success"></i>
                    </div>
                    <div>
                        @if (\Route::currentRouteName() == 'expense.report.get')
                            Expense
                        @elseif (\Route::currentRouteName() == 'stock-adjustment.report.get')
                            Stock Adjustment
                        @elseif (\Route::currentRouteName() == 'purchase.report.get')
                            Purchase
                        @elseif (\Route::currentRouteName() == 'purchase-payment.report.get')
                            Purchase Payment
                        @elseif (\Route::currentRouteName() == 'supplier.report.get')
                            Supplier
                        @elseif (\Route::currentRouteName() == 'doctor.report.get')
                            Doctor
                        @elseif (\Route::currentRouteName() == 'patient.report.get')
                            Patient
                        @elseif (\Route::currentRouteName() == 'service.report.get')
                            Service
                        @elseif (\Route::currentRouteName() == 'commission.test.get' || \Route::currentRouteName() == 'commission.doctor.get')
                            Commission
                        @endif
                            Report
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
            <div class="card-body">
                {{--expense--}}
                @if (\Route::currentRouteName() == 'expense.report.get')


                {{--Stock Adjustment--}}
                @elseif (\Route::currentRouteName() == 'stock-adjustment.report.get')
                    <form class="myForm needs-validation" method="POST" action="{{ route('stock-adjustment.report') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="start_date" class="">Start Date</label>
                                    <input name="start_date" id="start_date" placeholder="Start Date" type="text" class="datepicker form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="end_date" class="">End Date</label>
                                    <input name="end_date" id="end_date" placeholder="End Date" type="text" class="datepicker form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="product" class="">Product</label>
                                    <select name="product" id="product" class="form-control @error('product') is-invalid @enderror">
                                        <option value="">Select</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" @if (old('product') == $product->id) selected @endif>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>
                        </div>
                        <button class="mt-2 btn btn-primary">Submit</button>
                    </form>

                {{--Commission--}}
                @elseif (\Route::currentRouteName() == 'commission.doctor.get')
                    <form class="myForm needs-validation" method="POST" action="{{ route('commission.doctor.report') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="doctor" class="">Doctor <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                    <select name="doctor" id="doctor" class="select2 form-control @error('doctor') is-invalid @enderror" required>
                                        <option value="">Select</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" @if (old('doctor') == $doctor->id) selected @endif>{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('doctor')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>
                        </div>
                        <button class="mt-2 btn btn-primary">Submit</button>
                    </form>

                @elseif (\Route::currentRouteName() == 'commission.test.get')
                    <form class="myForm needs-validation" method="POST" action="{{ route('commission.test.report') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="test" class="">Test <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                    <select name="test" id="test" class="select2 form-control @error('test') is-invalid @enderror" required>
                                        <option value="">Select</option>
                                        @foreach ($tests as $test)
                                            <option value="{{ $test->id }}" @if (old('test') == $test->id) selected @endif>{{ $test->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('test')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>
                        </div>
                        <button class="mt-2 btn btn-primary">Submit</button>
                    </form>

                {{--Supplier--}}
                @elseif (\Route::currentRouteName() == 'supplier.report.get')
                    <form class="myForm needs-validation" method="POST" action="{{ route('supplier.report') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="supplier" class="">Supplier <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                    <select name="supplier" id="supplier" class="form-control @error('supplier') is-invalid @enderror" required>
                                        <option value="">Select</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" @if (old('supplier') == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="start_date" class="">Start Date</label>
                                    <input  name="start_date" id="start_date" placeholder="Start Date" type="text" class="datepicker form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="end_date" class="">End Date</label>
                                    <input  name="end_date" id="end_date" placeholder="End Date" type="text" class="datepicker form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>
                        </div>
                        <button class="mt-2 btn btn-primary">Submit</button>
                    </form>

                {{--Service--}}
                @elseif (\Route::currentRouteName() == 'service.report.get')

                    <div class="main-card mb-3 card">
                        <div class="card-body">

                        </div>
                    </div>
                {{--Doctor--}}
                @elseif (\Route::currentRouteName() == 'doctor.report.get')
                    <form class="myForm needs-validation" method="POST" action="{{ route('doctor.report') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="doctor" class="">Doctor <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                    <select name="doctor" id="doctor" class="form-control @error('doctor') is-invalid @enderror" required>
                                        <option value="">Select</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" @if (old('doctor') == $doctor->id) selected @endif>{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('doctor')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="start_date" class="">Start Date</label>
                                    <input  name="start_date" id="start_date" placeholder="Start Date" type="text" class="datepicker form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="end_date" class="">End Date</label>
                                    <input  name="end_date" id="end_date" placeholder="End Date" type="text" class="datepicker form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>
                        </div>
                        <button class="mt-2 btn btn-primary">Submit</button>
                    </form>

                {{--Patient--}}
                @elseif (\Route::currentRouteName() == 'patient.report.get')
                    <form class="myForm needs-validation" method="POST" action="{{ route('patient.report') }}" novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="patient" class="">Patient <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                    <select name="patient" id="patient" class="select2 form-control @error('doctor') is-invalid @enderror" required>
                                        <option value="">Select</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}" @if (old('patient') == $patient->id) selected @endif>{{ $patient->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('patient')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="start_date" class="">Start Date</label>
                                    <input name="start_date" id="start_date" placeholder="Start Date" type="text" class="datepicker form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="end_date" class="">End Date</label>
                                    <input name="end_date" id="end_date" placeholder="End Date" type="text" class="datepicker form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <span class="is-invalid">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks Good!</div>
                                    <div class="invalid-feedback">This Field is Required</div>
                                </div>
                            </div>
                        </div>
                        <button class="mt-2 btn btn-primary">Submit</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
