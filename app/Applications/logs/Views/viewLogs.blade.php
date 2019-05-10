@extends('layouts.template')
@section('pageTitle', 'Logs')
@section('content')
 
   
<div class='box box-solid  '>
	<div class='box-header with-border'>
		<h3 class='box-title'>  Logs </h3> 

	</div>
	<div class='box-body'>
		<table class="table table-bordered table-hover responsive nowrap" id="tableLog" style="width: 100%">
			<thead>
				<tr>
					<th>Date</th>
					<th>Type</th>
					<th>Category</th>
					<th>User</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>

		</table>

	</div>
</div>
	
	
 
@endsection

@section('javascript')
<script type="text/javascript"  >
	 
	$(document).ready(function(){

		$('#tableLog').DataTable({

			'ajax': {
				'url':'{{route('showLogs')}}',
				'type':'POST',
				'data':function(d){ 
					d._token = '{{csrf_token()}}'
				}
			},
			'serverSide':true,
			'procesing':true,
			'ordering': true,
			'responsive':true,
			'order':[0,'desc'],
			'pageLength': 50,
			'lengthMenu': [ 10, 25, 50, 75, 100, 200 ],
			'bLengthChange': true,
			'paging':true,
			'info':true,
			'searching':true

		});
	 });
</script>
@endsection
 