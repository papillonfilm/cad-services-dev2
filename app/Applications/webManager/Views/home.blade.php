@extends('layouts.template')
@section('pageTitle', 'Web Manager')
@section('content')
 
	<div class='row'> 
		<div class='col-md-4 col-sm-6 col-xs-12'> 
			<div class='info-box '>
				<a href='webManager/users/' ><span class='info-box-icon bg-green'><i class='fa fa-user'></i></span></a>
				<div class='info-box-content'>
					<span class='info-box-text'>Users </span>
					<span class='info-box-number'> {{$totalUsers}}  </span>

					<span class='progress-description smallFont'>
						<span class='smallFont'> </span>
					</span>
				</div>
			</div>
		</div>

		<div class='col-md-4 col-sm-6 col-xs-12'> 
			<div class='info-box '>
				<a href='webManager/applications/' ><span class='info-box-icon bg-blue'><i class='fa fa-th-large'></i></span> </a>
				<div class='info-box-content'>
					<span class='info-box-text'>Applications  </span>
					<span class='info-box-number'> {{$totalApps}}  </span>

					<span class='progress-description'>

					</span>
				</div>
			</div>
		</div>

		<div class='col-md-4 col-sm-6 col-xs-12'> 
			<div class='info-box '>
				<a href='webManager/groups/' ><span class='info-box-icon bg-yellow'><i class='fa fa-users'></i></span></a>
				<div class='info-box-content'>
					<span class='info-box-text'>Groups </span>
					<span class='info-box-number'> {{$totalGroups}}  </span>

					<span class='progress-description'>

					</span>
				</div>
			</div>
		</div>
		
		<div class='col-md-4 col-sm-6 col-xs-12'> 
			<div class='info-box '>
				<a href='{{route('permissions')}}' ><span class='info-box-icon bg-yellow'><i class='fa fa-key fa-rotate-90'></i></span></a>
				<div class='info-box-content'>
					<span class='info-box-text'>Permissions </span>
					<span class='info-box-number'>    </span>

					<span class='progress-description'>

					</span>
				</div>
			</div>
		</div>
		
		
		
	</div>
 
@endsection
