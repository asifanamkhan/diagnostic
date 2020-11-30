<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="name" id="name" placeholder="Name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', optional($doctor)->name) }}">
            @error('name')
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
            <label for="mobile" class="">Mobile <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="mobile" id="mobile" placeholder="Mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', optional($doctor)->mobile) }}">
            @error('mobile')
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
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="email" class="">Email</label>
            <input name="email" id="email" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', optional($doctor)->email) }}">
            @error('email')
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
            <label for="address" class="">Address</label>
            <input name="address" id="address" placeholder="Address" type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', optional($doctor)->address) }}">
            @error('address')
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
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="specialty" class="">Specialty</label>
            <input name="specialty" id="specialty" placeholder="Specialty" type="text" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty', optional($doctor)->specialty) }}">
            @error('specialty')
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
            <label for="qualification" class="">Qualification</label>
            <input name="qualification" id="qualification" placeholder="Qualification" type="text" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification', optional($doctor)->qualification) }}">
            @error('qualification')
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
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($doctor)->description) }}</textarea>
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

<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative row form-group">
            <label for="exampleFile" class="col-sm-3 col-form-label">Image</label>
            <div class="col-md-9">
                <input name="image" multiple id="exampleFile" type="file" class="form-control-file @error('image') is-invalid @enderror"  onchange="loadPreview(this)" accept="image/*">
                <small class="form-text text-muted">maximum file size is 2 mb</small>
                @error('image')
                    <span class="is-invalid">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group" id="uploadImage">
            <img id="upload" class="img-thumbnail" height="auto" src="#" alt="image" />
        </div>
        @if (optional($doctor)->image)
            <div class="form-group" id="showImage">
                <img src="{{ asset('images/doctor/'.$doctor->image) }}" alt="" class="img-thumbnail" width="200">
                <input type="hidden" value="{{ $doctor->image }}" name="oldimage">
            </div>
        @endif
    </div>
</div>

<h5>Test Commissions</h5>
<div class="form-row">
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="test" class="">Test</label>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <label for="commission_type" class="">Commission Type <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <label for="commission" class="">Commission <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="Note" class="">Note</label>
        </div>
    </div>
</div>

@foreach ($tests as $key => $test)
<div class="form-row">
    <div class="col-md-4">
        <div class="position-relative form-group">
            <input name="test[]" id="test{{ $key }}" placeholder="Test" type="text" class="form-control" value="{{ $test->name }}, {{ $test->category->name }}" readonly>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <select name="commission_type[]" id="commission_type{{ $key }}" class="form-control @error("commission_type.{$key}") is-invalid @enderror" required>
                <option value="">Select</option>
                <option value="1" @if (old("commission_type.{$key}", optional($doctor)->commissions ? ($doctor->commissions()->where('test_id', $test->id)->first()->pivot->commission_type ?? '') : '') == 1 || is_null($doctor)) selected @endif>
                    Percentage
                </option>
                <option value="2" @if (old("commission_type.{$key}", optional($doctor)->commissions ? ($doctor->commissions()->where('test_id', $test->id)->first()->pivot->commission_type ?? '') : '') == 2) selected @endif>
                    Fixed Amount
                </option>
            </select>
            @error("commission_type.{$key}")
                <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <input name="commission[]" id="commission{{ $key }}" placeholder="Commission" type="number" min="0" step="any" class="form-control @error("commission.{$key}") is-invalid @enderror" value="{{ old("commission.{$key}", optional($doctor)->commissions ? ($doctor->commissions()->where('test_id', $test->id)->first()->pivot->commission ?? 0) : 0) ?? 0 }}" required>
            @error("commission.{$key}")
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
            <input name="note[]" id="note{{ $key }}" placeholder="Note" type="text" class="form-control @error("note.{$key}") is-invalid @enderror" value="{{ old("note.{$key}", optional($doctor)->commissions ? ($doctor->commissions()->where('test_id', $test->id)->first()->pivot->description ?? "") : "") }}">
            @error("note.{$key}")
                <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>
</div>
@endforeach

<button class="mt-2 btn btn-primary">Submit</button>