@extends('layouts.template')
@section('pageTitle', 'Add Category')
@section('content')

<form role='form' name='form1' method='post' action='{{route('categories.saveRecord')}}' > 
	<div class='row'>
		<div class='col-md-12'> 
			<div class='box box-solid'>
				<div class='box-header'>
					<h3>Add Category</h3>
				</div>
				<div class='box-body'>
					<div class='form-group'>
						<label for='name' > Name </label>
 						<input type='text' class='form-control' name='name' id='name' placeholder='Name' value='{{old('name')}}' >
					</div>
					<div class='form-group'>
						<label for='description' > Description </label>
 						<input type='text' class='form-control' name='description' id='description' placeholder='Description' value='{{old('description')}}' >
					</div>
					<div class='form-group'>
						{!! csrf_field() !!}
						<input type='submit' value='Add Category' class='btn btn-primary'>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection
