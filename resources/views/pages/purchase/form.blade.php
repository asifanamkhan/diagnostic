<div class="form-row">
    <div class="col-md-4">
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

    <div class="col-md-2">
        <div class="position-relative form-group">
            <label for="status" class="">Status <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select v-model="form.status" id="status" class="form-control" required >
                <option value="ordered" >Ordered</option>
                <option value="received" >Received</option>
            </select>
            <span class="is-invalid" v-show="status">
                <strong>Status Date is Invalid</strong>
            </span>
        </div>
    </div>

    <div class="col-md-5">
        <div class="position-relative form-group">
            <label for="supplier_id" class="">Supplier <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select v-model="form.supplier_id" id="supplier_id" class="form-control" required @change="changeSupplier($event)">
                <option :value="0">Select</option>
                <option :value="supplier.id" v-for="supplier in suppliers">
                    @{{ supplier.name }}
                </option>
            </select>
            <span class="is-invalid" v-show="invalid_supplier">
                <strong>Supplier is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-1">
        <button type="button" class="add-more-and-delete btn btn-info" data-toggle="modal" data-target=".view-modal" title="Add New Supplier" @click="openModal()">New</button>
    </div>
</div>
<div class="">
    <button type="button" class="btn btn-info" data-toggle="modal" data-target=".view-modal-product" title="Add New Product" @click="openModalProduct()">New Product</button>
</div>
<div class="form-row" v-for="(list, n) in form.lists" :key="n" :id="n">
    <div class="col-md-3">
        <div class="position-relative form-group">
            <label :for="'product_id' + n" class="">Product <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select v-model="list.product_id" :id="'product_id' + n" class="form-control" required ref="widgetSelect" @change="changeProduct($event, list)" >
                <option value="">Select</option>
                <option :value="product.id" v-for="product in products">
                    @{{ product.name }}
                </option>
                {{--@foreach($products as $product)--}}
                    {{--<option value="{{ $product->id }}" :selected="list.product_id == '{{ $product->id }}'">--}}
                        {{--{{ $product->name }}--}}
                    {{--</option>--}}
                {{--@endforeach--}}
            </select>
            <span class="is-invalid" v-show="list.product_invalid">
                <strong>Product is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <label :for="'quantity' + n" class="">Quantity</label>
            <input v-model="list.quantity" :id="'quantity' + n" placeholder="Quantity" type="number" class="form-control" min="0" step="any" required @input="changeTotal(list)">
            <span class="is-invalid" v-show="list.quantity_invalid">
                <strong>Quantity is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <label :for="'unit' + n" class="">Unit</label>
            <input :id="'unit' + n" placeholder="Unit" type="text" class="form-control" v-model="list.unit"  readonly >
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <label :for="'rate' + n" class="">Rate</label>
            <input :id="'rate' + n" placeholder="Rate" type="number" class="form-control" v-model="list.rate" min="0" step="any" required @input="changeTotal(list)">
            <span class="is-invalid" v-show="list.rate_invalid">
                <strong>Rate is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <label :for="'amount' + n" class="">Amount <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required :id="'amount' + n" placeholder="Amount" type="number" class="form-control" v-model="list.amount" min="0" step="any" readonly>
            <span class="is-invalid" v-show="list.amount_invalid">
                <strong>Amount is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-1">
        <div class="position-relative form-group">
            <button type="button" class="add-more-and-delete btn btn-info" @click="addRow()" v-if="n == 0">
                <i class="fa fa-plus"></i>
            </button>
            <button type="button" class="add-more-and-delete btn btn-danger" @click="deleteRow(n, list)" v-if="n != 0">
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
    <div class="col-md-2">
        <div class="position-relative form-group">
            <input required id="total_amount" placeholder="Total Amount" type="number" class="form-control" v-model="form.total_amount" min="0" step="any" readonly>
            <span class="is-invalid" v-show="form.total_amount_invalid">
                <strong>Total Amount is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>

<div class="form-row">
    <div class="col-md-7"></div>
    <div class="col-md-2">
        <label for="discount" class=""><strong>Discount (%)</strong></label>
    </div>
    <div class="col-md-2">
        <div class="position-relative form-group">
            <input id="discount" placeholder="Discount" type="number" class="form-control" v-model="form.discount" min="0" step="any" @input="finalAmount()">
            <span class="is-invalid" v-show="discount_invalid">
                <strong>Discount is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>

