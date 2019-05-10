@extends('layouts.template')
@section('pageTitle', 'Group')
@section('content')
 
	 
	<div class='box box-solid '>
		<div class='box-header with-border'>
			<h3 class='box-title'>  Groups </h3> 
			<a href='{{route('addGroup')}}' class='btn btn-sm btn-success '  style='float:right'>Add Group</a>
		</div>
		<div class='box-body'>
			<table id="tableGroups" class="table table-bordered table-hover responsive nowrap" width="100%"> 
				<thead>
					<tr>
						<th data-priority="1"> Name </th>
						<th> Description </th>
						<th data-priority="2"> Action </th></tr>
				</thead>
				<tbody>
					@foreach ($groups as $group)
					<tr>
						<td> <a href= "{{route('viewGroup', ['id'=>$group->id]) }}"  > {{$group->name}} </a> </td>
						<td>{{$group->description}}</td>
						<td align="center">
							
							<div class="btn-group" style="min-width: 69px;">
								<button type="button" class="btn btn-default btn-xs">Action</button>
								<button aria-expanded="false" type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{route('editGroup', $group->id)}}"><i class="fa fa-pencil"></i> Edit</a></li>
									<li><a href="{{route('deleteGroup', $group->id)}}"><i class="fa fa-trash"></i> Delete</a></li>

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
			'responsive':true
		});
		
	});
	
</script>
@endsection
