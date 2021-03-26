@extends('layouts.app') 

@section('content')
<div class="container">
        <h1 style="color: rgb(149,165,166);margin-top: 128px;">Groups</h1>
        <a style="margin-top: 128px;" href="{{ route('addgroup') }}">Add Group</a>
    </div>
    <section>
        <div class="container">
            <div class="row">
                
                @foreach($groups as $group)
				<div class="col-lg-4">
                    <div class="card mb-4 box-shadow rounded-0">
                        <div class="card-body">
                            <h4 class="card-title">{{ $group->getName() }}</h4>
                            <p class="card-text">{{ $group->getDescription() }}</p><a class="btn btn-primary" role="button" href="{{ route('group', ['id'=>$group->getId()]) }}">View</a></div>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>
    </section>
@endsection