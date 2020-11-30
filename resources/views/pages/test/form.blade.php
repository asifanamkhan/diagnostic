<div class="form-row">
    <div class="col-md-12">       
        <div class="position-relative form-group">
            <label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="name" id="name" placeholder="Name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', optional($test)->name) }}">
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
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="test_category_id" class="">Category <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="test_category_id" id="test_category_id" class="select2 form-control @error('test_category_id') is-invalid @enderror" required>
                <option value="">Select</option>
                @foreach ($test_categories as $test_category)
                    <option value="{{ $test_category->id }}" @if (old('test_category_id', optional($test)->test_category_id) == $test_category->id) selected @endif>
                        {{ $test_category->name}}
                    </option>
                @endforeach
            </select>
            @error('test_category_id')
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
            <label for="cost" class="">Cost <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="cost" id="cost" placeholder="Cost" type="number" class="form-control @error('rate') is-invalid @enderror" value="{{ old('cost', optional($test)->cost) }}" min="0" step="any">
            @error('cost')
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
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($test)->description) }}</textarea>
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

<h5>Doctor's Commission</h5>
<div class="form-row">
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="doctor" class="">Doctor</label>
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

@foreach ($doctors as $key => $doctor)
<div class="form-row">
    <div class="col-md-4">
        <div class="position-relative form-group">
            <input name="doctor[]" id="doctor{{ $key }}" placeholder="Doctor" type="text" class="form-control" value="{{ $doctor->name }}, {{ $doctor->specialty }}" readonly>
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="position-relative form-group">
            <select name="commission_type[]" id="commission_type{{ $key }}" class="form-control @error("commission_type.{$key}") is-invalid @enderror" required>
                <option value="">Select</option>
                <option value="1" @if (old("commission_type.{$key}", optional($test)->commissions ? ($test->commissions()->where('doctor_id', $doctor->id)->first()->pivot->commission_type ?? '') : '') == 1 || is_null($test)) selected @endif>
                    Percentage
                </option>
                <option value="2" @if (old("commission_type.{$key}", optional($test)->commissions ? ($test->commissions()->where('doctor_id', $doctor->id)->first()->pivot->commission_type ?? '') : '') == 2) selected @endif>
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
            <input name="commission[]" id="commission{{ $key }}" placeholder="Commission" type="number" min="0" step="any" class="form-control @error("commission.{$key}") is-invalid @enderror" value="{{ old("commission.{$key}", optional($test)->commissions ? ($test->commissions()->where('doctor_id', $doctor->id)->first()->pivot->commission ?? 0) : 0) ?? 0 }}" required>
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
            <input name="note[]" id="note{{ $key }}" placeholder="Note" type="text" class="form-control @error("note.{$key}") is-invalid @enderror" value="{{ old("note.{$key}", optional($test)->commissions ? ($test->commissions()->where('doctor_id', $doctor->id)->first()->pivot->description ?? "") : "") }}">
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