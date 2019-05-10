@extends('layouts.template')
@section('pageTitle', 'Add Users')
@section('content')
 

	 
<form role='form' id ='form1' name='form1' action='{{route('saveUser')}}' method="post">
<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'>Add User </h3>
			</div>
	
			
				<div class=' box-body'>
					<div class='form-group'>
						<label for='name' class=''>Name</label>
						<input type='text' class='form-control' name='name' placeholder='Name' value='{{old('name')}}'>
					</div>
					
						<div class='form-group'>
						<label for='name' class=''>Initial</label>
						<input type='text' class='form-control' name='initial' placeholder='Initial' value='{{old('initial')}}'>
					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Lastname</label>
						<input type='text' class='form-control' name='lastname' placeholder='Lastame' value='{{old('lastname')}}'>
					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Second Lastname</label>
						<input type='text' class='form-control' name='secondLastname' placeholder='Second Lastame' value='{{old('secondLastname')}}'>
					</div>
					
					<div class='form-group input-group'>
							<span class='input-group-addon'><i class='fa fa-mars ' id='genderTag'></i></span>
							<select class='form-control' name='gender' placeholder='Gender' id='gender'>
								<option value='M' {{ old('gender')=='M'?'selected':'' }}>Male</option>
								<option Value='F' {{ old('gender')=='F'?'selected':'' }}>Female</option>
								<option Value=''  {{ old('gender')==''?'selected':'' }}>Not Specified</option>
								 
							</select>
					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Email</label>
						<input type='text' class='form-control' name='email' placeholder='Email' id='email' value='{{old('email')}}'>
					</div>
					
					<div class='form-group'>
						<label for='username' class=''>Username</label>
						<input type='text' class='form-control' name='username' placeholder='Username' id='username' value='{{old('username')}}'>
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
						<input type='text' class='form-control' name='mobile' placeholder='Mobile Phone' id='mobile' value='{{old('mobile')}}'>
					</div>
				 
							
					<div class='form-group  '>
						<label for='name' class=''>Account Enable</label>
						<select class='form-control' name='accountEnable' placeholder='Account Enable' id='accountEnable' >
							<option value='1' {{ old('accountEnable')==1?'selected':'' }} >Yes</option>
							<option Value='0' {{ old('accountEnable')==0?'selected':'' }} >No</option> 
						</select>
					</div>
					
					<div class='form-group  '>
						<label for='name' class=''>Account Activated</label>
						<select class='form-control' name='accountActivated' placeholder='Account Activated' id='accountActivated'>
							<option value='1' {{ old('accountActivated')==1?'selected':'' }}>Yes</option>
							<option Value='0' {{ old('accountActivated')==0?'selected':'' }}>No</option> 
						</select>
					</div>
					
							
				   
				</div>
				 
				
				
			
		</div>
	</div>
	

	
	
</div>
<div class='row'> 
	<div class='col-md-12'>
		 	{!! csrf_field() !!}
			<input type="submit" value="Add User" class="btn btn-primary"> 
	</div>
</div>
</form>
<br><br>
		

	 
 
@endsection

@section('javascript')
	<script src="/js/jquery.maskedinput.min.js"></script>
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


		$("#birthday").datepicker({
		  autoclose: true
		});

	});
	</script>
@endsection
