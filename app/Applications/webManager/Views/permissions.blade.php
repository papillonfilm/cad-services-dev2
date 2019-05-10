@extends('layouts.template')
@section('pageTitle', 'Permissions')
@section('content')
 
	 
	<div class='box box-solid '>
		<div class='box-header with-border'>
			<h3 class='box-title'>  Routes (Urls) </h3> 
			<span style='float:right'>
				<a href='{{route('permissions.addRoute')}}' class='btn btn-sm btn-success '  >Add Route</a>
				<a href='{{route('permissions.syncRoutes')}}' class='btn btn-sm btn-primary '  >Sync Routes</a>
			</span>
			
		</div>
		<div class='box-body'>
			<table id="tableGroups" class="table table-bordered table-hover responsive nowrap" width="100%"> 
				<thead>
					<tr>
						<th data-priority="1"> Application Name </th>
						<th data-priority="2"> Url </th>
						<th> Description </th>
						<th data-priority="3"> Action </th></tr>
				</thead>
				<tbody>
					@foreach ($permissions as $permission)
					<tr>
						<td> <a href= "{{route('permissions.view', ['id'=>$permission->id]) }}"  > {{$permission->applicationName or 'Not Defined'}} </a> </td>
						<td>{{$permission->url}}</td>
						<td>{{$permission->description}}</td>
						<td align="center">
							
							<div class="btn-group" style="min-width: 69px;">
								<button type="button" class="btn btn-default btn-xs">Action</button>
								<button aria-expanded="false" type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{route('permissions.edit', $permission->id)}}"><i class="fa fa-pencil"></i> Edit</a></li>
									<li><a href="{{route('permissions.delete', $permission->id)}}"><i class="fa fa-trash"></i> Delete</a></li>

								</ul>
							</div>
							
						
						
						</td>
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

		$('#tableGroups').DataTable({
			'pageLength':50,
			'responsive':true,
			'searching':false,
			'lengthChange':false
			
		});
		
	});
	
</script>
@endsection
