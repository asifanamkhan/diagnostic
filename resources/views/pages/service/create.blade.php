@extends('layouts.master')
@section('title', 'Service-Create')

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
                        Create New Service
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
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group text-center row">
                            <label class="col-form-label col-md-12 mb-3"><b>Tests</b></label>
                            <input v-model="searchTest" type="text" placeholder="Search" class="col-md-10 searchBox">
                            <div class="col-md-12 mt-4 text-left">
                                <ul class="product_list_ul">
                                    <li class="col-md-6 product_list_li active" v-for="test in filterStates" @click="addRow(test)">
                                        @{{ test.category.name }}, @{{ test.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <form class="myForm needs-validation" method="POST" action="{{ route('service.store') }}" novalidate>
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="date" class="">Date <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                        <input required id="date" placeholder="Date" type="text" class="form-control" v-model="form.date">
                                        <span class="is-invalid" v-show="invalid_date">
                                            <strong>Date is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="position-relative form-group">
                                        <label for="patient_id" class="">Patient <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                        <select v-model="form.patient_id" id="patient_id" class="form-control" required @change="changePatient($event)">
                                            <option :value="0">Select</option>
                                            <option :value="patient.id" v-for="patient in patients">
                                                @{{ patient.name }}
                                            </option>
                                            {{-- @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}" :selected="form.patient_id == '{{ $patient->id }}'">
                                                    {{ $patient->name }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                        <span class="is-invalid" v-show="invalid_patient">
                                            <strong>Patient is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <button type="button" class="add-more-and-delete btn btn-info" data-toggle="modal" data-target=".view-modal" title="Add New Patient" @click="openModal()">New</button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label for="delivery_date" class="">Delivery Date <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                        <input required id="delivery_date" placeholder="Delivery Date" type="text" class="form-control" v-model="form.delivery_date">
                                        <span class="is-invalid" v-show="invalid_delivery_date">
                                            <strong>Delivery Date is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label for="status" class="">Status <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                        <select v-model="form.status" id="status" class="form-control" required >
                                            <option value="pending" >Pending</option>
                                            <option value="complete" >complete</option>
                                        </select>
                                        <span class="is-invalid" v-show="status">
                                            <strong>Status Date is Invalid</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doctor_id" class="">Referred By Doctor</label>
                                        <select v-model="form.doctor_id" id="doctor_id" class="form-control" required @change="changeDoctor($event)">
                                            <option :value="0">Select</option>
                                            <option :value="doctor.id" v-for="doctor in doctors">
                                                @{{ doctor.name }}
                                            </option>
                                            {{-- @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" :selected="form.doctor_id == '{{ $doctor->id }}'">
                                                    {{ $doctor->name }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                        <span class="is-invalid" v-show="invalid_doctor">
                                            <strong>Doctor is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>
                            </div>

                            <span class="is-invalid" v-show="invalid_test">
                                <strong>At Least one Test is Required</strong>
                            </span>
                            <div class="form-row" v-for="(list, n) in form.lists" :key="n" :id="n">
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <label :for="'test_id' + n" class="">Test</label>
                                        <input type="text" v-model="list.test_name" :id="'test_name' + n" class="form-control" readonly>
                                        <input type="hidden" v-model="list.test_id" :id="'test_id' + n" class="form-control">
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label :for="'cost' + n" class="">Cost</label>
                                        <input v-model="list.cost" :id="'cost' + n" placeholder="Cost" type="number" class="form-control" min="0" step="any" readonly @input="calculateTotal()">
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="position-relative form-group">
                                        <label :for="'list_description' + n" class="">Description</label>
                                        <input :id="'list_description' + n" placeholder="Description" type="text" class="form-control" v-model="list.list_description">
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="position-relative form-group">
                                        <button type="button" class="add-more-and-delete btn btn-danger" @click="deleteRow(n, list)">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-7"></div>
                                <div class="col-md-2">
                                    <label for="total_amount" class=""><strong>Total Amount</strong> <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <input required id="total_amount" placeholder="Total Amount" type="number" class="form-control" v-model="form.total_amount" min="0" step="any" readonly>
                                        <span class="is-invalid" v-show="form.total_amount_invalid">
                                            <strong>Total Amount is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-7"></div>
                                <div class="col-md-2">
                                    <label for="discount" class=""><strong>Discount (%)</strong></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <input id="discount" placeholder="Discount" type="number" class="form-control" v-model="form.discount" min="0" step="any" @input="dueAmount()">
                                        <span class="is-invalid" v-show="discount_invalid">
                                            <strong>Discount is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-7"></div>
                                <div class="col-md-2">
                                    <label for="paid_amount" class=""><strong>Advance Paid</strong></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <input id="paid_amount" placeholder="Advance Paid" type="number" class="form-control" v-model="form.paid_amount" min="0" step="any" @input="dueAmount()">
                                        <span class="is-invalid" v-show="paid_amount_invalid">
                                            <strong>Advance Paid is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-7"></div>
                                <div class="col-md-2">
                                    <label for="due" class=""><strong>Due Amount</strong> <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <input required id="due" placeholder="Due Amount" type="number" class="form-control" v-model="form.due" min="0" step="any" readonly>
                                        <span class="is-invalid" v-show="due_invalid">
                                            <strong>Due Amount is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="description" class="">Description</label>
                                        <textarea v-model="form.description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description"></textarea>
                                        <span class="is-invalid" v-show="invalid_description">
                                            <strong>Description is Invalid</strong>
                                        </span>
                                        <div class="valid-feedback">Looks Good!</div>
                                        <div class="invalid-feedback">This Field is Required</div>
                                    </div>
                                </div>
                            </div>

                            <button class="mt-2 btn btn-primary" :disabled='isDisabled' @click="service">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- had to use modal inside main container to work with vue --}}
<div class="modal fade view-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="addPtientModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="myForm needs-validation" method="POST" action="{{ route('patient.store') }}" novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <input required v-model="patient.name" id="name" placeholder="Name" type="text" class="form-control">
                                <span class="is-invalid" v-show="invalid_name">
                                    <strong>Name is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="mobile" class="">Mobile <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <input required v-model="patient.mobile" id="mobile" placeholder="Mobile" type="text" class="form-control">
                                <span class="is-invalid" v-show="invalid_mobile">
                                    <strong>Mobile is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="gender" class="">Gender <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <select v-model="patient.gender" id="gender" class="form-control" required>
                                    <option :value=0>Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="others">Other</option>
                                </select> 
                                <span class="is-invalid" v-show="invalid_gender">
                                    <strong>Gender is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="age" class="">Age <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <input required v-model="patient.age" id="age" placeholder="Age" type="number" class="form-control" min="0" step="any">
                                <span class="is-invalid" v-show="invalid_age">
                                    <strong>Age is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="email" class="">Email</label>
                                <input v-model="patient.email" id="email" placeholder="Email" type="text" class="form-control">
                                <span class="is-invalid" v-show="invalid_email">
                                    <strong>Email is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="address" class="">Address</label>
                                <input v-model="patient.address" id="address" placeholder="Address" type="text" class="form-control">
                                <span class="is-invalid" v-show="invalid_address">
                                    <strong>Address is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="patient_description" class="">Description</label>
                                <textarea v-model="patient.description" class="description form-control" id="patient_description" rows="5" placeholder="Enter Description"></textarea>
                                <span class="is-invalid" v-show="invalid_patient_description">
                                    <strong>Description is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-gradient-primary" :disabled='isPatientDisabled' @click="addPatient">Submit</button>
                <button type="button" class="btn btn-gradient-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
<style type="text/css">
    .searchBox {
        border: none;
        outline: none;
        float: left;
        overflow: hidden;
        border-bottom: 1px solid black;
    }
    li { cursor: pointer; }
</style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<script>
    axios.defaults.trailingSlash = true;
    Vue.config.devtools = true;
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    let app = new Vue({
        el:"#app",
        data() {
            return {
                disabled: true,
                patientDisabled: true,
                invalid_test: false,
                invalid_date: false,
                invalid_status: false,
                invalid_patient: false,
                invalid_description: false,
                total_amount_invalid: false,
                discount_invalid: false,
                paid_amount_invalid: false,
                due_invalid: false,
                invalid_delivery_date: false,
                invalid_doctor: false,
                invalid_name: false,
                invalid_mobile: false,
                invalid_age: false,
                invalid_gender: false,
                invalid_email: false,
                invalid_address: false,
                invalid_patient_description: false,
                searchTest: "",
                patients: @json($patients),
                doctors: @json($doctors),
                tests: @json($tests),
                patient: {
                    name: "",
                    mobile: "",
                    age: 0,
                    gender: 0,
                    email: "",
                    address: "",
                    description: ""
                },
                form: {
                    date: "",
                    delivery_date: "",
                    status: "",
                    patient_id: 0,
                    doctor_id: 0,
                    total_amount: 0,
                    discount: 0,
                    paid_amount: 0,
                    due: 0,
                    description: "",
                    lists: []
                }
            }
        },
        mounted() {
            let vm = this;
            $('#date').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText) {
                    vm.form.date = dateText;
                    vm.invalid_date = false;
                }
            });

            $("#delivery_date").datetimepicker({
                format: 'yyyy-mm-dd hh:ii:ss',
                autoclose: true,
                minuteStep: 5
            })
            .on('changeDate', function(ev) {
                vm.form.delivery_date = new Date(ev.date.getTime() - (ev.date.getTimezoneOffset() * 60000)).toISOString().slice(0, 19).replace('T', ' ');
                vm.invalid_delivery_date = false;
            });
        },
        computed: {
            filterStates() {
                return this.tests.filter(test => {
                    return test.name.toLowerCase().match(this.searchTest.toLowerCase()) || test.category.name.toLowerCase().match(this.searchTest.toLowerCase());
                });
            },
            isDisabled: function() {
                return !this.disabled;
            },
            isPatientDisabled: function() {
                return !this.patientDisabled;
            }
        },
        watch: {
            async select2Widget() {
                await this.$nextTick();
                $(this.$refs.widgetSelect).select2();
            }
        },
        methods: {
            openModal() {
                $('#addPtientModal').appendTo("body");
            },
            addPatient() {
                this.patientDisabled = false;
                axios.post("{{ route('patient.store') }}", this.patient)
                    .then(response => {
                        this.patients = response.data[1];
                        this.form.patient_id = response.data[0].id;
                        this.patient.name = "";
                        this.patient.mobile = "";
                        this.patient.age = 0;
                        this.patient.gender = 0;
                        this.patient.email = "";
                        this.patient.address = "";
                        this.patient.description = "";
                        $("#addPtientModal .close").click()
                        this.patientDisabled = true;
                    })
                    .catch(error => {
                        if (error.response.data.errors.hasOwnProperty('name')) {
                            this.invalid_name = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('mobile')) {
                            this.invalid_mobile = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('age')) {
                            this.invalid_age = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('gender')) {
                            this.invalid_gender = true;
                        }         
                        this.patientDisabled = true;
                    });
            },
            addRow(test) {
                let exists = this.form.lists.filter(p => p.test_id == test.id);
                if (exists.length > 0) {
                    Toast.fire({
                        icon: 'error',
                        title: 'You Have Selected this Test Before'
                    });
                } else {
                    this.form.lists.push({
                        'test_id' : test.id,
                        'test_name' : test.name,
                        'cost' : test.cost,
                        'list_description' : ''
                    });
                    this.invalid_test = false;
                    this.calculateTotal();
                }
            },
            deleteRow(index, list) {
                let idx = this.form.lists.indexOf(list);
                if (idx > -1) {
                    this.form.lists.splice(idx, 1);
                }
                this.calculateTotal();
            },
            changePatient(event) {
                this.invalid_patient = false;
            },
            calculateTotal() {
                let total = 0;
                $.each(this.form.lists, function(key, value) {
                    if (!isNaN(value.cost)) {
                        total = total + parseFloat(value.cost);
                    }
                });
                this.form.total_amount = total.toFixed(2);
                this.dueAmount();
            },
            dueAmount() {
                this.form.due = (this.form.total_amount - (((this.form.total_amount/100)*this.form.discount) - ( - this.form.paid_amount))).toFixed(2);
            },
            service() {
                this.disabled = false;
                axios.post("{{ route('service.store') }}", this.form)
                    .then(response => {
                        Toast.fire({
                            icon: 'success',
                            title: 'Service Data Inserted Successfully'
                        });
                        window.location = "{{ route('service.index') }}";
                    })
                    .catch(error => {
                        if (error.response.data.errors.hasOwnProperty('date')) {
                            this.invalid_date = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('delivery_date')) {
                            this.invalid_delivery_date = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('patient_id')) {
                            this.invalid_patient = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('lists')) {
                            this.invalid_test = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('discount')) {
                            this.discount_invalid = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('paid_amount')) {
                            this.paid_amount_invalid = true;
                        }         
                        this.disabled = true;
                    });  
            }
        }
    });
</script>
@endsection
