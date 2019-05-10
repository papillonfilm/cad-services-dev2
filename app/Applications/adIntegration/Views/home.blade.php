@extends('layouts.template')
@section('pageTitle', 'AD Integration')
@section('content')

<div class='box box-solid'>
		<div class='box-header with-border'>
			<h3 class='box-title'> Local Database </h3>
		</div>
		<div class='box-body'  >
			<!-- Total Users -->
			<a href='{{route('localUsers')}}' title='Click for details' >
				<div class='col-md-4 col-sm-6 col-xs-12'>
					<div class='info-box bg-blue'>
						<span class='info-box-icon'><i class='fa fa-user'></i></span>
					<div class='info-box-content'>
						<span class='info-box-text'>Local Users</span>
						<span class='info-box-number'> {{$totalLocalUsers}} </span>
					<span class='progress-description'>

					</span>
					</div>

					</div>
				</div>
			</a>
			<!-- Total Accounts Enable -->
			<a href='{{route('enableAccounts')}}' title='Click for details' >
				<div class='col-md-4 col-sm-6 col-xs-12'>
					<div class='info-box bg-green'>
						<span class='info-box-icon'><i class='fa fa-user'></i></span>
					<div class='info-box-content'>
						<span class='info-box-text'>Enabled Accounts</span>
						<span class='info-box-number'> {{$totalActiveAccounts}} </span>
					<span class='progress-description'>

					</span>
					</div>

					</div>
				</div>
			</a>
			<!-- Total Accounts Disable -->
			<a href='{{route('disableAccounts')}}' title='Click for details' >
				<div class='col-md-4 col-sm-6 col-xs-12'>
					<div class='info-box bg-red'>
						<span class='info-box-icon'><i class='fa fa-user'></i></span>
					<div class='info-box-content'>
						<span class='info-box-text'>Disabled Accounts</span>
						<span class='info-box-number'> {{$totalDisableAccounts}} </span>
					<span class='progress-description'>

					</span>
					</div>

					</div>
				</div>
			</a>

		</div>

	</div>
	
	<!-- ldap data -->
	<div class='box box-solid'>
		<div class='box-header with-border'>
			<h3 class='box-title'> LDAP (Active Directory)</h3>
		</div>
		<div class='box-body'  >
			<!-- Total Users -->
			<a href='{{route('adUsers')}}' title='Click for details' >
				<div class='col-md-3 col-sm-6 col-xs-12'>
					<div class='info-box bg-blue'>
						<span class='info-box-icon'><i class='fa fa-address-book'></i></span>
					<div class='info-box-content'>
						<span class='info-box-text'>AD Users</span>
						<span class='info-box-number'> {{$totalAdUsers}} </span>
					<span class='progress-description'>

					</span>
					</div>

					</div>
				</div>
			</a>

			<!-- Total Accounts IMPORTED -->
			<a href='{{route('importedAdUsers')}}' title='Click for details' >
				<div class='col-md-3 col-sm-6 col-xs-12'>
					<div class='info-box bg-green'>
						<span class='info-box-icon'><i class='fa fa-address-book'></i></span>
					<div class='info-box-content'>
						<span class='info-box-text'>Imported</span>
						<span class='info-box-number'> {{$totalAdUsersInSystem}}</span>
					<span class='progress-description'>

					</span>
					</div>

					</div>
				</div>
			</a>
			
			<!-- Total Accounts Disable -->
			<a href='{{route('adNotImported')}}' title='Click for details' >
				<div class='col-md-3 col-sm-6 col-xs-12'>
					<div class='info-box bg-yellow'>
						<span class='info-box-icon'><i class='fa fa-address-book'></i></span>
					<div class='info-box-content'>
						<span class='info-box-text'>Not Imported</span>
						<span class='info-box-number'> {{$totalAdUsersNotInSystem}} </span>
					<span class='progress-description'>

					</span>
					</div>

					</div>
				</div>
			</a>
			
			<!-- accounts disable in ad that need to disable local -->
			<a href='{{route('usersNotInAd')}}' title='Click for details'>
				<div class='col-md-3 col-sm-6 col-xs-12'>
					<div class='info-box bg-red'>
						<span class='info-box-icon'><i class='fa fa-address-book'></i></span>
					<div class='info-box-content'>
						<span class='info-box-text'>Not in AD<br>Only Local System</span>
						<span class='info-box-number'> {{$totalInSystemButNotAd}} </span>
					<span class='progress-description'>

					</span>
					</div>

					</div>
				</div>
			</a>
			

		</div>

	</div>

@endsection