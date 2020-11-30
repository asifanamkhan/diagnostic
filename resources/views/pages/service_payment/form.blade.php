<div class="form-row">
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="date" class="">Date <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="date" id="date" placeholder="Date" type="text" class="datepicker form-control @error('date') is-invalid @enderror" value="{{ old('date', optional($servicePayment)->date ? date('Y-m-d', strtotime($servicePayment->date)) : '') }}">
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            @error('date')
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
            <label for="payment_type" class="">Payment Type <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="payment_type" id="payment_type" class="form-control @error('payment_type') is-invalid @enderror" required>
                <option value="">Select</option>
                <option value="1" @if (old('payment_type', optional($servicePayment)->payment_type) == "1") selected @endif>Initial</option>
                <option value="2" @if (old('payment_type', optional($servicePayment)->payment_type) == "2") selected @endif>Intermediate</option>
                <option value="3" @if (old('payment_type', optional($servicePayment)->payment_type) == "3") selected @endif>Final</option>
            </select> 
            @error('payment_type')
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
            <label for="amount" class="">Amount <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="amount" id="amount" placeholder="Amount" type="number" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', optional($servicePayment)->amount) }}" min="0" step="any">
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
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($servicePayment)->description) }}</textarea>
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