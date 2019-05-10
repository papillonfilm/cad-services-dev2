@extends('layouts.template')
@section('pageTitle', 'Applications')
@section('content')
 
	 
	<div class='box box-solid '>
        <div class='box-header with-border'>
        	<h3 class='box-title'>  Applications </h3> 
			<a href='{{route('addApplication')}}' class='btn btn-sm btn-success '  style='float:right'>Add Application</a>
        </div>
        <div class='box-body'>
			<table id="tableApps" class="table table-bordered table-hover responsive nowrap" width="100%"> 
				<thead>
					<tr>
						<th width='20%' data-priority="1"> Name </th>
						<th> Description </th>
						<th   data-priority="2"> Action </th></tr>
				</thead>
				<tbody>
					@foreach ($applications as $app)
					<tr>
						<td> 
							<a href="{{route('viewApplication',['id'=>$app->id])}}" >
								<img src="{{asset($app->iconPath)}}"  alt="Icon" width="25px"> &nbsp; {{$app->name}} 
							</a> 
						</td>
						<td>{{$app->description}}</td>
						<td align="center">
							<div class="btn-group" style="min-width: 69px;">
								<button type="button" class="btn btn-default btn-xs">Action</button>
								<button aria-expanded="false" type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{route('editApplication', $app->id)}}"><i class="fa fa-pencil"></i> Edit</a></li>
									<li><a href="{{route('deleteApplication', $app->id)}}"><i class="fa fa-trash"></i> Delete</a></li>


								</ul>
							</div>
						
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			
		</div>
	</div>
	
 
 
 
@endsection
@section('javascript')

<script type="text/javascript"  >
	 
	$(document).ready(function(){

		$('#tableApps').DataTable({
			'pageLength':50,
			'responsive':true,
			 
		});
		
	});
	
</script>
@endsection