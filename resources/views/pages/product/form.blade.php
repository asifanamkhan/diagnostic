<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="product_category_id" class="">Category <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="product_category_id" id="product_category_id" class="select2 form-control @error('product_category_id') is-invalid @enderror" required>
                <option value="">Select</option>
                @foreach($product_categories as $product_category)
                    <option value="{{ $product_category->id }}" @if( old('product_category_id', optional($product)->product_category_id) == $product_category->id ) selected @endif>
                        {{ $product_category->name}}
                    </option>
                @endforeach
            </select>
            @error('product_category_id')
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
            <label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="name" id="name" placeholder="Name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', optional($product)->name) }}">
            @error('name')
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
            <label for="alert_quantity" class="">Alert Quantity</label>
            <input name="alert_quantity" id="alert_quantity" placeholder="Alert Quantity" type="number" class="form-control @error('alert_quantity') is-invalid @enderror" value="{{ old('alert_quantity', optional($product)->alert_quantity) }}" min="0" step="any">
            @error('alert_quantity')
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
            <label for="expire_date" class="">Expire Date</label>
            <input name="expire_date" id="alert_quantity" placeholder="Expire Date" type="date" class="form-control @error('expire_date') is-invalid @enderror" value="{{ old('expire_date', optional($product)->expire_date) }}">
            @error('expire_date')
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
            <label for="unit" class="">Unit <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" required>
                <option value="">Select</option>
                <option @if (old('unit', optional($product)->unit) == 1) selected @endif value="1">Piece</option>
                <option @if (old('unit', optional($product)->unit) == 2) selected @endif value="2">Box</option>

            </select>
            @error('unit')
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
            <label for="exampleFile" class="">Image</label>
            <input name="image" multiple id="exampleFile" type="file" class="form-control-file @error('image') is-invalid @enderror" onchange="loadPreview(this)" accept="image/*">
            <small class="form-text text-muted">maximum file size is 2 mb</small>
            @error('image')
                <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group" id="uploadImage">
            <img id="upload" class="img-thumbnail" height="auto" src="#" alt="image" />
        </div>
        @if (optional($product)->image)
            <div class="form-group" id="showImage">
                <img src="{{ asset('images/product/'.$product->image) }}" alt="" class="img-thumbnail" width="200">
                <input type="hidden" value="{{ $product->image }}" name="oldimage">
            </div>
        @endif
    </div>
</div>

<div class="form-row">
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="description" class="">Description</label>
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($product)->description) }}</textarea>
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