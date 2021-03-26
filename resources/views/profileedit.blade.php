@extends('layouts.app') @section('styles')
<style>
label {
	color: gray;
}
</style>
@endsection @section('content')
<div class="container profile profile-view" id="profile">
	<form method="post" action="{{ route('updateUser')}}">
		{{ csrf_field() }}
		<div class="form-row profile-row">

			<!-- <div class="col-md-4 relative">
                    <div class="avatar">
                        <div class="avatar-bg center"></div>
                    </div><input type="file" class="form-control" name="avatar-file">
                </div> -->
			<div class="col-md-8">
				<h1 style="color: #555;">Edit Profile</h1>
				<hr>
				<div class="form-row">
					<input type="text" name="id" hidden readonly
						value="{{ $user->getId() }}"></input>
					<input type="text" name="editing" hidden readonly
						value="dummy"></input>
					<input type="text" name="suspended" hidden readonly
						value="{{ $user->getSuspended() }}"></input>
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label>Firstname </label><input class="form-control" type="text"
								name="firstname" value="{{$user->getFirstname()}}">
								
							@if($errors->first('firstname'))
								<p class="validation_error">{{ $errors->first('firstname') }}</p>
							@endif
							
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label>Lastname</label><input class="form-control" type="text"
								name="lastname" value="{{$user->getLastname()}}">
								
							@if($errors->first('lastname'))
								<p class="validation_error">{{ $errors->first('lastname') }}</p>
							@endif
						</div>
						
							
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label>Username</label><input class="form-control" type="text"
								name="username" value="{{$user->getUsername()}}">
								
    						@if($errors->first('username'))
    								<p class="validation_error">{{ $errors->first('username') }}</p>
    							@endif
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label>City</label><input class="form-control" type="text"
								name="city" value="{{$user->getCity()}}">
								
						@if($errors->first('city'))
								<p class="validation_error">{{ $errors->first('city') }}</p>
							@endif
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label>State</label><input class="form-control" type="text"
								name="state" value="{{$user->getState()}}">
								
						@if($errors->first('state'))
								<p class="validation_error">{{ $errors->first('state') }}</p>
							@endif
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label>Birthday</label>
						</div>
						<input class="form-control" name="birthday" type="date"
							value="{{$user->getBirthday()}}" style="margin-top: -17px;">
							
						@if($errors->first('birthday'))
								<p class="validation_error">{{ $errors->first('birthday') }}</p>
							@endif
					</div>
				</div>
				<div class="form-group">
					<label>Email </label><input class="form-control" type="email"
						autocomplete="off" required="" name="email"
						value="{{$user->getEmail()}}">
						
						@if($errors->first('email'))
								<p class="validation_error">{{ $errors->first('email') }}</p>
							@endif
				</div>
				<div class="form-group">
					<label>Tagline</label><input class="form-control" type="text"
						name="tagline" value="{{ $user->getTagline() }}">
						
						@if($errors->first('tagline'))
								<p class="validation_error">{{ $errors->first('tagline') }}</p>
							@endif
				</div>
				<div class="form-row">
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label>New Password </label><input class="form-control"
								type="password" name="password" autocomplete="off"
								value="">
								
						@if($errors->first('password'))
								<p class="validation_error">{{ $errors->first('password') }}</p>
							@endif
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group">
							<label>Confirm Password</label><input class="form-control"
								type="password" name="password_confirmation" autocomplete="off">
								
						@if($errors->first('password_confirmation'))
								<p class="validation_error">{{ $errors->first('password_confirmation') }}</p>
							@endif
						</div>
					</div>
				</div>
				<hr>
				<div class="form-row">
					<div class="col-md-12 content-right">
						<button class="btn btn-primary form-btn" type="submit">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection
