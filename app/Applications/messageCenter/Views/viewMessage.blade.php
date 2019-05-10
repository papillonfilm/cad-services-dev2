@extends( 'layouts.template' )
@section('pageTitle', 'Message Detail')
@section( 'content' )
 <div class='row'>
	<div class='col-md-12'>
		<div class='box  box-solid'>
			<div class='  box-body'>
				<ul class='nav nav-stacked'>
					<li class='spanLine'> Date: <span class='pull-right'> {{$message->created_at}}</span></li>
					<li class='spanLine'> Name:<span class='pull-right'>{{$message->name}}</span></li>
					
					
					<li class='spanLine'> Target:<span class='pull-right'> {{$target}}</span></li>
					
					
					<li class='spanLine'> Start Date: <span class='pull-right'>{{$message->startDate}} </span></li>
					<li class='spanLine'> End Date: <span class='pull-right'>{{$message->endDate}} </span></li>
					<li class='spanLine'> Show Times:<span class='pull-right'> {{$message->showTimes}}</span></li>
					<li class='spanLine'> Showed Times:<span class='pull-right'> {{$message->showedTimes}}</span></li>
					
					<li class='spanLine'> Created Date:<span class='pull-right'> {{$message->created_at}}</span></li>
				 
					<li class='spanLine'> Message:<span class='pull-right'> {{$message->message}}</span></li>
				</ul>
			</div>
		</div>
	 </div>
</div>
	 
@endsection

@section( 'javascript' )

@endsection

@section( 'css' )

@endsection