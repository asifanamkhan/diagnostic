<div class="form-row">
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input  name="name" id="name" placeholder="Name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', optional($settings)->name) }}">
            @error('name')
                <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="valid-feedback">Looks Good!</div>
            <div class="invalid-feedback">This Field is Required</div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="position-relative row form-group">
            <label for="exampleFile" class="col-sm-3 col-form-label">Logo</label>
            <div class="col-md-9">
                <input  name="logo" id="exampleFile" type="file" class="form-control-file @error('logo') is-invalid @enderror" onchange="loadPreview(this)" accept="image/*">
                @error('logo')
                    <span class="is-invalid">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="valid-feedback">Looks Good!</div>
                <div class="invalid-feedback">This Field is Required</div>
            </div>
        </div>
    </div>

    <div class="col-md-12 text-center">
        <div class="form-group" id="uploadImage">
            <img id="upload" class="img-thumbnail" height="auto" width="200" src="{{ asset('images/setting/'.$settings->logo) }}" alt="image" />
        </div>
    </div>
</div>

<button class="mt-2 btn btn-primary">Update</button>