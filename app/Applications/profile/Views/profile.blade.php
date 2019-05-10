@extends('layouts.template')
@section('pageTitle', 'Profile')
@section('content')
 
	 
<div class='row'> 
	<div class='col-md-6 '> 
		<div class='box box-solid'>
			<div class='box-body box-profile'>
				<img class='profile-user-img img-responsive img-circle bigProfile' src='{{ asset($user->profilePicture) }}' alt='User profile picture'>

				<h3 class='profile-username text-center'>{{ $user->name }} {{$user->initial}} {{$user->lastname}} {{$user->lastname2}}</h3>



				<ul class='list-group list-group-unbordered'>
					<li class='list-group-item'>
					<b>Email</b> <a class='pull-right'>{{$user->email}}</a>
					</li>
					<li class='list-group-item'>
						<b>Member Since</b> <a class='pull-right'>{{ date("j-M-Y", strtotime($user->created_at))}}</a>
					</li>
					<li class='list-group-item'>
						<b>Last Login</b> <a class='pull-right'>{{ date("j-M-Y g:i A", strtotime($user->lastLogin))}}</a>
					</li>

					<li class=' list-group-item' id='changePass' style='display:none'>
					<form role='form' id='form2' name='form2' method="post" action="{{route('updatePassword')}}">

						<div class='form-group'>
							<label for='Current Password' class=''>Current Password</label>
							<input class='form-control' name='currentPassword' placeholder='Current Password' type='password'   >

						</div>

						<div class='form-group'>
							<label for='New Password' class=''>New Password</label>
							<input class='form-control' name='password' placeholder='New Password' type='password'  >
						</div>

						<div class='form-group'>
							<label for='Confirm Password' class=''>Confirm Password</label>
							<input class='form-control' name='password_confirmation' placeholder='Confirm Password' type='password'  >
						</div>

						<div class='form-group' style='text-align:center'>
							<input type='submit' value="Change Password" class="btn btn-success"> &nbsp; &nbsp; 
							<span class='btn btn-danger' onclick='cancel();'> Cancel </span> 
							
							 
							{!! csrf_field() !!} 
							{{ method_field('PUT') }}
							
						</div>


						</form>

					</li>

					<!-- profile Picture -->
					<li class=' list-group-item' id='picUpload' style='display:none'>
					<form role='form' enctype='multipart/form-data' method='post' action="{{route('updateProfilePicture')}}">

						<div class='form-group'>
							<label for='Select Image' class=''>Select Image</label>
							<input class='btn-sm  ' style ='height:20px:' name='profilePicture' id='profilePicture' placeholder='Select Image' type='file' xaccept='image/*'   >

						</div>



						<div class='form-group' style='text-align:center'>
							<input type='submit' value="Upload Image" class="btn btn-success">
							&nbsp; &nbsp; <span class='btn btn-danger' onclick='cancelUpload();'> Cancel </span>  
							

						</div>
						
							{!! csrf_field() !!} 
							{{ method_field('PUT') }}

						</form>

					</li>
					<!-- end profile Picture-->


					<li class='list-group-item' style='text-align:center' id='btnChangePass'>
						
						@if(strpos($user->email, '@pbcgov.org') !== false )
						
						@else
							<span class='btn btn-primary' onclick='showPass();' > Change Password </span> 
						@endif
						 
						
						<span class='btn btn-primary' onclick='profilePic();' > Change Profile Picture </span> 
					</li>
				</ul>

			</div>
		</div>
	</div>

	<!-- Update Info -->
	<div class='col-md-6'>
		<form role='form' id='form1' name='form1' action="{{route('saveProfile')}}" method='post' >
			 
			<div class='box box-solid'>
			  <div class='box-header with-border'>
				<h3 class='box-title'> My Information </h3>
			  </div> 

			  <div class='box-body'>

					<div class='form-group'>
						<label for='name' class=''>Name</label>
						<input class='form-control' name='name' placeholder='Name' type='text' value='{{$user->name}}'>
					</div>

					<div class='form-group'>
						<label for='name' class=''>Initial</label>
						<input class='form-control' name='initial' placeholder='Initial' type='text' value='{{$user->initial or ''}}'>
					</div>

					<div class='form-group'>
						<label for='name' class=''>Lastname</label>
						<input class='form-control' name='lastname' placeholder='Lastame' type='text' value='{{$user->lastname}}'>
					</div>

					<div class='form-group'>
						<label for='name' class=''>Second Lastname</label>
						<input class='form-control' name='lastname2' placeholder='Second Lastame' type='text' value='{{$user->lastname2}}'>
					</div>
				  
				  	<div class='form-group' style="margin-bottom: 0px;">
						 <label for='name' class=''>Gender</label> 
						 
					</div>
				  
				 	 <div class='form-group input-group'>
						
						<span class='input-group-addon'><i class='fa fa-mars ' id='genderTag'></i></span>
						<select class='form-control' name='gender' placeholder='Gender' id='gender'>
							<option value='M' {{ $user->gender =='M'?'selected':'' }}>Male</option>
							<option Value='F' {{ $user->gender =='F'?'selected':'' }}>Female</option>
							<option Value=''  {{ $user->gender ==''?'selected':'' }}>Not Specified</option>

						</select>
					</div>

					<div class='form-group'>
						<label for='name' class=''>Phone</label>
						<input class='form-control' name='phone' id='phone' placeholder='Phone' type='text' value='{{$user->phone}}'>
					</div>

					<div class='form-group'>
						<label for='name' class=''>Mobile</label>
						<input class='form-control' name='mobile' id='mobile' placeholder='Mobile' type='text' value='{{$user->mobile}}'>
					</div>

					<div class='form-group'>
						<label>Date of Birth</label>
						<div class='input-group date'>
							<div class='input-group-addon'>
							<i class='fa fa-calendar'></i>
							</div>
							<input class='form-control pull-right' id='birthdate' name='birthdate' type='text' value='{{date("m/d/Y", strtotime($user->birthDate))}}'>
						</div>
					</div>


			  </div>

			  <div class='box-footer'>
			 	  {!! csrf_field() !!} 
				   {{ method_field('PUT') }}
				  <input type="submit" class="btn btn-primary btn-block" value="Update info" >
			  </div>

			</div>
		</form>
	</div>
		
		
