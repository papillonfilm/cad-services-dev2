@extends( 'layouts.template' )
@section('pageTitle', 'Mail Detail')
@section( 'content' )
	<div class='row'>
		<div class='col-md-12'>
			<div class='box  box-solid'>
				<div class='  box-body'>
					<ul class='nav nav-stacked'>
						<li class='spanLine'> Date Sent: <span class='pull-right'>   {{$log->created_at}} </span></li>
						<li class='spanLine'> Email To: <span class='pull-right'>    {{$log->email}} </span></li>
						<li class='spanLine'> Email From: <span class='pull-right'>  {{$log->fromEmail}} </span></li>
						<li class='spanLine'> Subject: <span class='pull-right'>     {{$log->subject}}  </span></li>
						<li class='spanLine'> Opened Times: <span class='pull-right'>{{$log->openedTimes}} </span></li>
						<li class='spanLine'> Last Opened Date: <span class='pull-right'>{{$log->lastOpenedDate}} </span></li>
						<li class='spanLine'> IP Address: <span class='pull-right'>  {{$log->ip}} </span></li>
						<li class='spanLine'> User Agent: <span class='pull-right'>  {{$log->userAgent}} </span></li>
						<li class='spanLine'> Message: <br><span  > {!! $email !!} </span> 
						
							<br>
							<span style="float:right">
								<a href="{{route('resendEmail', $log->id)}}" id="btn" class="btn btn-primary"  ><i class="fa fa-paper-plane"></i> Re-Send Email</a> 
							</span>

							
						</li>
						 
					</ul>
				</div>
			</div>
		</div>
	</div>
@endsection