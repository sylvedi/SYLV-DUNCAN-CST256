 @extends('layouts.app') @section('styles')
<link rel="stylesheet" href="{{ asset('/css/Login-Form-Clean.css') }}">
<link rel="stylesheet"
	href="{{ asset('/css/Registration-Form-with-Photo.css') }}">
@endsection @section('scripts')
<script>
    function toggleRegistration(){
		var l = document.getElementById("loginForm");
		var r = document.getElementById("registerForm"); 
		if(l.style.display === "none"){ // if we are on register
			l.style.display = "block";
			r.style.display = "none";
		} else {
			l.style.display = "none";
			r.style.display = "block";
		}
    }
    </script>
@endsection @section('content')

<div class="container">
	<div class="row d-flex justify-content-center" style="margin-top: 67px;">
		<div id="registerForm" style="display: block; width: 60%;">

			<div class="d-flex justify-content-center"
			style="color: rgba(9, 22, 34, 0.75); background-color: #0a3d62;">
			<form method="post" style="display:block;" action="{{ route('register')}}"
			
				style="padding: 12px;">
				{{ csrf_field() }}
				<h2 class="text-center"
					style="padding-bottom: 31px; color: rgb(246, 246, 247); padding-left: 0px; margin-top: 40px;">
					<strong>Create</strong>&nbsp;account.
				</h2>
				<div class="form-group">
					<input class="form-control" type="text" name="username" value="{{ $user->getUsername() }}"
						placeholder="Username" style="padding-left: 7px;">
						@if($errors->first('username'))
								<p class="validation_error">{{ $errors->first('username') }}</p>
							@endif
				</div>
				<div class="form-group" style="padding-left: -25px;">
					<input class="form-control" type="email" name="email" value="{{ $user->getEmail() }}"
						placeholder="Email">
						@if($errors->first('email'))
								<p class="validation_error">{{ $errors->first('email') }}</p>
							@endif
						<input class="form-control" type="password"
						name="password" placeholder="Password"
						style="margin-top: 16px; margin-left: 0px; padding-left: 11px; padding-right: 12px;">
						@if($errors->first('password'))
								<p class="validation_error">{{ $errors->first('password') }}</p>
							@endif
					<input class="form-control" type="text" name="firstname" value="{{ $user->getFirstname() }}"
						placeholder="Firstname" style="margin-top: 16px;">
						@if($errors->first('firstname'))
								<p class="validation_error">{{ $errors->first('firstname') }}</p>
							@endif
						<input
						class="form-control" type="text" name="lastname" value="{{ $user->getLastname() }}"
						placeholder="Lastname" style="margin-top: 16px;">
						@if($errors->first('lastname'))
								<p class="validation_error">{{ $errors->first('lastname') }}</p>
							@endif
						<input
						class="form-control" type="text" name="city" placeholder="City" value="{{ $user->getCity() }}"
						style="margin-top: 17px;">
						@if($errors->first('city'))
								<p class="validation_error">{{ $errors->first('city') }}</p>
							@endif
				</div>
				<div class="form-group">
					<input class="form-control" type="text" name="state" value="{{ $user->getState() }}"
						placeholder="state">
						@if($errors->first('state'))
								<p class="validation_error">{{ $errors->first('state') }}</p>
							@endif
				</div>
				<div class="form-group">
					<div class="form-check">
						<label class="form-check-label"><input class="form-check-input"
							type="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I agree to the license terms.</label>
					</div>
				</div>
				<div class="form-group">
					<button class="btn btn-primary btn-block" type="submit"
						style="background-color: rgb(16, 70, 128);">Sign
						Up</button>
				</div>
				<div class="form-group">
				<a class="text-center" onClick="toggleRegistration()" href="#"
					style="font-size: 10px; color: lightgray;">You
					already have an account? Login here.</a>
				</div>
			</form>
			</div>
		</div>
		<div id="loginForm" style="display: none; width: 60%;">

			<div class="d-flex justify-content-center"
			style="background-color: rgb(10, 61, 98); color: rgba(9, 22, 34, 0.75);">
			
			<form method="post" style="display:block;" action="{{ route('login')}}"
				style="padding: 12px;">
				{{ csrf_field() }}
				<h2 class="text-center"
					style="padding-bottom: 31px; color: rgb(246, 246, 247); padding-left: 0px; margin-top: 40px;">
					<strong>Sign-In</strong>
				</h2>
				<div class="illustration"></div>
				<div class="form-group">
					<input class="form-control" type="text" name="username" value="{{ $user->getUsername() }}"
						placeholder="Username">
						@if($errors->first('username'))
								<p class="validation_error">{{ $errors->first('username') }}</p>
							@endif
				</div>
				<div class="form-group">
					<input class="form-control" type="password" name="password"
						placeholder="Password">
						@if($errors->first('password'))
								<p class="validation_error">{{ $errors->first('password') }}</p>
							@endif
				</div>
				<div class="form-group">
					<button class="btn btn-primary btn-block" type="submit"
						style="background-color: rgb(16, 70, 128);">Log
						In</button>
				</div>
				
				<div class="form-group">
				<a class="forgot" href="#"
					style="font-size: 11px;">Forgot
					your email or password?</a><br> 
				<a class="text-center" onClick="toggleRegistration()" href="#"
					style="font-size: 10px; color: lightgray;">Sign up for a new account.</a>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>

@isset($doLogin)
<script>toggleRegistration()</script>
@endisset

@endsection