</div>
		
 
 
@endsection
@section('css')
	<link rel='stylesheet' href='/css/bootstrap-datepicker3.css'>
@endsection
@section('javascript')
<script src="/js/jquery.maskedinput.min.js"></script>
<script src="/js/bootstrap-datepicker.js"></script>
<script type="text/javascript"  >
	 
	$(document).ready(function(){
			 
		$('#birthdate').datepicker({
			  autoclose: true
		});

		$('#phone').mask('999-999-9999? x999999');
		$('#mobile').mask('999-999-9999');
		 
	});
	
	
	function showPass(){
		$('#btnChangePass').hide();
		$('#changePass').slideDown();

	}

	function cancel(){

		$('#changePass').slideUp();
		$('#btnChangePass').slideDown();
	}

	function cancelUpload(){
		$('#picUpload').slideUp();
		//$('#changePass').slideUp();
		$('#btnChangePass').slideDown();
	}

	function profilePic(){
		$('#btnChangePass').hide();
		$('#picUpload').slideDown();

	}
	
	$("#gender").change(function(){
		setGenderIcon();
	});
	
	function setGenderIcon(){
		if( $("#gender").val() =="M" ){
			$("#genderTag").removeClass("fa-genderless");
			$("#genderTag").removeClass("fa-mars");
			$("#genderTag").removeClass("fa-venus");
			$("#genderTag").addClass("fa-mars");


		}else if ($("#gender").val() =="F"){
			$("#genderTag").removeClass("fa-genderless");
			$("#genderTag").removeClass("fa-mars");
			$("#genderTag").removeClass("fa-venus");
			$("#genderTag").addClass("fa-venus");

		}else{
			$("#genderTag").removeClass("fa-genderless");
			$("#genderTag").removeClass("fa-mars");
			$("#genderTag").removeClass("fa-venus");
			$("#genderTag").addClass("fa-genderless");
		}
	}

	
	
</script>
@endsection