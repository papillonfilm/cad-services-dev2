@extends('layouts.template')
@section('pageTitle', 'Edit User')
@section('content')
 
	 
<form role='form' id ='form1' name='form1' method="post" action="{{route('updateUser')}}">
<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid'>
			<div class='box-header '>
				<h3 class='box-title'>Edit User Information</h3>
			</div>
	
			
				<div class=' box-body'>
					<div class='form-group'>
						<label for='name' class=''>Name</label>
						<input type='text' class='form-control' name='name' placeholder='Name' value="{{$user->name}}">
					</div>
					
						<div class='form-group'>
						<label for='name' class=''>Initial</label>
						<input type='text' class='form-control' name='initial' placeholder='Initial' value="{{$user->initial}}">
					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Lastname</label>
						<input type='text' class='form-control' name='lastname' placeholder='Lastame' value="{{$user->lastname}}">
					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Second Lastname</label>
						<input type='text' class='form-control' name='lastname2' placeholder='Second Lastame' value="{{$user->lastname2}}">
					</div>
					
					<div class='form-group input-group'>
							<span class='input-group-addon'><i class='fa fa-mars ' id='genderTag'></i></span>
							<select class='form-control' name='gender' placeholder='Gender' id='gender'>
								<option value='M' {!! $user->gender=="M"?"selected='selected'":'' !!}>Male</option>
								<option Value='F' {!! $user->gender=="F"?"selected='selected'":'' !!}>Female</option>
								<option Value='' {!! $user->gender==""?"selected='selected'":'' !!}>Not Specified</option>
								 
							</select>
					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Email</label>
						<input type='text' class='form-control' name='email' placeholder='Email' id='email' value="{{$user->email}}">
					</div>
					<!--
					<div class='form-group'>
						<label for='name' class=''>Password</label>
						<input type='password' class='form-control' name='password' placeholder='Password'>
					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Re Type Password</label>
						<input type='password' class='form-control' name='password2' placeholder='Re Type Password'>
					</div>
					
					-->
					
					
					<div class='form-group'>
						<label for='name' class=''>Mobile Phone</label>
						<input type='text' class='form-control' name='mobile' placeholder='Mobile Phone' id='mobile' value="{{$user->mobile}}">
					</div>
					<div class='form-group'>
						<label for='name' class=''> Phone</label>
						<input type='text' class='form-control' name='phone' placeholder='Phone' id='phone' value="{{$user->phone}}">
					</div>
					
					
					 <div class='form-group '>
						<label for='name' class=''>Account Enable</label>
						<div class='form-group '>
						 
							<select class='form-control' name='accountEnable'  >
								<option value='1' {!! $user->accountEnable=="1"?"selected='selected'":'' !!}>Yes</option>
								<option Value='0' {!! $user->accountEnable=="0"?"selected='selected'":'' !!}>No</option>
								 
								 
							</select>
						</div>
					</div>
					
					<div class='form-group '>
						<label for='name' class=''>Account Enable Date</label>
						<div class='form-group '>
						 	<input type="text" class="form-control" name='accountEnableDate' id='accountEnableDate' value="{{$user->enableOnDate}}">
							 
						</div>
					</div>
					
					<div class='form-group '>
						<label for='name' class=''>Account Disable Date</label>
						<div class='form-group '>
						 <input type="text" class="form-control" name='accountDisableDate' id='accountDisableDate' value="{{$user->disableOnDate}}">
							 
						</div>
					</div>
					
					
					
					
					<div class='form-group  '>
						<label for='name' class=''>Account Activated</label>
						<select class='form-control' name='accountActivated' placeholder='Account Activated' id='accountActivated'>
							<option value='1' {!! $user->accountActivated=="1"?"selected='selected'":'' !!}>Yes</option>
							<option Value='0' {!! $user->accountActivated=="0"?"selected='selected'":'' !!}>No</option> 
						</select>
					</div>
					
					
					
					
					
					
							
				   
				</div>
				 
				
				
			
		</div>
	</div>
	

	
	
</div>
<div class='row'> 
	<div class='col-md-12'>
			 {!! csrf_field() !!}
			<input type="hidden" name='id' value="{{$user->id}}">
		 	<input type="submit" value="Save Changes" class="btn btn-primary"> 
			 
			 
		
	</div>

</div>

</form>
<br><br>
 
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('/css/bootstrap-datetimepicker.css')}}">
@endsection

@section('javascript')


	<script src="{{asset('/js/jquery.maskedinput.min.js')}}"></script>
	<script src="{{asset('/js/moment-with-locales.js')}}"></script>
	<script src="{{asset('/js/bootstrap-datetimepicker.js')}}"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		setGenderIcon();

		$("#mobile").mask("999-999-9999");
		$("#phone").mask("999-999-9999");

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


		$("#birthday").datetimepicker({
		 
		});
		
		$("#accountEnableDate").datetimepicker({
		 	format: "YYYY-MM-DD HH:mm:ss" 
			 
		});
		
		
		$("#accountDisableDate").datetimepicker({
			 format: "YYYY-MM-DD HH:mm:ss"
		});

	});
		
		
	</script>
@endsection
