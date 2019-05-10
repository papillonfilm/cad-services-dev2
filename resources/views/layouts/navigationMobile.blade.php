	

@if (Auth::check())
<ul class='sidebar-menu'>
	 
	 
 
	 
	@foreach( getApplications() as  $category=>$apps)
		<li class='header menuItemHeader'> {{$category}} </li>
		@foreach($apps as $app)
			<li> 
			@if(\Route::getRoutes()->hasNamedRoute($app['location']))
				<a href='{{route($app['location'])}}'>
					<img src="{{asset($app['iconPath'])}}" height="14px" > &nbsp;
					<span> {{$app['name']}}</span> 
				</a>  
				@if(isset($subNavigation[$app['name']]))
						
						 
							@foreach($subNavigation[$app['name']] as $nav)
								<li class='  ' >
								 
										<a href="{!! $nav['link'] !!}" style="padding-left: 30px" >
											<i class='fa fa-angle-right' style="width: 10px"></i>{{$nav['title']}} 
										</a>
									 
								</li>
							@endforeach
						 
						
						@endif
				@else
				<a href='#noRoute'>
					<img src="{{asset($app['iconPath'])}}" height="14px" > &nbsp;
					<span> {{$app['name']}}</span> 
				</a>  
			@endif
			</li>
		 @endforeach

	@endforeach

 


	<li><a href='{{route('logout')}}'><i class="fa fa-sign-out"></i><span>Logout</span> </a>  </li>
</ul>

 

@else

<ul class='sidebar-menu'>
	<li>   <a href='{{route('login')}}'><i class="fa fa-sign-in">  </i> <span>Login</span> </a>  </li>
</ul>

@endif