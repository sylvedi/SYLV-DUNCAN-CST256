<style>

a.dropdown-toggle{
   color: rgb(236, 240, 241);
}

a.dropdown-toggle:hover{
   color: rgb(48, 151, 209);
}

</style>

<nav class="navbar navbar-light navbar-expand-md"
	style="height: 61px; background-color: #0a3d62;">
	<div class="container-fluid">
		<div>
			<a class="navbar-brand" href="{{ route('welcome') }}"
				style="color: rgb(236, 240, 241);">Connect</a>
		</div>
		<div id="navcol-1">
		<a class="navlink" href="{{ route('welcome') }}"
				style="color: rgb(236, 240, 241);">Jobs</a><span>&nbsp;</span>
		<a class="navlink" href="{{ route('groups') }}"
				style="color: rgb(236, 240, 241);">Groups</a>
		</div>
		<div class="collapse navbar-collapse" id="navcol-2"
			style="height: 48px; color: #ecf0f1;">
			<ul class="nav navbar-nav ml-auto" style="float:right;" id="desktop-toolbar">
				<li class="nav-item dropdown">@if(session('LoggedIn')) <a
					class="dropdown-toggle" data-toggle="dropdown"
					aria-expanded="false" href="#">
						<img class="rounded-circle" src="{{ session('user')->getPhoto() }}"
						width="25px" height="25px" style="margin-top: -5px;"> {{
						session('user')->getFirstname() }}
				</a>
					<div class="dropdown-menu" role="menu">
						<a class="dropdown-item" role="presentation"
							href="{{ route('profile', ['id'=>session('user')->getId()]) }}"><i
							class="fa fa-user fa-fw"></i> Profile </a>
						@if(session('IsAdmin')) <a class="dropdown-item"
							role="presentation" href="{{ route('admin') }}"><i
							class="fa fa-power-off fa-fw"></i>Admin </a> @endif <a
							class="dropdown-item" role="presentation"
							href="{{ route('logout') }}"><i class="fa fa-power-off fa-fw"></i>Logout
						</a>
					</div> @else <a role="presentation" href="{{ route('signin') }}"><i
						class="fa fa-user fa-fw"></i> Login or Sign Up </a> @endif
				</li>
			</ul>
			<!-- <ul class="nav navbar-nav" id="mobile-nav">
        <li class="nav-item" role="presentation"></li>
        <li class="nav-item" role="presentation"><a class="nav-link" href="index.html" style="color: rgb(236,240,241);"> Nav Item</a></li>
        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: rgb(236,240,241);"> Dropdown<i class="fa fa-chevron-down fa-fw"></i> </a>
            <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#nogo"><i class="fa fa-star fa-fw"></i> Link Item</a><a class="dropdown-item" role="presentation" href="#nogo"><i class="fa fa-star fa-fw"></i> Link Item</a></div>
        </li>
        <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: rgb(236,240,241);"><i class="fa fa-star fa-fw"></i> Dropdown </a>
            <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="fundraising.html"><i class="fa fa-star fa-fw"></i> Link Item</a><a class="dropdown-item" role="presentation" href="donations.html"><i class="fa fa-star fa-fw"></i> Link Item</a><a class="dropdown-item"
                    role="presentation" href="events-listing.html"><i class="fa fa-star fa-fw"></i> Link Item</a></div>
        </li>
    </ul> -->
		</div>
	</div>
</nav>

