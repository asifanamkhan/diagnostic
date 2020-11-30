<div class="form-row">
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="name" id="name" placeholder="Name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', optional($productCategory)->name) }}">
            @error('name')
                <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This field is Required</div>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="description" class="">Description</label>
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($productCategory)->description) }}</textarea>
            @error('description')
                <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This field is Required</div>
        </div>
    </div>
</div>

<button class="mt-2 btn btn-primary">Submit</button>