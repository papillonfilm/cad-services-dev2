@extends('layouts.template')

@section('pageTitle', 'Dashboard')
@section('content')
 
<div>
 
<!-- Container -->	
	@if(isset($categories))
		@foreach($categories as $category)
			<div class='box box-solid'>
				<div class='box-header with-border'>
					<h3 class='box-title'>  {{$category->name}} </h3>
				</div>
				<div class='box-body'  >

				@if(isset($applications))
					@foreach($applications as $apps)
						 @if($category->id == $apps->categoryId)
					
							@if(Route::getRoutes()->hasNamedRoute($apps->location))
								<a href='{{route($apps->location)}}' > 
							@else
								<a href='#noRoute' > 
							@endif
								<div class='appBox' >  
									<img src='{{asset($apps->iconPath)}}'  width='64px' height='64px'><br>{{$apps->name}}
								</div> 
							</a>
						@endif
					@endforeach
				@endif

				</div>
			</div>
		@endforeach
	 @endif
<!-- end Container -->
 
</div>
  
@endsection
