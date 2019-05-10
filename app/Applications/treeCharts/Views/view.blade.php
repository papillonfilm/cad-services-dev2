@extends('layouts.template')
@section('pageTitle', 'Charts')
@section('content')
 

	 
 
<div class='row' >
	 <div class='col-md-12'>
	<!-- Horizontal Form -->
		<div class='box box-solid '>
			<div class='box-header  with-border'>
				<h3 class='box-title'>  {{$chart->name}} </h3>
			</div>
			<div class="box-body">
			
				<div id="treeChart" class ='charts' style="width: 100%;height:800px;"></div>
					
			</div>
		 </div>
	</div>
 
</div>
 
 

	 
 
@endsection

@section('javascript')
 
<script src="{{asset('js/echarts.min.js')}}"></script>
<script type="text/javascript">
	
	var myChart = echarts.init(document.getElementById('treeChart'));
	
	myChart.showLoading();
	$.get('{{route('treeCharts.chartJson',$chart->id)}}', function (data) {
    	myChart.hideLoading();

		

    myChart.setOption(option = {
        tooltip: {
            trigger: 'item',
            triggerOn: 'mousemove'
        },
        series: [
            {
                type: 'tree',
                data: [data],
                top: '5%',
                left: '5%',
                bottom: '5%',
                right: '15%',

                symbolSize: 12,
				symbol:"emptyCircle",

                label: {
                    normal: {
                        position: 'left',
                        verticalAlign: 'middle',
                        align: 'right',
                        fontSize: 9
                    }
                },
				itemStyle:{
					 
					borderColor:'#78a9d3' 
				},

                leaves: {
                    label: {
                        normal: {
                            position: 'right',
                            verticalAlign: 'middle',
                            align: 'left'
                        }
                    }
                },

                expandAndCollapse: true,
                animationDuration: 550,
                animationDurationUpdate: 750
            }
        ]
    });
		
});
	
	// make ECharts Responsive.
	$(window).on('resize', function(){

		$(".charts").each(function(){
			var id = $(this).attr('_echarts_instance_');
			window.echarts.getInstanceById(id).resize();
		}); 

	});

</script>

@endsection
