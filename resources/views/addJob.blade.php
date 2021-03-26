@extends('layouts.app') @section('styles')
<style>
label {
	color: gray;
}
</style>
@endsection @section('content')
<section id="contact"
	style="padding: 40px; padding-right: 5px; padding-left: 4px;">
	<div class="container">
		<form id="contactForm" style="padding: 15px;"
			action="{{ ($editing) ? route('updateJob') : route('addJob') }}" method="post">
			{{ csrf_field() }}
			<div class="form-row">
				<div class="col"
					style="width: 1042px; padding: 159px; padding-top: 36px;">
					<div class="form-group">
					
                        <input type="text" name="id" hidden value="{{$job->getId()}}"/>
                        <input type="text" name="companyId" hidden value="{{$job->getCompanyid()}}"/>
						<label for="from_name" style="margin-left: 1px;">Job Title&nbsp;</label>
						<input class="form-control" type="text" tabindex="-1" name="title"
							required="" value="{{ $job->getTitle() }}" style="height: 58px;">
						@if($errors->first('title'))
						<p class="validation_error">{{ $errors->first('title') }}</p>
						@endif
					</div>

					<div class="form-group">
						<label for="comments">Description</label>
						<textarea class="form-control" name="description" rows="5">{{ $job->getDescription() }}</textarea>
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
