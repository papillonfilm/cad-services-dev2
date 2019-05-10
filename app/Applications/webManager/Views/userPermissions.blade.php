@extends('layouts.template')
@section('pageTitle', 'Permissions')
@section('content')
 
	 
	<div class='box box-solid '>
		<div class='box-header with-border'>
			<h3 class='box-title'>  Permissions for {{$user->name or ''}} {{$user->initial or ''}} {{$user->lastname or ''}} </h3> 

		</div>
		<div class='box-body'>
		 
				<?php
					$appName = '';
				?>
				
				
				@foreach($accessRoutes as $route)
					 
					 
			
				
					@if($route['applicationName'] == $appName)
					
						@if($loop->last)
							<tr>
								<td align='center' >
									<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},1)" {!!$route['hasAccess']===1?"checked='checked'":''!!} value="1">
								</td>
								<td align='center' >
									<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},0)" {!!$route['hasAccess']===0?"checked='checked'":''!!} value="0">
								</td>
								<td align='center' >
									<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},2)" {!!$route['hasAccess']===''?"checked='checked'":''!!} value="">
								</td>
								<td> {!!$route['url']!!} </td>
								<td>{{$route['description'] or ''}}</td>
							</tr>
						</tbody>
					</table>
						@else
							<tr>
								<td align='center' >
									<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},1)" {!!$route['hasAccess']===1?"checked='checked'":''!!} value="1">
								</td>
								<td align='center' >
									<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},0)" {!!$route['hasAccess']===0?"checked='checked'":''!!} value="0">
								</td>
								<td align='center' >
									<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},2)" {!!$route['hasAccess']===''?"checked='checked'":''!!} value="">
								</td>
								<td> {!!$route['url']!!} </td>
								<td>{{$route['description'] or ''}}</td>
							</tr>
						@endif
				
					@else
						<?php
							$appName = $route['applicationName'];
						?>
								 			

						@if($loop->first)
							<h4>{{$appName}}</h4>
							<table class='table table-hover ' style="font-size: 12px;" width="100%">
								<thead>
									<tr>
										<th align='center' width="10%">Allow</th>
										<th align='center' width="10%">Deny</th>
										<th align='center' width="10%">Not Configured</th>
										<th>Url</th>
										<th>Description </th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td align='center' >
											<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},1)" {!!$route['hasAccess']===1?"checked='checked'":''!!} value="1">
										</td>
										<td align='center' >
											<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},0)" {!!$route['hasAccess']===0?"checked='checked'":''!!} value="0">
										</td>
										<td align='center' >
											<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},2)" {!!$route['hasAccess']===''?"checked='checked'":''!!} value="">
										</td>
										<td> {!!$route['url']!!} </td>
										<td>{{$route['description'] or ''}}</td>
									</tr>
						@else
								</tbody>
							</table>
							<h4>{{$appName}}</h4>
							<table class='table table-hover ' style="font-size: 12px;" width="100%">
								<thead>
									<tr>
										<th align='center' width="10%">Allow</th>
										<th align='center' width="10%">Deny</th>
										<th align='center' width="10%">Not Configured</th>
										<th>Url</th>
										<th>Description </th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td align='center' >
											<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},1)" {!!$route['hasAccess']===1?"checked='checked'":''!!} value="1">
										</td>
										<td align='center' >
											<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},0)" {!!$route['hasAccess']===0?"checked='checked'":''!!} value="0">
										</td>
										<td align='center' >
											<input type="radio" name="r{{$route['id']}}" onClick="updatePermission({{$route['id']}},2)" {!!$route['hasAccess']===''?"checked='checked'":''!!} value="">
										</td>
										<td> {!!$route['url']!!} </td>
										<td>{{$route['description'] or ''}}</td>
									</tr>	
						
						@endif
				
				
					@endif
				
				@endforeach
				
			 	</tbody>
			</table>

		</div>
	</div>
		
 
 
@endsection
@section('javascript')
<script type="text/javascript">

	
	function updatePermission(id,access){
		changeColor(id, access);
		var u = {{$user->id}};
		$.ajax({
			url:'{{route('user.permissions.update')}}',
			type:'POST',
			async: true,
			data: {"id":id, "access":access, "_token":'{{csrf_token()}}','u':u },
			dataType:'json',

			success: function(json) {
				//console.log(json);
			}
		
		});
	}


	function changeColor(target,id){

		if(id == 1){
			$("#u"+target).removeClass('red');
			$("#u"+target).addClass('green');
			$("#g"+target).hide();
		}else if(id == 0){
			$("#u"+target).removeClass('green');
			$("#u"+target).addClass('red');
			$("#g"+target).hide();
		}else{
			$("#u"+target).removeClass('green');
			$("#u"+target).removeClass('red');
			
			if($("#g"+target).hasClass('green')){
				$("#g"+target).addClass('green'); 
				$("#u"+target).removeClass('red');
				$("#u"+target).addClass('green');
				
			}else if ($("#g"+target).hasClass('red')){
				$("#g"+target).addClass('red'); 
				$("#u"+target).removeClass('green');
				$("#u"+target).addClass('red');
			}
			
			$("#g"+target).show();
		}
		
	}
	
	
</script>

@endsection