<div class="form-row">
    <div class="col-md-7"></div>
    <div class="col-md-2">
        <label for="shipping_cost" class=""><strong>Shipping Cost</strong></label>
    </div>
    <div class="col-md-2">
        <div class="position-relative form-group">
            <input id="shipping_cost" placeholder="Shipping Cost" type="number" class="form-control" v-model="form.shipping_cost" min="0" step="any" @input="finalAmount()">
            <span class="is-invalid" v-show="shipping_cost_invalid">
                <strong>Shipping Cost is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>

<div class="form-row">
    <div class="col-md-7"></div>
    <div class="col-md-2">
        <label for="final_amount" class=""><strong>Final Amount</strong> <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
    </div>
    <div class="col-md-2">
        <div class="position-relative form-group">
            <input required id="final_amount" placeholder="Final Amount" type="number" class="form-control" v-model="form.final_amount" min="0" step="any" readonly>
            <span class="is-invalid" v-show="final_amount_invalid">
                <strong>Final Amount is Invalid</strong>
            </span>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>
    <div class="col-md-1"></div>
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

<button class="mt-2 btn btn-primary" :disabled='isDisabled' @click="purchase">Submit</button>

{{-- had to use modal inside main container to work with vue --}}
<div class="modal fade view-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="addSupplierModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="myForm needs-validation" method="POST" action="{{ route('supplier.store') }}" novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <input required v-model="supplier.name" id="name" placeholder="Name" type="text" class="form-control">
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
                                <input required v-model="supplier.mobile" id="mobile" placeholder="Mobile" type="text" class="form-control">
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
                                <label for="email" class="">Email</label>
                                <input v-model="supplier.email" id="email" placeholder="Email" type="text" class="form-control">
                                <span class="is-invalid" v-show="invalid_email">
                                    <strong>Email is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="company" class="">Company <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <input required v-model="supplier.company" id="company" placeholder="Company" type="text" class="form-control">
                                <span class="is-invalid" v-show="invalid_name">
                                    <strong>Company is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="address" class="">Address</label>
                                <input v-model="supplier.address" id="address" placeholder="Address" type="text" class="form-control">
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
                                <label for="supplier_description" class="">Description</label>
                                <textarea v-model="supplier.description" class="description form-control" id="supplier_description" rows="5" placeholder="Enter Description"></textarea>
                                <span class="is-invalid" v-show="invalid_supplier_description">
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
                <button type="button" class="btn btn-gradient-primary" :disabled='isSupplierDisabled' @click="addSupplier">Submit</button>
                <button type="button" class="btn btn-gradient-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade view-modal-product" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="addProductModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="myForm needs-validation" method="POST" action="{{ route('product.store') }}" novalidate>
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name" class="">Category <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <select class="form-control" required v-model="product.product_category_id" >
                                    <option value="">Select</option>
                                    @foreach($products_category as $products_categori)
                                        <option value="{{ $products_categori->id }}" >
                                            {{ $products_categori->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="is-invalid" v-show="invalid_product_category_id">
                                    <strong>Product category is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <input required v-model="product.name"  placeholder="Product name" type="text" class="form-control">
                                <span class="is-invalid" v-show="invalid_product_name">
                                    <strong>Name is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="mobile" class="">Alert Quantity </label>
                                <input required v-model="product.alert_quantity" placeholder="Alert Quantity" type="text" class="form-control">
                                <span class="is-invalid" v-show="">
                                    <strong>Alert Quantity is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="mobile" class="">Expiry date </label>
                                <input required v-model="product.expire_date" type="date" class="form-control">
                                <span class="is-invalid" v-show="">
                                    <strong>Expiry date is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="unit"  class="">Unit <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                                <select class="form-control" v-model="product.unit" required >
                                    <option value="">Select</option>
                                    <option value="1">Piece</option>
                                    <option value="2">Box</option>
                                </select>
                                <span class="is-invalid"  v-show="invalid_unit">
                                    <strong>Unit is Invalid</strong>
                                </span>
                                <div class="valid-feedback">Looks Good!</div>
                                <div class="invalid-feedback">This Field is Required</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group">
                                <label for="supplier_description" class="">Description</label>
                                <textarea v-model="supplier.description" class="description form-control" id="supplier_description" rows="5" placeholder="Enter Description"></textarea>
                                <span class="is-invalid" v-show="invalid_supplier_description">
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
                <button type="button" class="btn btn-gradient-primary" :disabled='isProductDisabled' @click="addProduct">Submit</button>
                <button type="button" class="btn btn-gradient-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@section('script')
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
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
                supplierDisabled: true,
                productDisabled: true,
                invalid_date: false,
                invalid_status: false,
                invalid_supplier: false,
                invalid_description: false,
                total_amount_invalid: false,
                discount_invalid: false,
                shipping_cost_invalid: false,
                final_amount_invalid: false,
                invalid_name: false,
                invalid_mobile: false,
                invalid_email: false,
                invalid_address: false,
                invalid_supplier_description: false,
                invalid_product_category_id: false,
                invalid_product_name: false,
                invalid_unit: false,
                suppliers: @json($suppliers),
                products: @json($products),
                supplier: {
                    name: "",
                    mobile: "",
                    company: "",
                    email: "",
                    address: "",
                    description: ""
                },
                product: {
                    product_category_id: "",
                    name: "",
                    alert_quantity: "",
                    expire_date: "",
                    unit: "",
                    description: ""
                },
                form: {
                    date: "{{ $purchase ? date('Y-m-d', strtotime($purchase->date)) : '' }}",
                    supplier_id: "{{ $purchase->supplier_id ?? 0 }}",
                    status: "{{ $purchase->status ?? '' }}",
                    total_amount: "{{ $purchase->total_amount ?? 0 }}",
                    discount: "{{ $purchase->discount ?? 0 }}",
                    shipping_cost: "{{ $purchase->shipping_cost ?? 0 }}",
                    final_amount: "{{ $purchase->final_amount ?? 0 }}",
                    description: "{{ $purchase->description ?? '' }}",
                    lists: @json($lists),
                    deleted: []
                }
            }
        },
        beforeMount() {
            this.form.lists.map(object => {
                object.product_invalid = false;
                object.quantity_invalid = false;
                object.rate_invalid = false;
                object.amount_invalid = false;
                return object;
                //for some reason ES6 spread operator does not work here
                // return {...object, 
                //     'product_invalid': true,
                //     'quantity_invalid': true,
                //     'rate_invalid': true,
                //     'amount_invalid': true
                // };
            });
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
            })
        },
        computed: {
            isDisabled: function() {
                return !this.disabled;
            },
            isSupplierDisabled: function() {
                return !this.supplierDisabled;
            },
            isProductDisabled: function() {
                return !this.productDisabled;
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
                $('#addSupplierModal').appendTo("body");
            },
            addSupplier() {
                this.supplierDisabled = false;
                axios.post("{{ route('supplier.store') }}", this.supplier)
                    .then(response => {
                        this.suppliers = response.data[1];
                        this.form.supplier_id = response.data[0].id;
                        this.supplier.name = "";
                        this.supplier.mobile = "";
                        this.supplier.company = "";
                        this.supplier.email = "";
                        this.supplier.address = "";
                        this.supplier.description = "";
                        $("#addSupplierModal .close").click();
                        this.supplierDisabled = true;
                    })
                    .catch(error => {
                        if (error.response.data.errors.hasOwnProperty('name')) {
                            this.invalid_name = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('mobile')) {
                            this.invalid_mobile = true;
                        }

                        this.supplierDisabled = true;
                    });
            },
            openModalProduct(){
                $('#addProductModal').appendTo("body");
            },
            addProduct() {
                this.productDisabled = false;
                axios.post("{{ route('product.store') }}", this.product)
                    .then(response => {
                        console.log(response.data[1]);
                        this.products = response.data[1];
                        this.product.name = "";
                        this.product.product_category_id = "";
                        this.product.alert_quantity = "";
                        this.product.expire_date = "";
                        this.product.unit = "";
                        this.product.description = "";
                        $("#addProductModal .close").click();
                        this.productDisabled = true;
                    })
                    .catch(error => {
                        if (error.response.data.errors.hasOwnProperty('product_category_id')) {
                            this.invalid_product_category_id = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('name')) {
                            this.invalid_product_name = true;
                        }
                        if (error.response.data.errors.hasOwnProperty('unit')) {
                            this.invalid_unit = true;
                        }

                        this.productDisabled = true;
                    });
            },
            addRow(n) {
                this.form.lists.push({
                    'id' : 0,
                    'product_id' : '',
                    'unit':'',
                    'quantity' : 0,
                    'rate' : 0,
                    'amount' : 0,
                    'product_invalid' : false,
                    'quantity_invalid' : false,
                    'rate_invalid' : false,
                    'amount_invalid' : false
                });
            },
            deleteRow(index, list) {
                let idx = this.form.lists.indexOf(list);
                if (idx > -1) {
                    this.form.lists.splice(idx, 1);
                    this.form.deleted.push(list.id);
                }
                this.calculateTotal();
            },
            changeSupplier(event) {
                this.invalid_supplier = false;
            },
            changeProduct(event, list) {
                list.product_invalid = false;
                let product_id = event.target.value;
                let exists = this.form.lists.filter(p => p.product_id == product_id);
                if (exists.length > 1) {
                    list.product_id = "";          
                    Toast.fire({
                        icon: 'error',
                        title: 'You have Selected this Product Before'
                    });
                }
                let url = "{{ route('purchase.product.unit', 'product_id') }}";
                url = url.replace('product_id',product_id);
                axios.post(url)
                    .then(response => {
                    list.unit = response.data;
                });
            },
            changeTotal(list) {
                if (list.rate >= 0) {
                    list.rate_invalid = false;
                }

                if (list.quantity >= 0) {
                    list.quantity_invalid = false;
                }

                let total = parseFloat(list.rate) * parseFloat(list.quantity);
                if (!isNaN(total)) {
                    list.amount = total.toFixed(2);
                } else {
                    list.amount = 0;
                }
                this.calculateTotal();
            },
            calculateTotal() {
                let total = 0;
                $.each(this.form.lists, function(key, value) {
                    if (!isNaN(value.amount)) {
                        total = total + parseFloat(value.amount);
                    }
                });
                this.form.total_amount = total.toFixed(2);
                this.finalAmount();
            },
            finalAmount() {
                this.form.final_amount = ((this.form.total_amount - ( - this.form.shipping_cost)) -((this.form.total_amount/100))*this.form.discount).toFixed(2);
            },
            purchase() {
                this.disabled = false;
                if ("{{ $purchase }}") {
                    axios.put("{{ route('purchase.update', $purchase->id ?? 0) }}", this.form)
                        .then(response => {
                            Toast.fire({
                                icon: 'success',
                                title: 'Purchase Data Updated Successfully'
                            });
                            window.location = "{{ route('purchase.index') }}";
                        })
                        .catch(error => {
                            for (key in error.response.data.errors) {
                                if (key.includes('product_id')) {
                                    let index = key.slice(6, 7);
                                    this.form.lists[index].product_invalid = true;
                                }

                                if (key.includes('quantity')) {
                                    let index = key.slice(6, 7);
                                    this.form.lists[index].quantity_invalid = true;
                                }

                                if (key.includes('rate')) {
                                    let index = key.slice(6, 7);
                                    this.form.lists[index].rate_invalid = true;
                                }
                            }
                            if (error.response.data.errors.hasOwnProperty('date')) {
                                this.invalid_date = true;
                            }
                            if (error.response.data.errors.hasOwnProperty('supplier_id')) {
                                this.invalid_supplier = true;
                            }
                            if (error.response.data.errors.hasOwnProperty('discount')) {
                                this.discount_invalid = true;
                            }
                            if (error.response.data.errors.hasOwnProperty('shipping_cost')) {
                                this.shipping_cost_invalid = true;
                            }         
                            this.disabled = true;
                        });
                } else {
                    axios.post("{{ route('purchase.store') }}", this.form)
                        .then(response => {
                            Toast.fire({
                                icon: 'success',
                                title: 'Purchase Data Inserted Successfully'
                            });
                            window.location = "{{ route('purchase.index') }}";
                        })
                        .catch(error => {
                            for (key in error.response.data.errors) {
                                if (key.includes('product_id')) {
                                    let index = key.slice(6, 7);
                                    this.form.lists[index].product_invalid = true;
                                }

                                if (key.includes('quantity')) {
                                    let index = key.slice(6, 7);
                                    this.form.lists[index].quantity_invalid = true;
                                }

                                if (key.includes('rate')) {
                                    let index = key.slice(6, 7);
                                    this.form.lists[index].rate_invalid = true;
                                }
                            }
                            if (error.response.data.errors.hasOwnProperty('date')) {
                                this.invalid_date = true;
                            }
                            if (error.response.data.errors.hasOwnProperty('supplier_id')) {
                                this.invalid_supplier = true;
                            }
                            if (error.response.data.errors.hasOwnProperty('discount')) {
                                this.discount_invalid = true;
                            }
                            if (error.response.data.errors.hasOwnProperty('shipping_cost')) {
                                this.shipping_cost_invalid = true;
                            }         
                            this.disabled = true;
                        });
                }   
            }
        }
    });
</script>
@endsection