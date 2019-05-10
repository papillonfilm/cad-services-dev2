@extends('layouts.template')
@section('pageTitle', 'Charts')
@section('content')
 

	 
 
<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'>Edit Node [ {{$node->name}} ] </h3>
			</div>
			<div class="box-body">
				<form method="post" action="{{route('treeCharts.updateNode')}}">
					<div class='form-group'>
						<label for='name' class=''>Node Name</label>
						<input type='text' class='form-control' name='name' placeholder='Group Name' value='{{$node->name}}'>

					</div>
					
					<div class='form-group'>
						<label for='parent' class=''>Parent</label>
						<select class="form-control" name="parent" placeholder="Parent Node"  >
							@if($tree and $node->parentId != 0 )
								@foreach($tree as $key=>$n)
									<option value='{{$key}}'  {{ $key == $node->parentId ?'selected':'' }} >  {{$n}} </option>
								@endforeach
							@else
								<option value='0'>  Root Node </option>
							@endif
						</select>

					</div>


					<div class='form-group '  >
						<label for='parent' class=''>Color</label>
						<select class="form-control" name="color" placeholder="Colors"  >
							 
							<option value='#3c8dbc' {{ $node->color =="#3c8dbc"? 'selected':'' }}> Default </option>
							<option value='#00a65a' {{ $node->color =="#00a65a"? 'selected':'' }}> Green </option>
							<option value='#dd4b39' {{ $node->color =="#dd4b39"? 'selected':'' }}> Red </option>
							<option value='#f39c12' {{ $node->color =="#f39c12"? 'selected':'' }}> Orange </option>
							
							 
						</select>

					</div>
					

					<div class='form-group'>
						 {!! csrf_field() !!}
						<input type="hidden" name='id' value="{{$node->chartId}}">
						<input type="hidden" name='node' value="{{$node->id}}">
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
