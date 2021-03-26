
<div class="card-body">
	<div class="row">
		<div class="col-md-6 text-nowrap">
			<a href="{{ route('addNewJob') }}">Add New Job</a></div>
		<div class="col-md-6">
			<div class="text-md-right dataTables_filter" id="dataTable_filter">
				<label><input type="search" class="form-control form-control-sm"
					aria-controls="dataTable" placeholder="Search"></label>
			</div>
		</div>
	</div>
	<div class="table-responsive table mt-2" id="dataTable" role="grid"
		aria-describedby="dataTable_info">
		<table class="table dataTable my-0" id="dataTable">
			<thead>
				<tr>
					<th>Company</th>
					<th>Title</th>
					<th>Description</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($jobs as $job)

				<tr>
					<td>{{ $job->getCompanyid() }}</td>
					<td>{{ $job->getTitle() }}</td>
					<td>{{ $job->getDescription() }}</td>

					<td>

						<form method="post" action="{{ route('deleteJob') }}" onsubmit="return confirm('Are you sure you want to delete the job?');">
							{{ csrf_field() }} <input readonly hidden="" type="text"
								value="{{ $job->getId() }}" name="id"></input>
							<button class="btn btn-primary" type="submit"
								style="background-color: rgb(192, 57, 43);">
								<i class="fa fa-trash-o"
									style="margin-top: -9px; margin-left: -2px;"></i>
							</button>
						</form> <a class="btn btn-primary"
						href="{{ route('editjob', ['id'=>$job->getId()]) }}" type="submit"><i
							class="fa fa-save" style="margin-top: -9px; margin-left: -2px;"></i></a>
						<br>

					</td>
				</tr>
				@endforeach

			</tbody>
			<tfoot>
				<tr>
					<td><strong>Company</strong></td>
					<td><strong>Title</strong></td>
					<td><strong>Description</strong></td>
					<td><strong></strong></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="row">
		<div class="col-md-6 align-self-center">
			<!-- <p id="dataTable_info" class="dataTables_info" role="status"
									aria-live="polite">Showing 1 to 10 of 27</p>
							</div>
							<div class="col-md-6">
								<nav
									class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
									<ul class="pagination">
										<li class="page-item disabled"><a class="page-link" href="#"
											aria-label="Previous"><span aria-hidden="true">«</span></a></li>
										<li class="page-item active"><a class="page-link" href="#">1</a></li>
										<li class="page-item"><a class="page-link" href="#">2</a></li>
										<li class="page-item"><a class="page-link" href="#">3</a></li>
										<li class="page-item"><a class="page-link" href="#"
											aria-label="Next"><span aria-hidden="true">»</span></a></li>
									</ul>
								</nav>
							</div> -->
		</div>
	</div>
</div>