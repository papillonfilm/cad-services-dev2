@extends('layouts.template')
@section('pageTitle', 'Charts')
@section('content')
 

	 
 
<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'>Charts </h3>
			</div>
			<div class="box-body">
				<form method="post" action="{{route('treeCharts.update')}}">
					<div class='form-group'>
						<label for='name' class=''>Chart Name</label>
						<input type='text' class='form-control' name='name' placeholder='Group Name' value='{{$chart->name}}'>

					</div>


					<div class='form-group'>
						<label for='name' class=''>Description</label>
						<textarea class='form-control' name='description' rows='3' placeholder='Enter description...'>{{$chart->description}}</textarea>

					</div>

					<div class='form-group'>
						 {!! csrf_field() !!}
						<input type="hidden" name='id' value="{{$chart->id}}">
						<input type="submit" value="Save Changes" class='btn btn-primary'>
					</div>
 				</form>
			
			</div>
		 </div>
	</div>
 
</div>
 
 

	 
 
@endsection

@section('javascript')
 
@endsection
