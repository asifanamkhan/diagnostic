<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="name" id="name" placeholder="Name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', optional($patient)->name) }}">
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
            <input required name="mobile" id="mobile" placeholder="Mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', optional($patient)->mobile) }}">
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
            <label for="gender" class="">Gender <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                <option value="">Select</option>
                <option value="male" @if (old('gender', optional($patient)->gender) == "male") selected @endif>Male</option>
                <option value="female" @if (old('gender', optional($patient)->gender) == "female") selected @endif>Female</option>
                <option value="others" @if (old('gender', optional($patient)->gender) == "others") selected @endif>Other</option>
            </select> 
            @error('gender')
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
            <label for="age" class="">Age <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input required name="age" id="age" placeholder="Age" type="number" class="form-control @error('age') is-invalid @enderror" value="{{ old('age', optional($patient)->age) }}" min="0" step="any">
            @error('age')
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
            <input name="email" id="email" placeholder="Email " type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', optional($patient)->email) }}">
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
            <input name="address" id="address" placeholder="Address" type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', optional($patient)->address) }}">
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
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="description" class="">Description</label>
            <textarea name="description" class="description form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Enter Description">{{ old('description', optional($patient)->description) }}</textarea>
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