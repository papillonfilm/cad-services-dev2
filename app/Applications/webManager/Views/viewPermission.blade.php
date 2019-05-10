@extends('layouts.template')
@section('pageTitle', 'Route Details')
@section('content')
 
	 
<div class='row' >
	  

	<div class='col-md-12'>
		<div class='box box-widget  '>
			<!-- Add the bg color to the header using any of the bg-* classes -->
			 
			<div class='box-footer no-padding'>
				<ul class='nav nav-stacked'>
					
					<li class='spanLine'>Application Name: &nbsp; <span class='pull-right '>  {{$permission->applicationName}}    </span>  </li>
					<li class='spanLine'>Url: &nbsp; <span class='pull-right '>  {{$permission->url}}    &nbsp; <i class='fa fa-key'></i></span>  </li>
					
					<li class='spanLine'>Description: &nbsp; <i>{{$permission->description}} </i> </li>
					 
					
					<li class='spanLine'>Creation Date &nbsp; <span class='pull-right '>  
						{{ \Carbon\Carbon::parse($permission->created_at)->format('j-M-Y')}} &nbsp; <i class='fa fa-calendar'></i></span>
					</li>
					 
				 

				</ul>

			</div>
		</div>
	</div>



 


 
</div>
		

	 
 
@endsection
