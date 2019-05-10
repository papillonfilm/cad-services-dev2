<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>@yield('pageTitle') - {{ config('app.name') }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<!-- Bootstrap -->
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
	<!-- Theme style -->
	<link href="{{ asset('css/main.css') }}" rel="stylesheet">
	<!-- VidalFramework Main CSS -->
	<link href="{{ asset('css/vfThemeWhite.css') }}" rel="stylesheet">
	<link href="{{ asset('css/vidalFramework.css') }}" rel="stylesheet">
	<!-- Datatables -->
	<link href="{{ asset('css/dataTables.bootstrap.css') }}" rel="stylesheet">
	<link href="{{ asset('css/responsive.bootstrap.min.css') }}" rel="stylesheet">
	 
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	
	@yield('css')
	
</head>
<body class="hold-transition skin-blue-light fixed2 layout-top-nav2">
<div class="wrapper" style="border-bottom:1px #ccc solid;">

  <header class="main-header" style="text-align: center; min-width:330px;   ">
		 
	 		<span class=" hidden-sm hidden-md hidden-lg " style="width: 45px; display: inline-block;"   >
				 &nbsp;
			</span>
	  
			<a href="/dashboard/" class="logo logo-vf">
				<span class=" hidden-sm hidden-md hidden-lg "><img alt="Logo" src="{{ asset('images/logoPage.svg') }}"   style="width:33%; max-width: 180px"  ></span>
				<span class=" hidden-xs "><img alt="Logo" src="{{ asset('images/logoPage.svg') }}" width="180px"  ></span>
			</a>
		 

		<span>
			<a href="#" class="sidebar-toggle hidden-sm hidden-md hidden-lg hamburger-vf" data-toggle="offcanvas" role="button" id="toggle-sidebar" >
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			@include('layouts.mobileProfile')
		</span>
	  
    <nav class="navbar navbar-static-top hidden-xs ">
		
      <div class=" hidden-xs">
        <div class="navbar-header">
          <!--<a href="/dashboard/" class="navbar-brand hidden-xs"><img alt ="Logo" src="{{ asset('images/logoPage.svg') }}" width="180px" style="margin-top: -11px;"> </a>-->
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
			
		<a href="#" class="sidebar-toggle  hamburger-vf" data-toggle="offcanvas" role="button" id="toggle-sidebar" >
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		
		@include('layouts.navigation') 
			
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        @include('layouts.loginBox')
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
	
<!-- Sidebar for Small Navigations -->
	<aside class="main-sidebar " id="main-sidebar" style='background-color: #f1f1f1'>
	 
		<section class="sidebar">
		   
			<div class="user-panel">
				<!--
				<div class="pull-left image">
					<img src="" class="img-circle" alt="User Image">
				</div> -->
				<div class="pull-left info">
					<p>   </p>

				</div>
			</div>
			
			@include('layouts.navigationMobile') 
			 
			
		</section>
	 
	</aside>
 
	
	
  <!-- Full Width Column -->
  <div class="content-wrapper" style="border-left: none; ">
    <div >
      <!-- Content Header (Page header) -->
      <section class="content-header">
 
			<h1>
			  
				@yield('pageTitle')
			   
			</h1>
		  {!! breadcrumb() !!}
		  		
			 
		</section>

      <!-- Main content -->
      <section class="content">
		<!-- Flash Message -->  
		<div class="flash-message" id='flashMessage'>
			
			
		@foreach (['danger', 'warning', 'success', 'info', 'error', 'message'] as $msg)
			@if(Session::has('alert-' . $msg))
				<p class="alert alert-{{ $msg }}">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					{!! Session::get('alert-' . $msg) !!} 
					
				</p>
			@endif
		@endforeach
		</div> 
		<!-- End Flash Message --> 
		 
		 <!-- Errors -->
		  @if ($errors->any())
			<div class="" id='errorMessage'>
				 
				@foreach ($errors->all() as $error)
					<p class="  alert alert-danger "> {{$error}} 
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					</p>
				@endforeach
				 
			</div>
		@endif
		 <!-- End Errors -->
     
		 
      
		@yield('content')
		 
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
	 
  </div>
	
  <!-- /.content-wrapper -->
  
	 
</div>
<footer class="main-footer no-print" style="border-left: none; border-top:none; margin-left:0px !important; ">
		<!-- Copyright Info --> &copy; {{ date("Y") }} 
</footer>	
	
<!-- ./wrapper -->


	<!-- jQuery  -->
	<script src="{{ asset('js/jquery-2.2.4.min.js') }}" ></script>
	<!-- Bootstrap -->
	<script src="{{ asset('js/bootstrap.min.js') }}" ></script>
	<!-- SlimScroll -->
	<script src="{{ asset('js/slimScroll/jquery.slimscroll.min.js') }}" ></script>
	<!-- FastClick -->
	<script src="{{ asset('js/fastclick/fastclick.js') }}" ></script> 
	<!-- Main App -->
	<script src="{{ asset('js/app.js') }}" ></script>
	<!-- VidalFramework -->
	<script src="{{ asset('js/vidalFramework.js') }}" ></script>
	<!-- Data Tables -->
	<script src="{{ asset('js/jquery.dataTables.min.js') }}" ></script>
	<script src="{{ asset('js/dataTables.bootstrap.min.js') }}" ></script>
	<script src="{{ asset('js/dataTables.responsive.js') }}" ></script>
	<script src="{{ asset('js/responsive.bootstrap.min.js') }}" ></script>
	
	
	@foreach (['danger','warning','success','info', 'error'] as $msg)
		@if (Session::has('alert-' . $msg))
		<script type="text/javascript">
			$(document).ready(function($){
				 $("#flashMessage").delay(5000).slideUp(500);
			});

		</script>

		@break
		@endif
	@endforeach
	@if ($errors->any())
		<script type="text/javascript">
			$(document).ready(function($){
				 $("#errorMessage").delay(5000).slideUp(500);
			});

		</script>
	
	@endif

	@if (Auth::check() and session('rememberMe') !=1) 
	<script>
		var timeout = ({{config('session.lifetime')}} * 60000) +3 ;
		setTimeout(function(){
			window.location.reload(1);
		},  timeout);

	</script>
 	@endif
	
	@yield('javascript')
	
	<script type="text/javascript">

		function readNotification(id, object){
			var total ; 
			
			$(object).parent().fadeOut();
			total = parseInt($("#notificationTotal").html());
			 
			total --;
			if(total == 0){
				$(".notificationTotal").fadeOut();
			} else{
				$(".notificationTotal").html(total);
			}
			
			@if(Route::getRoutes()->hasNamedRoute('updateNotification'))
				$.ajax({
				  method: "GET",
				  url: "{{ route('updateNotification') }}/"+id+"/",
				  cache: false
				});
									
			 
			@endif
			
			 
			
		}
		
	</script>
	
</body>
</html>