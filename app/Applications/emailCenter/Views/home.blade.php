@extends('layouts.template')
@section('pageTitle', 'Email Center')
@section('content')
 
	 
<div class='box box-solid '>
	<div class='box-header with-border'>
		<h3 class='box-title'>  Email Center </h3>  
		 
	</div>
	<div class='box-body'>
 
		<form method="post" action="{{route('sendMessage')}}" id='form1'>

			 

			<div class='form-group'>
				<label> Target  </label>
				<select class='form-control' name='target' id='target'>
					<option value="0" {{ old('target')=="0"?"selected='selected'":'' }} >--Select Target--</option>
					<option value="1" {{ old('target')=="1"?"selected='selected'":'' }}>User</option>
					<option value="2" {{ old('target')=="2"?"selected='selected'":'' }}>Group</option>
					<option value="3" {{ old('target')=="3"?"selected='selected'":'' }}>Application</option>
				</select>
			</div>
			
			
			
			<div class='form-group' id='targetUser'>
				<label> Target User </label>
				<select class='form-control' name='userId' id='userId'>
					<option value="">--Select User--</option>
					@if(isset($users))
						@foreach($users as $user)
							<option value='{{$user->id}}' {{ $user->id==old('userId')?"selected='selected'":'' }} >{{$user->name}} {{$user->initial or ''}} {{$user->lastname or ''}} {{$user->lastname2 or ''}} </option>
						@endforeach
					@endif
				</select>
			</div>
			
			<div class='form-group' id='targetGroup'>
				<label> Target Group </label>
				<select class='form-control' name='groupId'>
					<option value="">--Select Group--</option>
					@if(isset($groups))
						@foreach($groups as $group)
							<option value='{{$group->id}}'  {{ $group->id==old('groupId')?"selected='selected'":'' }}>{{$group->name}}   </option>
						@endforeach
					@endif
				</select>
			</div>
			
			<div class='form-group' id='targetApplication'>
				<label> Target Application </label>
				<select class='form-control' name='applicationId'>
					<option value="">--Select Application--</option>
					@if(isset($applications))
						@foreach($applications as $application)
							<option value='{{$application->id}}' {{ $application->id==old('applicationId')?"selected='selected'":'' }} >{{$application->name}}   </option>
						@endforeach
					@endif
				</select>
			</div>
			

		 <div class='form-group'>
			  <label for='author'>Subject</label>
			  <input class='form-control' name='subject' placeholder='Subject' type='text' value="">
			</div>

			 

			<div class='form-group'>
				<label for='name' class=''>Message</label>
				<textarea class='form-control' name='message' rows='5' placeholder='Enter message...'>{{old('message')}}</textarea>

			</div>


			{!! csrf_field() !!}
			 
		 
			<a id='btnSubmit' class='btn btn-primary'><i class='fa fa-paper-plane'></i> Send Emails</a>

		</form>
		 
		
	</div>
</div>  
		
 
 
@endsection

@section( 'javascript' )

<script type="text/javascript">
	
	$(document).ready(function(){
		
		
		$("#targetUser").hide();
		$("#targetGroup").hide();
		$("#targetApplication").hide();
		

		$("#target").change(function(){
			displayTarget();
		});
		
		
		function displayTarget(){
			if( $("#target").val() == 1  ){
			   // User
				$("#targetUser").slideDown();
				$("#targetGroup").slideUp();
				$("#targetApplication").slideUp();
			}else if( $("#target").val() == 2 ){
				// Group
				$("#targetUser").slideUp();
				$("#targetGroup").slideDown();
				$("#targetApplication").slideUp();
			}else if($("#target").val() == 3  ){
				// Application
				$("#targetUser").slideUp();
				$("#targetGroup").slideUp();
				$("#targetApplication").slideDown();
			}else{
				$("#targetUser").slideUp();
				$("#targetGroup").slideUp();
				$("#targetApplication").slideUp();
			}
		}
		
		// set defaults
		displayTarget();
		
		
		$('#btnSubmit').click(function(){
			$('#form1').submit();
		});
		
	});
	
	
	
	
</script>

@endsection
 