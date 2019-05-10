@extends('layouts.template')
@section('pageTitle', 'Edit Route')
@section('content')
 


<form role='form' id ='form1' name='form1' action="{{route('permission.route.update')}}" method="post">
	<div class='row' >
		 <div class='col-md-12'>
		<!-- Horizontal Form -->
			<div class='box box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>Route Information</h3>
				</div>


				<div class=' box-body'>
						<div class='form-group'>
						<label for='name' class=''>Applicatin Name</label>
						<input type='text' class='form-control' name='applicationName' placeholder='Application Name' value='{{$route->applicationName}}'>

					</div>

					<div class='form-group'>
						<label for='name' class=''>Url</label>
						<input type='text' class='form-control' name='url' placeholder='url' value='{{$route->url}}'>

					</div>

					<div class='form-group'>
						<label for='name' class=''>Description</label>
						
						<textarea class='form-control' name='description' rows='3' placeholder='Enter description...'>{{$route->description}}</textarea>
					</div>
					<div class='form-group'>
						 @csrf()
						<input type='hidden' name='id' value="{{$route->id}}" >
						<input type='submit' class='btn btn-success'  value='Save Changes' name='button'>

					</div>
					
			 </div>
		</div>
	</div>
</form>

	 
 
		

	 
 
@endsection
