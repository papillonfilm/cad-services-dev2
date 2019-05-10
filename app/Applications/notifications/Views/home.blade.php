@extends('layouts.template')
@section('pageTitle', 'Notifications')
@section('content')
 
	 
	<div class='box box-solid '>
		<div class='box-header with-border'>
			<h3 class='box-title'> Notifications </h3> 
		</div>
		<div class='box-body'>
			
			@foreach($notification as $n)
				
			 
				<div class="{{ $n->readed=='1'?"notificationBoxReaded":"notificationBox"}} "  >
					<span class="notificationMenu">
						@if($n->readed == '1')
							<a href='{{route('removeNotification',['id'=>$n->id])}}' class="btn btn-danger btn-xs"  > <i class="fa fa-trash"></i> Delete </a> 
						@else
							<a href='{{route('acknowledgeNotification',['id'=>$n->id])}}'  class="btn btn-primary btn-xs"> <i class="fa fa-check-square-o "></i> Acknowledge </a><br>
							<a href='{{route('removeNotification',['id'=>$n->id])}}' class="btn btn-danger btn-xs" style="margin-top: 5px"> <i class="fa fa-trash"></i> Delete </a> 
						@endif
					
					</span>
					 
					{{$n->notification}} <br>&nbsp; <span style="font-size: 11px; "><i>{{$n->created_at}}</i></span>
				</div>
			@endforeach
			
			
			 

		</div>
	</div>
		
 
 
@endsection
@section('javascript')

<script type="text/javascript"  >
	 
	$(document).ready(function(){

		$('#tableUsers').DataTable({
			'pageLength':50,
		});
		
	});
	
</script>
@endsection