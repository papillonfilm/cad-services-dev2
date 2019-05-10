@extends('layouts.template')
@section('pageTitle', 'User Details')
@section('content')
 
<div class='row' >

 
	<div class='col-md-6'>
		<div class='box box-widget widget-user-2'>

			<div class='widget-user-header bg-blue'>
				<div class='widget-user-image'>
					<img class='img-circle' src='{{asset($user->profilePicture)}}' alt='User Avatar'>
				</div>

				<h3 class='widget-user-username'> {{$user->name}} {{$user->initial}} {{$user->lastname}} {{$user->lastname2}} </h3>
				<h5 class='widget-user-desc'> {{$user->email}} </h5>
			</div>
			<div class='box-footer no-padding'>
				<ul class='nav nav-stacked'>
					<li class='spanLine'>Gender: <span class='pull-right '>    {{$user->gender or 'not specified'}} &nbsp; 
						<i class='fa 
								  @if($user->gender == 'M')
									fa-mars  
								  @elseif($user->gender == 'F')				  
								  	fa-venus
								  @endif
														  
					  	'> </i></span></li>
					<li class='spanLine'>Mobile: <span class='pull-right '> {{$user->mobile}} &nbsp; <i class='fa fa-mobile'></i></span></li>
					<li class='spanLine'>Phone: <span class='pull-right '> {{$user->phone}} &nbsp; <i class='fa fa-phone'></i></span></li>
					<li class='spanLine'>Join: <span class='pull-right '> {{date("j-M-Y", strtotime($user->created_at))}} &nbsp; <i class='fa fa-calendar'></i></span></li>
					<li class='spanLine'>Last Login: <span class='pull-right '> {{date("j-M-Y", strtotime($user->lastLogin))}}  &nbsp; <i class='fa fa-calendar'></i></span></li>
					<li class='spanLine'>Account Enable: <span class='pull-right '> {{$user->accountEnable==1?'Yes':'No' }} &nbsp; <i class="fa {{$user->accountEnable==1?'fa-check':'fa-close' }}  "> </i> </span></li>
					<li class='spanLine'>Account Enable Date: <span class='pull-right '> {{date("j-M-Y g:i A", strtotime($user->enableOnDate)) }} &nbsp; <i class="fa fa-calendar }}  "> </i> </span></li>
					<li class='spanLine'>Account Disable Date: <span class='pull-right '> {{date("j-M-Y g:i A", strtotime($user->disableOnDate)) }} &nbsp; <i class="fa fa-calendar"> </i> </span></li>
				 
					<li class='spanLine'>Total Apps: <span class='pull-right '> {{$totalApps or 0}} &nbsp; <i class='fa fa-th-large'></i></span></li>
					<li class='spanLine'>Groups: <span class='pull-right '> {{$totalGroups}} &nbsp; <i class='fa fa-users'></i></span></li>
				</ul>

			</div>
		</div>


		<div class='box   box-solid  '>
			<div class='box-header with-border  '>
				<h3 class='box-title '>Applications with Access</h3>

			</div> 
			<div class='box-body no-padding '>
				 <ul class='nav nav-stacked'>
						<li class='spanLine'>Applications: &nbsp; <i>
								@if(isset($applications))
									@foreach($applications as $app)
										{{ $loop->first ? '' : ', ' }}
										{{$app->name}}
									@endforeach
								@endif
							
							</i></span></li>
						<li class='spanLine'> </li>
					</ul>
			</div> 

		</div> 

	 </div>

	<div class='col-md-6'>

		<div class='box   box-solid  '>
			<div class='box-header with-border  '>
				<h3 class='box-title '>Groups Membership</h3>

			</div> 
			<div class='box-body no-padding '>
				 <ul class='nav nav-stacked'>
						<li class='spanLine'>Groups: &nbsp; <i>
						@if(isset($groups))
					 		@foreach($groups as $g)
								{{ $loop->first ? '' : ', ' }}
					 			{{$g->name}}
					 		@endforeach
					 	@endif
						   </i> </li>
						<li class='spanLine'> </li>


					</ul>
			</div> 

		</div> 

	</div>

	<div class='col-md-6'>
		<div class='box   box-success   '>
				<div class='box-header with-border  '>
					<h3 class='box-title '>Add to Group</h3>

				</div> 
				<form id='form1' name ='form1' method="post" action="{{route('addUserToGroup')}}">
					<div class='box-body  '>
						 <ul class='nav nav-stacked'>
								<li> 

									<div class='form-group'>
								   <label> Add Access to Group   </label>
								   
									  <select class='form-control' name='groupId'>
										  @if(isset($notInGroups))
										  	@foreach($notInGroups as $nig)
										  		<option value='{{$nig->id}}'> {{$nig->name}} </option>
										  	@endforeach
										  @endif
										 
									  </select>
									</div>

								 </li>
								 {{ csrf_field() }}
							 	<input type='hidden' value='{{$user->id}}' name='userId'  >
							 	<input type="submit" value="Add To Group" class="btn btn-success btn-block">
								 



							</ul>
					</div> 
				</form>
		</div> 

		<!-- remove -->

		<div class='box   box-danger  '>
			<div class='box-header with-border  '>
				<h3 class='box-title '>Remove from Group </h3>

			</div> 
			<form id='form2' name ='form2' method="post" action="{{route('removeUserFromGroup')}}">
				<div class='box-body   '>
					<ul class='nav nav-stacked'>	
						<li> 
							<div class='form-group'>
								<label> Remove User from Group  </label>
								
								<select class='form-control' name='groupId'>
									@if(isset($groups))
										  	@foreach($groups as $g)
										  		<option value='{{$g->id}}'> {{$g->name}} </option>
										  	@endforeach
										  @endif
								 
								</select>
							</div>



						</li>
						{{ csrf_field() }}
						<input type='hidden' value='{{$user->id}}' name='userId'  >
						<input type="submit" value="Remove From Group" class="btn btn-danger btn-block">
						  
						</ul>
				</div> 
			</form>


		</div> 
		
		<div class='box   box-warning  '>
			<div class='box-header with-border  '>
				<h3 class='box-title '>User Permisions</h3>

			</div> 
			<form id='form3' name ='form3' method="post" action="{{  route('user.permissions')  }}">
				<div class='box-body   '>
					  
					{{ csrf_field() }}
					<input type='hidden' value='{{$user->id}}' name='userId'  >
					<input type="submit" value="Manage Permissions" class="btn btn-warning btn-block">
					</form>
				</div> 
			</form>


		</div> 

		<!-- end Remove box -->



	</div>


 
  
 
</div>
		 
@endsection
