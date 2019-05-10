@extends('layouts.template')
@section('pageTitle', 'Import Users')
@section('content')
 
	 
	<div class='box box-solid '>
		<div class='box-header with-border'>
			<h3 class='box-title'>  Local Users not in Active Directory </h3> 
			 
		</div>
		<div class='box-body'>
			<table id="tableUsers" class="table table-bordered table-hover responsive nowrap" width="100%"> 
				<thead>
					<tr>
						<th data-priority="1"> Name </th>
						<th> Email </th>
						<th data-priority="2"> Disable </th>
					 
				</thead>
				<tbody>
					@foreach ($users as $user)
					<tr>
						<td> {{$user['name'] or ''}} {{$user['initial'] or ''}} {{$user['lastname'] or ''}} {{$user['lastname2'] or ''}} </td>
						<td>{{$user['email'] or ''}}</td>
						<td align="center"><a href='{{route('importAdUser',$user['username'])}}' class='btn btn-xs btn-primary'> <i class='fa fa-download'></i> &nbsp; Import </a></td>
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