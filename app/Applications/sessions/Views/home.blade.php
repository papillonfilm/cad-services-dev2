@extends( 'layouts.template' )
@section('pageTitle', 'Active Sessions')
@section( 'content' )
	<div class='row'>
		
		
		 
			 
		<!-- Total Active Sessions -->
		<div class="col-md-6 col-sm-6 col-xs-12">
		  <div class="info-box">
			<span class="info-box-icon bg-green"><i class="fa fa-key fa-rotate-90"></i></span>

			<div class="info-box-content">
			  <span class="info-box-text">Active Sessions</span>
			  <span class="info-box-number" style="font-size: 36px">&nbsp; &nbsp; {{$total}} </span>
			</div>
			<!-- /.info-box-content -->
		  </div>
		  <!-- /.info-box -->
		</div>
		
		
		<!-- Total Active Users -->
		<div class="col-md-6 col-sm-6 col-xs-12">
		  <div class="info-box">
			<span class="info-box-icon bg-orange"><i class="fa fa-user"></i></span>

			<div class="info-box-content">
			  <span class="info-box-text">Active Users</span>
			  <span class="info-box-number" style="font-size: 36px">&nbsp; &nbsp; {{$totalUsers}} </span>
			</div>
			<!-- /.info-box-content -->
		  </div>
		  <!-- /.info-box -->
		</div>
					
					
	</div>
	<div class='row'>
		
		
		<div class='col-md-12'>
			<div class='box  box-solid'>
				<div class='  box-body'>
				 
					<table class="table table-bordered table-hover responsive  " id="table" style="width: 100%">
					<thead>
						<tr>
							<th  data-priority="1">User</th>
							<th  >IP Address</th>
							<th  >Last Activity</th>
							<th  >User Agent</th> 
							<th data-priority="2">Logout User</th> 
						</tr>
					</thead>
					<tbody>
						
					@foreach($sessions as $session)
						<tr>
							<td>{{$session->name}} {{$session->initial or ''}} {{$session->lastname}}</td>
							<td>{{ $session->ip_address}}</td>
							<td>{{date("Y-m-d G:i" ,$session->last_activity)}}</td>
							<td>{{$session->user_agent}}</td> 
							<td align="center"> <a href="{{route('sessionsLogoutUser',['id'=>$session->user_id])}}" class='btn btn-xs btn-danger'><i class='fa fa-sign-out'></i> Logout</a></td>
						</tr>
						
					@endforeach
						
					</tbody>
					</table>
					
					
				</div>
			</div>
		</div>
		
		 
	</div>
@endsection
			
@section('javascript')
<script type="text/javascript"  >
	 
	$(document).ready(function(){

		$('#table').DataTable( {
		 
			'ordering': true,
			'responsive':true,
			 
			'order':[2,'desc'],
			'pageLength': 50,
			'lengthMenu': [ 10, 25, 50, 75, 100, 200, 300, 500, 1000 ],
			'bLengthChange': true,
			'paging':true,
			'info':true,
			'searching':true

		});
	 });
</script>
@endsection