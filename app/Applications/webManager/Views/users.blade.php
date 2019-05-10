@extends('layouts.template')
@section('pageTitle', 'Users')
@section('content')
 
	 
	<div class='box box-solid '>
		<div class='box-header with-border'>
			<h3 class='box-title'>  Users </h3> 
			<a href='{{route('addUser')}}' class='btn btn-sm btn-success '  style='float:right'>Add User</a>
		</div>
		<div class='box-body'>
			<table id="tableUsers" class="table table-bordered table-hover responsive nowrap" width="100%"> 
				<thead>
					<tr>
						<th data-priority="1"> Name </th>
						<th> Email </th>
						<th width='50px' data-priority="2"> Action </th></tr>
				</thead>
				<tbody>
					@foreach ($users as $user)
					<tr>
						<td><a href="{{route('userDetail',['id'=>$user->id])}}" >{{$user->name}} {{$user->initial}} {{$user->lastname}} {{$user->lastname2}}</a></td>
						<td>{{$user->email}}</td>
						<td align="center"> 
							<div class="btn-group" style="min-width: 69px;">
								<button type="button" class="btn btn-default btn-xs">Action</button>
								<button aria-expanded="false" type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{route('editUser', $user->id)}}"><i class="fa fa-pencil"></i> Edit</a></li>


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

		$('#tableUsers').DataTable({
			'pageLength':50,
			'responsive':true
		});
		
	});
	
</script>
@endsection