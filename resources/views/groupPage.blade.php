@extends('layouts.app')

@section('content')
<section style="margin-top: 16px;">
        <div class="container" style="margin-top: 78px;">
                <div class="form-row" style="width: 817px;margin-right: auto;margin-left: auto;">
                    <div class="col">
                        
                        <div class="card" style="width: 807px;height: 159px;margin-right: auto;margin-left: auto;background-image:url({{ asset('resources/assets/img/Jodhpur.jpg') }}); background-size: cover">
                            <div class="card-body" style="height: 243px;">
                                <div class="form-row" style="width: 793px;margin-top: -44px;">
                                
                                    <div class="col-md-10">
                                        <div class="media" style="margin-top: 6px;">
                                            <div class="media-body" style="padding: 12px;">
                                            <h1 style="font-size: 30px;color: rgb(53,59,72);">{{ $group->getName() }}</h1>
                                            
                                            	@foreach($group->getMembers() as $member)
                                            		
                                            		@if($member->getId() == $group->getAdminid())
                                                		<h4 class="text-left"><a href="{{ route('profile', ['id'=>$member->getId()]) }}" style="color: rgb(113,128,147);">{{ $member->getFirstname() }} {{ $member->getLastname() }}</a></h4>
                                            		@endif
                                            	@endforeach
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group text-left" style="background-color: #ffffff;">
                        <div class="col-md-3 text-right" style="float:right; padding-top: 12px;">
                                    
                                    @if(session()->get('LoggedIn'))
                                    	@if(session()->get('UserID') == $group->getAdminid())
                                            <a class="btn btn-outline-primary text-center justify-content-center align-items-end justify-content-xl-center align-items-xl-start" role="button" data-bs-hover-animate="pulse"
                                                style="height: 30px;padding-top: 0px;padding-bottom: 4px;" href="{{ route('editgroup', ['id'=>$group->getId()]) }}">Edit Group</a>
                                            <form action="{{ route('deleteGroup') }}" method="post" onsubmit="return confirm('Are you sure you want to delete the group?');">
                                                {{csrf_field()}}
                                                <input hidden value="{{ $group->getId() }}" name="id" />
                                                <div class="btn-group" role="group"><button class="btn btn-primary" type="submit"> <i class="fa fa-trash-o"></i></button></div>
                                            </form>
                                        @else
                                        
                                        	@foreach($group->getMembers() as $member)
                                            		
                                            		@if($member->getId() == session()->get('UserID'))
                                            			<form action="{{ route('leaveGroup') }}" method="post">
                                            			{{csrf_field()}}
                                                        <input hidden value="{{ session()->get('UserID') }}" name="UserID" />
                                                        <input hidden value="{{ $group->getId() }}" name="GroupID" />
                                                        <button class="btn btn-outline-primary text-center justify-content-center align-items-end justify-content-xl-center align-items-xl-start" role="button" data-bs-hover-animate="pulse"
                                                            style="height: 30px;padding-top: 0px;padding-bottom: 4px;" type="submit">Leave Group</button>
                                                    	</form>
                                                    	@break
                                            		@endif
                                            		
                                            		@if($loop->last)
                                                		<form action="{{ route('joinGroup') }}" method="post">
                                                		{{csrf_field()}}
                                                        <input hidden value="{{ session()->get('UserID') }}" name="UserID" />
                                                        <input hidden value="{{ $group->getId() }}" name="GroupID" />
                                                        <button class="btn btn-outline-primary text-center justify-content-center align-items-end justify-content-xl-center align-items-xl-start" role="button" data-bs-hover-animate="pulse"
                                                            style="height: 30px;padding-top: 0px;padding-bottom: 4px;" type="submit">Join Group</button>
                                                    	</form>
                                            		@endif
                                            		
                                            @endforeach
                                            
                                            
                                        	
                                        @endif
                                    @endif
                                    </div>
                        	<label style="margin-bottom: 13px;font-size: 20px;margin-left: 13px;margin-top: 22px;">About this group</label>
                        	<p style="height: 179px;width: 784px;margin-left: auto;margin-right: auto;">{{ $group->getDescription() }}</p>
                        </div>
                        
                        <div class="form-group text-left" style="background-color: #ffffff; padding-bottom:12px;">
                        <label style="margin-bottom: 13px;font-size: 20px;margin-left: 13px;margin-top: 22px;">Members</label>
                            @foreach($group->getMembers() as $member)
                            		<br /><a href="{{ route('profile', ['id'=>$member->getId()]) }}" style="color: rgb(113,128,147); padding-left:12px;">
                            		@if($member->getId() == $group->getAdminid())
                            		<i class="fa fa-star-o"></i>
                            		@endif
                            		{{ $member->getFirstname() }} {{ $member->getLastname() }}
                            		</a>
                        	@endforeach
                        </div>
                        
                    </div>
                    
                </div>
        </div>
    </section>
@endsection