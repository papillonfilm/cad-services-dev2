@extends('layouts.template')
@section('pageTitle', 'Email Logs')
@section('content')
 
	 
	<div class='box box-solid '>
		<div class='box-header with-border'>
			<h3 class='box-title'>  Mail Logs </h3> 
		</div>
		<div class='box-body'>
			<table id="tableLog" class="table table-bordered table-hover responsive" style="width: 100%"> 
				<thead>
					<tr>
						<th> Date </th>
						<th> Email </th>
						<th> Subject </th>
						<th> Last Opened Date </th>
					</tr>
				</thead>
				<tbody>
					 
					<tr>
						<td>   </td>
						<td>   </td>
						<td>   </td>
						<td>   </td>				
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
				'url':'{{route('ajaxMailLogs')}}',
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
			'pageLength':50,
			'lengthMenu': [ 10, 25, 50, 75, 100, 200 ],
			'bLengthChange': true,
			'paging':true,
			'info':true,
			'searching':true

		});
	 });
</script>
@endsection