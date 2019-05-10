@extends('layouts.template')
@section('pageTitle', 'Categories')
@section('content')

<div class='row'>
	<div class='col-md-12'> 
		<div class='box box-solid'>
			<div class='box-header'>
				<h3 style='display:inline-block'>Manage Categories</h3>
				<a href='{{route('categories.addRecord')}}' class='btn btn-success' style='float:right;'><i class='fa fa-plus'></i> Add Category</a>
			</div>
			<div class='box-body'>
				<table class='table table-bordered table-hover responsive nowrap' width='100%' id='table' >
					<thead>
						<tr>
							<th> Name </th>
							<th> Description </th>
							<th> Action </th>
						</tr>
					</thead>
					<tbody>
						@foreach ($sysApplicationCategories as $row)
							<tr>
								<td> {{$row->name}} </td>
								<td> {{$row->description}} </td>
								<td> <div class='btn-group' style='min-width: 69px;'>
 										<button type='button' class='btn btn-default btn-xs'>Action</button>
										<button aria-expanded='false' type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' >
											 <span class='caret'></span>
											 <span class='sr-only'>Toggle Dropdown</span>
										</button>
										<ul class='dropdown-menu' role='menu'>
											  <li><a href='{{route('categories.editRecord',['id'=> $row->id])}}'><i class='fa fa-pencil'></i> Edit</a></li> 
											  <li><a href='{{route('categories.deleteRecord',['id'=> $row->id])}}'><i class='fa fa-trash'></i> Delete</a></li> 
										</ul>
									</div> </td>
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

<script type="text/javascript">
	$(document).ready(function(){
		$('#table').DataTable({ 
			'pageLength':50,
			'searching':false,
			'responsive':true,
			'lengthChange':false
		});
	});
</script>
@endsection
