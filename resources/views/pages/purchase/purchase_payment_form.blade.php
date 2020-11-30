<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="date" class="">Date <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="date" id="date" placeholder="Date" type="text" class="datepicker form-control @error('date') is-invalid @enderror" value="{{ old('date', optional($purchasePayment)->date ? date('Y-m-d', strtotime($purchasePayment->date)) : '') }}">
            <input type="hidden" name="purchase_id" value="{{ $id }}">
            <input type="hidden" name="supplier_id" value="{{ $supplier_id }}">
            <input type="hidden" name="due" value="{{ $due }}">
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
            <label for="amount" class="">Amount <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="amount" id="amount" placeholder="Amount" type="number" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', optional($purchasePayment)->amount) }}" min="0" step="any">
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