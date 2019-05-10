	

@if (Auth::check())

<ul class="nav navbar-nav">
	<li class="treeview menu-item">
		<a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i><span> Dashboard</span></a>
	 </li>
	<li class="dropdown">
		<a href="#" class="drop-downtoggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-th-large"></i>&nbsp;<span> Apps</span> <span class="caret"></span>
		</a>
		
	 
		
		
		<ul class="dropdown-menu" role="menu">
			
			@foreach( getApplications() as  $category=>$apps)
			
				<li class='menuItemHeader' style="padding-left: 5px;"> {{$category}} </li>

				@foreach($apps as $app)
					<li>
					@if(\Route::getRoutes()->hasNamedRoute($app['location']))
						<a href='{{route($app['location'])}}' style=";padding-left: 30px">
							<img src="{{asset($app['iconPath'])}}" height="14px" > &nbsp;
							<span> {{$app['name']}}</span> 
						</a>  
						
						@if(isset($subNavigation[$app['name']]))
						
						 
							@foreach($subNavigation[$app['name']] as $nav)
								<li class='menu-item treeview' style="list-style: none; padding-left: 40px"> 
									<a href="{!! $nav['link'] !!}" style="color:#666 " >
										<i class='fa fa-angle-right' style="width: 10px;margin-right: 0px; "></i>  {{$nav['title']}} 
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
			
				@if($loop->last)
				
				@else
					<li class='divider'>   </li>
				@endif
			@endforeach
			
			 
		</ul>
	</li>
</ul>

@else

@endif