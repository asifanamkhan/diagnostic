<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="date" class="">Date <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="date" id="date" placeholder="Date" type="text" class="datepicker form-control @error('date') is-invalid @enderror" value="{{ old('date', optional($stockAdjustment)->date ? date('Y-m-d', strtotime($stockAdjustment->date)) : '') }}">
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
            <label for="product_id" class="">Product <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="product_id" id="product_id" class="select2 form-control @error('product_id') is-invalid @enderror" required>
                <option value="">Select</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @if (old('product_id', optional($stockAdjustment)->product_id) == $product->id) selected @endif>
                        {{ $product->name}}
                    </option>
                @endforeach
            </select>
            @error('product_id')
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
            <label for="adjustment_class" class="">Adjustment Class <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="adjustment_class" id="adjustment_class" class="form-control @error('adjustment_class') is-invalid @enderror" required>
                <option value="">Select</option>
                <option @if (old('adjustment_class', optional($stockAdjustment)->adjustment_class) == 1) selected @endif value="1">Uses</option>
                <option @if (old('adjustment_class', optional($stockAdjustment)->adjustment_class) == 2) selected @endif value="2">Wastage</option>
                <option @if (old('adjustment_class', optional($stockAdjustment)->adjustment_class) == 3) selected @endif value="3">Adjustment</option>
                <option @if (old('adjustment_class', optional($stockAdjustment)->adjustment_class) == 4) selected @endif value="4">Others</option>
            </select>
            @error('adjustment_class')
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
            <label for="adjustment_type" class="">Adjustment Type <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select required name="adjustment_type" id="adjustment_type" class="form-control @error('adjustment_type') is-invalid @enderror">
                <option value="">Select</option>
                <option @if (old('adjustment_type', optional($stockAdjustment)->adjustment_type) == 1) selected @endif value="1">Addition</option>
                <option @if (old('adjustment_type', optional($stockAdjustment)->adjustment_type) == 2) selected @endif value="2">Subtraction</option>
            </select>
            @error('adjustment_type')
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
            <label for="adjusted_quantity" class="">Adjusted Quantity <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="adjusted_quantity" id="adjusted_quantity" placeholder="Adjusted Quantity" type="number" min="0" step="any" class="form-control @error('adjusted_quantity') is-invalid @enderror" value="{{ old('adjusted_quantity', optional($stockAdjustment)->adjusted_quantity) }}">
            @error('adjusted_quantity')
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
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($stockAdjustment)->description) }}</textarea>
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