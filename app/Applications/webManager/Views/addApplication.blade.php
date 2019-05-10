@extends('layouts.template')
@section('pageTitle', 'Add Application')
@section('content')
 
<form role='form' id ='form1' name='form1' method="post" action="{{route('saveApplication')}}">
<div class='row' >
	 <div class='col-md-6'>
	<!-- Horizontal Form -->
		<div class='box box-solid'>
			<div class='box-header with-border'>
				<h3 class='box-title'>General Information</h3>
			</div>
	
			
				<div class=' box-body'>
					<div class='form-group'>
						<label for='name' class=''>Application Name</label>
						<input type='text' class='form-control' name='name' placeholder='Application Name'>
					
					</div>
					
					<div class='form-group'>
						<label for='name' class=''>Description</label>
						<textarea class='form-control' name='description' rows='3' placeholder='Enter description...'></textarea>
					
					</div>
					
					<div class='form-group'>
						<label> Category   </label>
						<select class='form-control' name='categoryId'>
							@if(isset($categories))
								@foreach($categories as $cat)
									<option value='{{$cat->id}}'   >{{$cat->name}} </option>
								@endforeach
							@endif
						</select>
					</div>
								
				 
					<div class='form-group'>
					  <label for='author'>Author</label>
					  <input class='form-control' name='author' placeholder='Author' type='text'>
					</div>
					
					<div class='form-group'>
					  <label for='version'>Version</label>
					  <input class='form-control' name='version' placeholder='Version' type='text'>
					</div>
					
				
				
				 
				   
				</div>
				<!-- /.box-body -->
				
				
			
		</div>
	</div>
	
	<!-- -->
	
	 <div class='col-md-6'>
	<!-- Horizontal Form -->
		<div class='box box-solid'>
			<div class='box-header with-border'>
				<h3 class='box-title'> Icon and Security </h3>
			</div>
	
			
				<div class=' box-body'>
				 			 							
					<div class='form-group'>
					  <label for='icon'>Icon (click to change) </label>
					  <div align='center' onclick="showIcons();"> <img src='{{asset('images/icons/appIcon.svg')}}' title='Select Icon' id='iconImg' width='64' height='64'>  </div>
					  <div id='icons' style='display:none'>   
						<!-- available icons -->
							@if(isset($icons))
						  		@foreach($icons as $k=>$i)
						  			
						  			<img src='{!!asset($i)!!}' onClick='setIcon("{!!$i!!}")'   style='margin:10px;' width='64px' height='64px' > 
						  
						  		@endforeach
						  	@endif
						  
						<!-- end Available Icons-->  
					  </div>
					  
					  <input class='form-control' name='iconPath' id='iconPath'  type='hidden' value = 'images/icons/appIcon.svg' >
					</div>
					
					<div class='form-group'>
					  <label for='path'>Application Key</label>
					  <input class='form-control' name='appKey' placeholder='Application Key' type='text'>
					</div>
					
					<div class='form-group'>
					  <label for='path'>Application Defaut Route </label>
					  <input class='form-control' name='location' placeholder='Name of the application folder' type='text'>
					</div>
					
				 
								 
				   
				</div>
				 
				<div class='box-footer'>
					
				</div>		
			
		</div>
	</div>
	
</div>
<div class='row'> 
	<div class='col-md-12'>
		  {!! csrf_field() !!}
			<input type="submit" value="Add Application" class="btn btn-primary" >
	</div>

</div>

</form>
<br><br>
<script type="text/javascript">

	function setIcon(path){
		var fullUrl = "{{asset('/')}}";
		$("#iconPath").val(path);
		$("#iconImg").attr('src', fullUrl + path);
		$("#icons").slideUp();
		
	}
	
	function showIcons(){
		$("#icons").slideDown();
		return false;
	}
	
	
</script>
 
@endsection
