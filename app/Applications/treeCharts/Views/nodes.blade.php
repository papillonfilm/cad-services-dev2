@extends('layouts.template')
@section('pageTitle', 'Charts')
@section('content')
 

	 
 
<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'> Add Nodes </h3>
			</div>
			<div class="box-body">
				<form method="post" action="{{route('treeCharts.addNode')}}" id='formadd'>
					
					<div class='form-group'>
						<label for='parent' class=''>Parent</label>
						<select class="form-control" name="parent" placeholder="Parent Node"  >
							@if($nodes )
								@foreach($nodes as $key=>$node)
									<option value='{{$key}}'>  {{$node}} </option>
								@endforeach
							@else
								<option value='0'>  Root Node </option>
							@endif
						</select>

					</div>
					
					
					
					<div class='form-group'>
						<label for='name' class=''>Name</label>
						<input type='text' class='form-control' name='name' placeholder='Node Name' value=''>

					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Color</label>
						<select class="form-control" name="color" placeholder="Colors"  >
							 
							<option value='#3c8dbc' {{ old('color') =="#3c8dbc"? 'selected':'' }}> Default </option>
							<option value='#00a65a' {{ old('color') =="#00a65a"? 'selected':'' }}> Green </option>
							<option value='#dd4b39' {{ old('color') =="#dd4b39"? 'selected':'' }}> Red </option>
							<option value='#f39c12' {{ old('color') =="#f39c12"? 'selected':'' }}> Orange </option>
							
							 
							
							 
						</select>
					</div>

					<div class='form-group'>
						  {!! csrf_field() !!}
						<input type="hidden" name='id' value="{{$chart->id}}">
						 
						<a onClick="$('#formadd').submit()" class='btn btn-success'><i class='fa fa-plus'></i> Add Node</a>
					</div>
					
					
 				</form>
			
			</div>
		 </div>
	</div>
 
</div>
 
<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'> Update Node Color </h3>
			</div>
			<div class="box-body">
				<form method="post" action="{{route('treeCharts.updateColor')}}" id='updateColor'>
					
					<div class='form-group '>
						<label for='parent' class=''>Node</label>
						<select class="form-control" name="node" placeholder="node"  >
							 
							@foreach($nodes as $key=>$node)
								<option value='{{$key}}'>  {{$node}} </option>
							@endforeach
						</select>

					</div>
					
					<div class='form-group '  >
						<label for='parent' class=''>Color</label>
						<select class="form-control" name="color" placeholder="Colors"  >
							 
							 
							
							<option value='#3c8dbc' > Default </option>
							<option value='#00a65a' > Green </option>
							<option value='#dd4b39' > Red </option>
							<option value='#f39c12' > Orange </option>
							
							 
						</select>

					</div>
					

					<div class='form-group '>
						 
						  {!! csrf_field() !!}
						<input type="hidden" name='id' value="{{$chart->id}}">
						 <a onClick="$('#updateColor').submit()" class='btn btn-primary'> <i class='fa fa-paint-brush'></i> Update Color</a>
						 
						 
						
					</div>
					
 				</form>
			
			</div>
		 </div>
	</div>
 
</div>
 

<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'> Edit Node  </h3>
			</div>
			<div class="box-body">
				<form method="post" action="{{route('treeCharts.editNode')}}" id='editNode'>
					
					<div class='form-group  '>
						<label for='parent' class=''>Node</label>
						<select class="form-control" name="node" placeholder="node"  >
							 
							@foreach($nodes as $key=>$node)
								<option value='{{$key}}'>  {{$node}} </option>
							@endforeach
						</select>

					</div>
					
					 
					

					<div class='form-group'>
						  {!! csrf_field() !!}
						<input type="hidden" name='id' value="{{$chart->id}}">
					 
						<a onClick="$('#editNode').submit()" class='btn btn-primary'> <i class='fa fa-pencil'></i> Edit Node</a>
					</div>
 				</form>
			
			</div>
		 </div>
	</div>
 
</div>

<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'> Delete Node  </h3>
			</div>
			<div class="box-body">
				<form method="post" action="{{route('treeCharts.deleteNode')}}" id='formDelete'>
					
					<div class='form-group  '>
						<label for='parent' class=''>Node</label>
						<select class="form-control" name="node" placeholder="node"  >
							 
							@foreach($nodes as $key=>$node)
								<option value='{{$key}}'>  {{$node}} </option>
							@endforeach
						</select>

					</div>
					
					 
					<div class='form-group'>
						 This will delete the node and all child elements associated with it.
					 
					</div>

					<div class='form-group'>
						  {!! csrf_field() !!}
						<input type="hidden" name='id' value="{{$chart->id}}">
						<a onClick="$('#formDelete').submit()" class='btn btn-danger'><i class='fa fa-trash'></i> Delete Node </a>
					 
					</div>
 				</form>
			
			</div>
		 </div>
	</div>
 
</div>
	 
 
@endsection