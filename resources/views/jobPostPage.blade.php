<div class="article-list">
	<div class="container" style="margin-top: 2px;">
		<div class="intro">
			<h2 class="text-center" style="padding: 40px; color:slategrey">JOBS</h2>
		</div>

		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12 col-md-10 col-lg-8">
					<form method="post" action="">
					    {{ csrf_field() }}
						<div class="row no-gutters align-items-center">
							<div class="col">
								<input
									name="keywords"
									class="form-control form-control-lg form-control-borderless"
									type="text" placeholder="Search topics or keywords" value="{{ $keywords }}">
							</div>
							<div class="col-auto">
								<button class="btn btn-md btn-primary" type="submit">Search</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="row articles"
			style="width: 70%; margin:auto; margin-top: 18px;">

			@foreach($jobs as $job)
			 <div class="col-sm-6">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title" style="color:dimgrey">{{ $job->getTitle() }}</h5>
				</div>
				<div class="card-body">
					<p class="card-text" style="color:darkgray">{{ $job->getDescription() }}</p>
					<a href="{{ route('viewjob', ['id'=>$job->getId()]) }}" class="btn btn-primary">-></a>
				</div>
			</div>
			</div>
			@endforeach
    		
		</div>
	</div>
</div>
