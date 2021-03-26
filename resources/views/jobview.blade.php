@extends('layouts.app')

@section('content')
<section style="margin-top: 16px;">
        <div class="container" style="margin-top: 78px;">
            <div class="form-row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-10">
                                	<a href="{{ route('welcome') }}">Back to Jobs</a>
                                	<h3>{{ $job->getTitle() }}</h3>
                                	<p>{{ $job->getDescription() }}</p>
                                	<form method="post" action="{{ route('applyToJob') }}">
                                		{{ csrf_field() }}
                                		<input hidden="true" type="text" name="id" value="{{ $job->getId() }}" />
                                		<button type="submit" class="btn btn-primary">Apply Now</button>
                                	</form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection