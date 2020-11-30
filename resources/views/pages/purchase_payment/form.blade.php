<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="date" class="">Date <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="date" id="date" placeholder="Date" type="text" class="datepicker form-control @error('date') is-invalid @enderror" value="{{ old('date', optional($purchasePayment)->date ? date('Y-m-d', strtotime($purchasePayment->date)) : '') }}">
            @error('date')
            <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="supplier_id" class="">Supplier <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="supplier_id" id="supplier_id" class="select2 form-control @error('supplier_id') is-invalid @enderror" required>
                <option value="">Select</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @if (old('supplier_id', optional($purchasePayment)->supplier_id) == $supplier->id) selected @endif>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
            @error('supplier_id')
            <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="amount" class="">Amount <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="amount" id="amount" placeholder="amount" type="number" min="0" class=" form-control @error('amount') is-invalid @enderror" value="{{ old('amount', optional($purchasePayment)->amount) }}">
            @error('amount')
            <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="description" class="">Description</label>
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($purchasePayment)->description) }}</textarea>
            @error('description')
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