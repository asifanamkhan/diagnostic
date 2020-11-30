<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ config('app.name', 'Laravel') }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon"/>
	<link href="{{ asset('css/login/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/login/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/login/icon-font.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/login/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('css/login/hamburgers.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/login/util.css') }}" rel="stylesheet">
	<link href="{{ asset('css/login/main.css') }}" rel="stylesheet">
</head>

<body>	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-50 p-r-50 p-t-77 p-b-30">
				<span class="login100-form-title p-b-30">
					{{ config('app.name', 'Laravel') }}
				</span>
				<form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
					@csrf
					<div class="wrap-input100 validate-input m-b-16" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="email" id="email" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<span class="lnr lnr-envelope"></span>
						</span>
						@error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>

					<div class="wrap-input100 validate-input m-b-16" data-validate = "Password is required">
						<input class="input100" type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<span class="lnr lnr-lock"></span>
						</span>
						@error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>

					<div class="contact100-form-checkbox m-l-4">
						<input class="input-checkbox100" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
						<label class="label-checkbox100" for="remember">
							Remember me
						</label>
					</div>
					
					<div class="container-login100-form-btn p-t-25">
						<button class="login100-form-btn">
							Login
						</button>
					</div>

					@if (Route::has('password.request'))
						<div class="text-center w-full p-t-30">
							<span class="txt1">
								Forget Password?
							</span>

							<a class="txt1 bo1 hov1" href="{{ route('password.request') }}">
								Reset Now							
							</a>
						</div>
					@endif
				</form>
			</div>
		</div>
	</div>
		
	<script src="{{ asset('js/login/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('js/login/popper.min.js') }}"></script>
	<script src="{{ asset('js/login/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/login/main.js') }}"></script>
</body>
</html>