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
    <section id="contact" style="padding:40px;padding-right:5px;padding-left:4px;">
        <div class="container">
        
            <form id="contactForm" style="padding:15px;" action="{{ ($editing) ? route('updateSkill') : route('addSkill') }}" method="post">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="col" style="width: 1042px;padding: 159px;padding-top: 36px;">
                        <input type="text" name="id" hidden value="{{$skill->getId()}}"/>
                        <input type="text" name="userId" hidden value="{{$skill->getUserId()}}"/>
                        <div class="form-group has-feedback"><label for="from_name">Skill</label>
                        <input class="form-control" type="text" name="description" required="" value="{{ $skill->getDescription() }}">
                        @if($errors->first('description'))
								<p class="validation_error">{{ $errors->first('description') }}</p>
							@endif
                        </div>
                        <div class="form-group has-feedback"><label for="from_name">Years</label>
                        <input class="form-control" type="number" name="years" required="" value="{{ $skill->getYears() }}">
                        @if($errors->first('years'))
								<p class="validation_error">{{ $errors->first('years') }}</p>
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