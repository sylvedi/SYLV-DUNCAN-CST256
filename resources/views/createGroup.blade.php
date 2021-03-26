@extends('layouts.app')

@section('content')
    <section>
        <div class="container">
        <h1 class="text-center" style="font-size: 19px;color: rgb(149,165,166);">Create group</h1>
            <form action="{{ ($editing) ? route('updateGroup') : route('addGroup') }}" method="post">
            {{ csrf_field() }}
                <div class="form-row" style="width: 817px;margin-right: auto;margin-left: auto;">
                    <div class="col">
                        <div style="height: 156px;background-image:url({{ asset('resources/assets/img/Jodhpur.jpg') }})"></div>
                        <input type="text" name="id" hidden="true" value="{{$group->getId()}}"/>
                        <div class="form-group text-left">
                        	<label>Group name</label>
                        	<input class="form-control" name="name" type="text" value="{{$group->getName()}}">
                        	@if($errors->first('name'))
    						<p class="validation_error">{{ $errors->first('name') }}</p>
    						@endif
                        </div>
                        <div class="form-group text-left">
                        	<label>Description</label>
                        	<textarea class="form-control" style="height: 76px;" name="description">{{$group->getDescription()}}</textarea>
                        	@if($errors->first('description'))
    						<p class="validation_error">{{ $errors->first('description') }}</p>
    						@endif
                        </div>
                        <div class="form-group">
                        	<button type="submit" class="btn btn-outline-primary text-center d-inline-flex float-right justify-content-center align-items-end justify-content-xl-center align-items-xl-start" role="button" data-bs-hover-animate="pulse" style="height: 30px;padding-top: 0px;padding-bottom: 4px;">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection