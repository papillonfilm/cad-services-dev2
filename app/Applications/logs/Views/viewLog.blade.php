@extends( 'layouts.template' )
@section('pageTitle', 'View Log')
@section( 'content' )
 <div class='row'>
	<div class='col-md-12'>
		<div class='box  box-solid'>
			<div class='  box-body'>
				<ul class='nav nav-stacked'>
					<li class='spanLine'> Date: <span class='pull-right'> {{$log->created_at}}</span>
					</li>
					<li class='spanLine'> Type:<span class='pull-right'>{{$log->type}}</span>
					</li>
					<li class='spanLine'> Category:<span class='pull-right'> {{$log->category}}</span>
					</li>
					<li class='spanLine'> User: <span class='pull-right'> 
						{{$user->name or ''}} {{$user->initial or ''}} {{$user->lastname or ''}} {{$user->lastname2 or ''}} 
						</span>
					</li>
					<li class='spanLine'> Page:<span class='pull-right'> {{$log->url}}</span>
					</li>
					<li class='spanLine'> IP Address:<span class='pull-right'> {{$log->ip}} </span>
					</li>
					<li class='spanLine'> UserAgent<span class='pull-right'> {{$log->userAgent}} </span>
					</li>
					<li class='spanLine'> Description:<span class='pull-right'> {{$log->description}}</span>
					</li>
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