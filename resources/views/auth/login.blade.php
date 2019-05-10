@extends('layouts.templateWithoutNavigation')
@section('pageTitle', 'Login')
@section('content')

<div class="col-md-4 col-sm-6 col-xs-10 col-md-offset-4 col-sm-offset-3 col-xs-offset-1"> 
 
		<div class="row justify-content-center">
			<div class=" ">
				<div class="card">
					<div class="card-header">{{ __('Login') }}</div>

					<div class="card-body">
						<form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
							@csrf

							<div class="form-group ">
								<label for="email" class=" ">{{ __('Username') }}</label>

								<div class=" ">
									<input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="username" value="{{ old('email') }}" required autofocus>
 
								</div>
							</div>

							<div class="form-group ">
								<label for="password" class=" ">{{ __('Password') }}</label>

								<div class=" ">
									<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

								</div>
							</div>
							
							<div class="form-group ">
							 

								<div class=" ">
									<input id="remember" type="checkbox"   value = '1' name="remember"  > &nbsp; Remember me.

								</div>
							</div>



							<div class="form-group  ">
								<div class=" ">
									<button type="submit" class="btn btn-primary">
										{{ __('Login') }}
									</button>


								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	 
</div>
@endsection
