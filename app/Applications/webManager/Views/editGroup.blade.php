@extends('layouts.template')
@section('pageTitle', 'Edit Group')
@section('content')

 
<form role='form' id ='form1' name='form1' action ='{{route('updateGroup')}}' method="post">
	<div class='row' >
		 <div class='col-md-12'>
		<!-- Horizontal Form -->
			<div class='box box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>Group Information</h3>
				</div>


					<div class=' box-body'>
						<div class='form-group'>
							<label for='name' class=''>Group Name</label>
							<input type='text' class='form-control' name='name' placeholder='Group Name' value='{{$groups->name}}'>

						</div>
						
						<div class='form-group'>
							<label for='name' class=''>Permission Priority</label>
							<input type='text' class='form-control' name='permissionPriority' placeholder='Permission Priority ex 500' value='{{$groups->permissionPriority}}'>

						</div>

						<div class='form-group'>
							<label for='name' class=''>Description</label>
							<textarea class='form-control' name='description' rows='3' placeholder='Enter description...'>{{$groups->description}}</textarea>

						</div>


					</div>
					<!-- /.box-body -->



			</div>
		</div>




	</div>
	<div class='row'> 
		<div class='col-md-12'>
				 {!! csrf_field() !!}
				<input type="hidden" name='id' value="{{$groups->id}}">
				<input type="submit" value="Save Changes" class='btn btn-primary'>



		</div>

	</div>

</form>
<br><br>

		

	 
 
@endsection
