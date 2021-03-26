 @extends('layouts.app') @section('styles')
<style>
label {
	color: gray;
}
</style>
@endsection @section('content')
   <div>
        <div class="container-fluid">
            <h1 style="font-size: 19px;">Add experience</h1>
           
        </div>
    </div>
    <section id="contact" style="padding:40px;padding-right:5px;padding-left:4px;">
        <div class="container">
        	<form id="contactForm" style="padding:15px;" action="{{ ($editing) ? route('updateExperience') : route('addExperience') }}" method="post">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="col" style="width: 1042px;padding: 159px;padding-top: 36px;">
                        <div class="form-group has-feedback">
                        <label for="from_name">Job Title</label>
                        
                        <input type="text" name="id" hidden value="{{$experience->getId()}}"/>
                        <input type="text" name="userId" hidden value="{{$experience->getUserId()}}"/>
                        <input class="form-control" type="text" tabindex="-1" name="jobtitle" required="" value="{{ $experience->getJobtitle() }}">
                        @if($errors->first('jobtitle'))
								<p class="validation_error">{{ $errors->first('jobtitle') }}</p>
							@endif
                        </div>
                        <div class="form-group has-feedback"><label for="from_name">Company</label>
                        <input class="form-control" type="text" tabindex="-1" name="company" required="" value="{{ $experience->getCompany() }}">
                        @if($errors->first('company'))
								<p class="validation_error">{{ $errors->first('company') }}</p>
							@endif
                        </div>

                        <div class="form-row">
                            <div class="col-sm-12">
                                <div class="form-group has-feedback">
                                <label for="startdate">Start Date</label>
                                <label for="enddate" style="margin-left: 151px;">End Date</label>&nbsp;
                                @if($experience->getCurrentjob())
                                	<input name="currentjob" type="checkbox" checked onclick="var input = document.getElementById('enddate'); if(this.checked){ input.disabled = true; input.focus();}else{input.disabled=false;}" />&nbsp;<label style="display:inline">I currently work here</label>
                                @else
                                	<input name="currentjob" type="checkbox" onclick="var input = document.getElementById('enddate'); if(this.checked){ input.disabled = true; input.focus();}else{input.disabled=false;}" />&nbsp;<label style="display:inline">I currently work here</label>
                                @endif
                                </div>
                                <input class="form-control" value="{{ $experience->getStartdate() }}" type="date" name="startdate" style="margin-top: -10px;width: 185px;">
                                @if($errors->first('startdate'))
								<p class="validation_error">{{ $errors->first('startdate') }}</p>
							@endif
							@if($experience->getCurrentjob())
                                	<input disabled="" class="form-control" value="{{ $experience->getEnddate() }}" id="enddate" type="date" name="enddate" style="margin-top: -39px;width: 185px;margin-left: 210px;">
                                @else
                                	<input class="form-control" value="{{ $experience->getEnddate() }}" id="enddate" type="date" name="enddate" style="margin-top: -39px;width: 185px;margin-left: 210px;">
                                @endif
                                
                                @if($errors->first('enddate'))
								<p class="validation_error">{{ $errors->first('enddate') }}</p>
							@endif
                                </div>
                        </div>
                        <div class="form-group">
                        <label for="comments">Description</label>
                        <textarea style="color:black;" class="form-control" id="comments" name="description" rows="5">{{ $experience->getDescription() }}</textarea>
                        
                        @if($errors->first('description'))
								<p class="validation_error">{{ $errors->first('description') }}</p>
							@endif
                        </div>
                        <div class="form-group">
                        <button class="btn btn-primary active btn-block" style="background-color: #303641;color: rgb(210,210,210);" type="submit">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection