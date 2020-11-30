<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
        	<label for="name" class="">Name <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
        	<input name="name" id="name" placeholder="Name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', optional($user)->name) }}">
        	@error('name')
				<span class="is-invalid">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="email" class="">Email <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <input name="email" id="email" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', optional($user)->email) }}">
            @error('email')
                <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>

<div class="form-row">
    @if (!$user)
        <div class="col-md-6">
            <div class="position-relative form-group">
                <label for="password" class="">Password <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
                <input name="password" id="password" placeholder="Password" type="text" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') ?? 112358 }}">
                @error('password')
                    <span class="is-invalid">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    @endif

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="role" class="">Role <i class="fa fa-asterisk fa-xs" aria-hidden="true"></i></label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                <option value="">Select</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @if (old('role', optional($user)->roles ? $user->roles->first()->id : '') == $role->id) selected @endif>{{ $role->display_name }}</option>
                @endforeach
            </select>
            @error('role')
                <span class="is-invalid">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>

<button class="mt-2 btn btn-primary">Submit</button>