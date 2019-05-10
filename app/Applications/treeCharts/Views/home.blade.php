@extends('layouts.template')
@section('pageTitle', 'Charts')
@section('content')
 

	 
 
<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'>Charts </h3>
				<span style='float:right'> <a href='{{route('treeCharts.addChart')}}' class='btn btn-success btn-sm'><i class='fa fa-plus'></i> Add Chart </a></span>
			</div>
			<div class="box-body">
			
			 
				<table class='table table-bordered table-hover responsive' width='100%' id='table'>
					<thead>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th width="70px">Action</th>
						</tr>
					</thead>
					<tbody>
					
						@foreach($charts as $chart)
						<tr>
							<td> <a href='{{route('treeCharts.view',['id'=>$chart->id])}}'>  {{$chart->name}} </a> </td>
							<td> {{$chart->description}} </td>
							<td> 
								<div class="btn-group" style="min-width: 69px;">
								<button type="button" class="btn btn-default btn-xs">Action</button>
								<button aria-expanded="false" type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{route('treeCharts.edit', $chart->id)}}"><i class="fa fa-pencil"></i> Edit</a></li>
									<li><a href="{{route('treeCharts.nodes', $chart->id)}}"><i class="fa fa-circle-o"></i> Manage Nodes</a></li>
									<li><a href="{{route('treeCharts.delete', $chart->id)}}"><i class="fa fa-trash"></i> Delete</a></li>


								</ul>
							</div>
							
							
							
							</td>

						</tr>
						
						@endforeach
						
					</tbody>
					
				</table>
					 
					
				 
					
							
				   
				 
				 
				
				
			
			</div>
		 </div>
	</div>
 
</div>
 
 

	 
 
@endsection

@section('javascript')
 <script type="text/javascript"  >
	 
	$(document).ready(function(){

		$('#table').DataTable({
			'pageLength':50,
			'responsive':true,
			'lengthChange': false,
			'searching':false
			
			 
		});
		
	});
	
</script>
@endsection
