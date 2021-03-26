 @extends('layouts.app') @section('styles')
<style>
label {
	color: gray;
}
</style>
@endsection @section('content')

<div>
	<div class="container-fluid">
		<h1 style="font-size: 19px;">Add education</h1>

	</div>
</div>
<section id="contact"
	style="padding: 40px; padding-right: 5px; padding-left: 4px;">
	<div class="container">

		<form id="contactForm" style="padding: 15px;"
			action="{{ ($editing) ? route('updateEducation') : route('addEducation') }}"
			method="post">
			{{ csrf_field() }}
			<div class="form-row">
				<div class="col"
					style="width: 1042px; padding: 159px; padding-top: 36px;">
					<input type="text" name="id" hidden value="{{$education->getId()}}" />
					<input type="text" name="userId" hidden
						value="{{$education->getUserId()}}" />
					<div class="form-group has-feedback">
						<label for="from_name">School</label> <input class="form-control"
							type="text" name="school" required=""
							value="{{ $education->getSchool() }}">
						@if($errors->first('school'))
						<p class="validation_error">{{ $errors->first('school') }}</p>
						@endif
					</div>
					<div class="form-group has-feedback">
						<label for="from_name">Degree</label> <input class="form-control"
							type="text" name="description" required=""
							value="{{ $education->getDescription() }}">
						@if($errors->first('description'))
						<p class="validation_error">{{ $errors->first('description') }}</p>
						@endif
					</div>
					<div class="form-group">
						<button class="btn btn-primary active btn-block"
							style="background-color: #303641; color: rgb(210, 210, 210);"
							type="submit">Save</button>
					</div>
				</div>
			</div>
		</form>

	</div>
</section>
@endsection
