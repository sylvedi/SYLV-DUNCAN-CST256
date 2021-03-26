

<div class="card-body">
	<div class="row">
		<div class="col-md-6 text-nowrap"></div>
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
					<th style="width: 86px;">Username</th>
					<th style="width: 104px;">Firstname</th>
					<th style="width: 91px;">Lastname</th>
					<th style="width: 175px;">Email</th>
					<th style="width: 62px;">City</th>
					<th style="width: 10px;">State</th>
					<th style="width: 36px;"></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($users as $user)

				<tr>
					<td style="width: -4px;"><img class="rounded-circle mr-2"
						width="30" height="30"
						src="{{ asset('img/avatars/avatar5.jpeg') }}"
						style="margin-top: -2px;">{{ $user->getUsername() }}</td>
					<td style="width: 76px;">{{ $user->getFirstname() }}</td>
					<td style="width: 69px;">{{ $user->getLastname() }}</td>
					<td style="width: 69px;">{{ $user->getEmail() }}</td>
					<td>{{ $user->getCity() }}</td>
					<td style="width: 15px;">{{ $user->getState() }}</td>
					<td style="width: 56px;">@if (!$user->getSuspended())
						<form method="post" action="{{ route('suspendUser') }}">
							{{ csrf_field() }} <input readonly hidden="true" type="text"
								value="{{ $user->getId() }}" name="id"></input>
							<button class="btn btn-primary" type="submit" value="Unsuspend"
								style="width: 36px; height: 37px; margin-right: 14px; background-color: #6f9;">
								<i class="fa fa-unlock"
									style="margin-top: -9px; margin-left: -2px;"></i>
							</button>
						</form> @else
						<form method="post" action="{{ route('unsuspendUser') }}">
							{{ csrf_field() }} <input readonly hidden type="text"
								value="{{ $user->getId() }}" name="id"></input>
							<button class="btn btn-primary" type="submit" value="Suspend"
								style="width: 36px; height: 37px; margin-right: 14px; background-color: #f90;">
								<i class="fa fa-lock"
									style="margin-top: -9px; margin-left: -2px;"></i>
							</button>
						</form> @endif

						<form method="post" id="deleteUserForm" action="{{ route('deleteUser') }}" onsubmit="return confirm('Are you sure you want to delete the user?');">
							{{ csrf_field() }} <input readonly hidden="true" type="text"
								value="{{ $user->getId() }}" name="id"></input>
							<button class="btn btn-primary" type="submit"
								style="width: 36px; height: 37px; margin-right: 14px; background-color: rgb(192, 57, 43);">
								<i class="fa fa-trash-o"
									style="margin-top: -9px; margin-left: -2px;"></i>
							</button>
						</form> <a class="btn btn-primary"
						href="{{ route('editprofile', ['id'=>$user->getId()]) }}"
						type="submit" style="width: 36px; height: 37px;"><i
							class="fa fa-save" style="margin-top: -9px; margin-left: -2px;"></i></a>
						<br>

					</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td><strong>Name</strong></td>
					<td><strong>Firstname</strong></td>
					<td><strong>Lastname</strong></td>
					<td><strong>Email</strong></td>
					<td><strong>City</strong></td>
					<td><strong>State</strong></td>
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


<script>
var el = document.getElementById('deleteUserForm');

el.addEventListener('submit', function(){
    return confirm('Are you sure you want to submit this form?');
}, false);
</script>