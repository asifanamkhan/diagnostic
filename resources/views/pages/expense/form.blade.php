<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="date" class="">Date <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="date" id="date" placeholder="Date" type="text" class="datepicker form-control @error('date') is-invalid @enderror" value="{{ old('date', optional($expense)->date ? date('Y-m-d', strtotime($expense->date)) : '') }}">
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
            <label for="expense_category_id" class="">Category <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="expense_category_id" id="expense_category_id" class="select2 form-control @error('expense_category_id') is-invalid @enderror" required>
                <option value="">Select</option>
                @foreach($expense_categories as $expense_category)
                    <option value="{{ $expense_category->id }}" @if (old('expense_category_id', optional($expense)->expense_category_id) == $expense_category->id) selected @endif>
                        {{ $expense_category->name }}
                    </option>
                @endforeach
            </select>
            @error('expense_category_id')
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
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="quantity" class="">Quantity</label>
            <input name="quantity" id="quantity" placeholder="Quantity" type="number" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', optional($expense)->quantity) }}" min="0" step="any">
            @error('quantity')
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
            <label for="rate" class="">Rate</label>
            <input name="rate" id="rate" placeholder="Rate" type="number" class="form-control @error('rate') is-invalid @enderror" value="{{ old('rate', optional($expense)->rate) }}" min="0" step="any">
            @error('rate')
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
            <input required name="amount" id="amount" placeholder="Amount" type="number" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', optional($expense)->amount ? $expense->getRawOriginal('amount') : 0) }}" min="0" step="any">
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
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($expense)->description) }}</textarea>
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