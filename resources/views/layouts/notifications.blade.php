<?php
	$notifications = notifications();
?>


@if($notifications['access'] == 1)
  <li class="dropdown user user-menu">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<i class='fa fa-bell'></i>
			@if($notifications['total'] > 0)
				<span class="label label-warning notificationTotal" id='notificationTotal' style="top:9px; right: 7px;position: absolute;padding: 2px 3px;font-size: 9px;">{{ $notifications['total'] }}</span>
			@endif

		</a>
		<ul class="dropdown-menu"  >
 
			@if( $notifications['total'] > 0 )
				@foreach($notifications['notifications'] as $alert)
					<li style=" 
							   width: 100%;
							   padding-left: 10px;
							   padding-right: 10px;
							    border-bottom: 1px #eee solid;
							   " class= "notificationList" >

						 <div  style="padding: 0px;">
							<a href="#" class="close" style='float: right; opacity.4; ' onClick="readNotification({{$alert['id']}}, this)" >×</a>

							 {{ $alert['notification'] }} <br>
							 <div style="font-size: 11px; text-align: right;color: #969696;  "  >
							 <i>{{ date("j-M-Y g:i a",strtotime($alert['created_at'])) }}</i> 
							 </div>
						 </div>

					</li>	 

				@endforeach
			@else
				<li style=" 
							   width: 100%;
							   padding-left: 5px;
							   padding-right: 5px;
							   padding-top: 5px;
							   ">

						 <div style=" padding: 10px !important;  background-color: #fefefe;width: 100%;  ">
							<center> No New Notifications</center>
						 </div>

					</li>	

			@endif



			<li class="user-footer">
				<div >
					<a href="{{route('notifications')}}" class="btn btn-default btn-flat btn-block "   >View all notifications</a>
				</div>
			</li>
		</ul>
	</li>
@endif