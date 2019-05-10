@extends('layouts.template')
@section('pageTitle', 'Sync Routes')
@section('content')
 
	 
 

	<div class='box box-solid '>
		<div class='box-header with-border'>
			<h3 class='box-title'>  Routes </h3> 
			 
		</div>
		<div class='box-body'>
				<table class="table table-bordered table-hover responsive nowrap" width="100%" id='table'>
					<thead>
						<tr>
							<th>Url</th>
							<th>Permission Enforce</th>
							<th>Added </th>
						</tr>
					</thead>
					<tbody>
						@foreach($syncRoutes as $route)
						<tr>
							<td> {{ $route['url'] }}  </td> 
							<td> {{ $route['sync'] }} </td>
							<td> {!! $route['added'] !!} </td>
						</tr>
						@endforeach 
					</tbody>
					
					
				 

				</table>

		</div>
	</div>

	 
 
@endsection
@section('javascript')

<script type="text/javascript"  >
	 
	$(document).ready(function(){

		$('#table').DataTable({
			'pageLength':50,
			'responsive':true,
			'searching':false
		});
		
	});
	
</script>
@endsection
