@if (Auth::check()) 
	<div class="navbar-custom-menu hidden-lg hidden-md hidden-sm">
          <ul class="nav navbar-nav">
			<!-- notifications -->
			@include('layouts.notifications')
			  
			  	<!-- User Account Menu -->
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ asset(Auth::user()->profilePicture)  }}" class="user-image  " alt="">
						 
					</a>
					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header" >
							<img src="{{ asset(Auth::user()->profilePicture)  }}" class="img-circle  " alt="User Image">
							<p>
								{{ Auth::user()->name}} {{ Auth::user()->lastname}}
								<small>Last Login: {{   date ("j-M-Y g:i a",strtotime(Auth::user()->lastLogin  )) }}</small>
							</p>
						</li>
						 
						<!-- Menu Footer-->
						<li class="user-footer">
							<div >
								
								<a href="{{route('logout')}}" class="btn btn-default btn-flat btn-block "   >Sign out</a>
							</div>
						</li>
					</ul>
				</li>
          </ul>
     </div>

@else
		<div class="navbar-custom-menu">
			  <ul class="nav navbar-nav">
			 
					<li class="dropdown user user-menu">
						<a href="/login" class=" " data-toggle=" " style="display:inline-block;">
							<span  ><i class="fa fa-sign-in"></i> </span>
						</a>
					</li>
			  </ul>
		</div>
@endif