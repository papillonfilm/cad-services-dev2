<?php

namespace App\Applications\treeCharts\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Applications\treeCharts\Models\treeCharts;
use App\Applications\treeCharts\Models\treeChartElements;
use Auth;
use Validator;

class treeChartsController extends Controller{ 

	var $familyTree = array();
	var $tempArray = array();
	var $total = 0;
	
	public function __construct(){

	}

	
	public function index(){
		
		$userId = Auth::getUser()->id;
		
		$charts= treeCharts::where('userId','=',$userId)->orderBy('name')->get();
		
		
		return view('treeCharts::home',['charts'=>$charts]);
		
	}
	
	public function view($id){
		
		if(!is_numeric($id)){
			return back()->with('alert-error','Invalid Chart');
		}
		
		$userId = Auth::getUser()->id;
		
		$chart= treeCharts::where('userId','=',$userId)->
			where('id','=',$id)->
			orderBy('name')->first();
		
		
		return view('treeCharts::view',['chart'=>$chart]);
		
	}
	
	public function addChart(){
		
		return view("treeCharts::addChart");
		
	}
	
	public function edit($id){
		
		if(!is_numeric($id)){
			
			return back()->with('alert-error','Invalid Chart');
		}
		
		$userId = Auth::getUser()->id;
		
		
		$charts= treeCharts::where('id','=',$id)->
			where('userId','=',$userId)->
			orderBy('name')->
			first();
		
		return view('treeCharts::edit',['chart'=>$charts]);
		
		
	}
	
	public function save(Request $post){
		
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string|max:100', 
			'description'=>'string'
			 
		] )->validate();
		
		$userId = Auth::getUser()->id;
		$chart = new treeCharts;
		
		$chart->name = $post['name'];
		$chart->description = $post['description'];
		$chart->userId = $userId;
		$chart->save();
		
		
		return redirect()->route('treeCharts')->with('alert-success','Chart sucessfully created');
		
		
		
	}
	
	public function update(Request $post){
		
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string|max:100', 
			'description'=>'string',
			'id'=>'required|numeric'
		] )->validate();
		
		$userId = Auth::getUser()->id;
		$chart = treeCharts::where('id','=',$post['id'])->
			where('userId','=',$userId)->
			first();
		
		$chart->name = $post['name'];
		$chart->description = $post['description'];
		$chart->save();
		
		
		return redirect()->route('treeCharts')->with('alert-success','Chart sucessfully updated');
		
		
		
	}
	
	
	public function delete($id){
		
		if(!is_numeric($id)){
			return back()->with('alert-error','Invalid Chart');
		}
		
		$userId = Auth::getUser()->id;
		
		$chart = treeCharts::where('id','=',$id)->
			where('userId','=',$userId)->
			first();
		
		if($chart){
			
			$elements = treeChartElements::where('userId','=',$userId)->
				where('chartId','=',$chart->id)->delete();
			
			$chart->delete();
			
	 		return redirect()->route('treeCharts')->with('alert-success','Chart sucessfully deleted');
			
		}
		
		
		return redirect()->route('treeCharts')->with('alert-error','No record found to Delete');
		
		
	}
	
	
	public function chartJson($id){
		
		if(!is_numeric($id)){
			return back()->with('alert-error','Invalid Chart');
		}
		
		$tree = $this->makeTree($id);
		
		 
		if(isset($tree[0])){
			return $tree[0]; 
		}
			
		 
		return ''; 
	}
	
	
	public function viewChartNodes($id){
		
		if(!is_numeric($id)){
			return redirect()->back()->with('alert-error','Invalid Chart Node');
		}
		
		$userId = Auth::getUser()->id;
		
		
		$chart = treeCharts::where('id','=',$id)->
			where('userId','=',$userId)->
			first();
		
		 
		
		// Create tree for Select
		$tree = $this->makeDeserializeTree($id);
	
		return view('treeCharts::nodes',['nodes'=>$tree, 'chart'=>$chart]);
	}
	
	public function saveNode(Request $post){
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string|max:100', 
			'id'=>'required|numeric',
			'parent'=>'required|numeric',
			'color'=>'required|max:7'
		] )->validate();
		
		$userId = Auth::getUser()->id;
		
		$node = new treeChartElements;
		$node->chartId = $post['id'];
		$node->parentId = $post['parent'];
		$node->name = $post['name'];
		$node->color = $post['color'];
		$node->userId = $userId;
		$node->save();
		
		return redirect()->route('treeCharts.nodes',['id'=>$post['id']])->with('alert-success','Node Successfully added');
		
	}
	
	public function updateNode(Request $post){
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string|max:100', 
			'id'=>'required|numeric',
			'node'=>'required|numeric',
			'color'=>'required|max:7',
			'parent'=>'required|numeric'
		] )->validate();
		
		$userId = Auth::getUser()->id;
		$nodeId = $post['node']; 
		
		$node = treeChartElements::where('userId','=',$userId)->
			where('id','=',$nodeId)->
			where('chartId','=',$post['id'])->
			first();
		
		$node->chartId = $post['id'];
		$node->parentId = $post['parent'];
		$node->name = $post['name'];
		$node->userId = $userId;
		$node->color = $post['color'];
		$node->save();
		
		return redirect()->route('treeCharts.nodes',['id'=>$post['id']])->with('alert-success','Node Successfully updated');
	}
	
	public function updateColor(Request $post){
		
		$validator = Validator::make(
			$post->All()
		,[
			'color' => 'required|max:7', 
			'node'=>'required|numeric'
		] )->validate();
		
		$userId = Auth::getUser()->id;
		$nodeId = $post['node'];
		$chartId = $post['id'];
		
		$element = treeChartElements::where('id','=',$nodeId)->
			where('userId','=',$userId)->first();
		$element->color = $post['color'];
		$element->save();
		
		return redirect()->route('treeCharts.nodes',['id'=>$chartId])->with('alert-success','Node Color sucessfully updated');
		
		
	}
	
	public function editNode(Request $post){
		
		$validator = Validator::make(
			$post->All()
		,[ 
			'node'=>'required|numeric'
		] )->validate();
		
		$userId = Auth::getUser()->id;
		
		$node = treeChartElements::where('id','=',$post['node'])->
			where('userId','=',$userId)->first();
		
		$tree = $this->makeDeserializeTree($node->chartId);
		
		return view('treeCharts::editNode',['node'=>$node,'tree'=>$tree]);
		
		
	}
	
	public function deleteNode(Request $post){
		$validator = Validator::make(
			$post->All()
		,[ 
			'node'=>'required|numeric',
			'id'=>'required|numeric'
		] )->validate();
		
		$userId = Auth::getUser()->id;
		
		$node = treeChartElements::where('id','=',$post['node'])->
			where('userId','=',$userId)->delete();
		
		$childs = treeChartElements::where('userId','=',$userId)->
			where('parentId','=',$post['node'])->delete();
		
		return redirect()->route('treeCharts.nodes',['id'=>$post['id']])->with('alert-success','Node sucessfully removed.');
		
		
	}
	
	public function makeTree($chartId ){
		
		$userId = Auth::getUser()->id;
		// We have to get all the nodes and childs to create the json for the chart.
		$children = array();
		$elements = treeChartElements::where('chartId','=',$chartId)->
			where('userId','=',$userId)->
			orderBy('parentId' )->
			get()->toArray(); 

		$treeArray = array();
		foreach($elements as $key=>$element){
			$treeArray[$element['id']] = array(
				'parent'=>$element['parentId'], 
				'name'=>$element['name'] ,
				'color'=>$element['color']) ;
		}
		
		$this->tempArray = $treeArray;
		
		$returnArray = array();
		// look for the parent node		
		foreach($this->tempArray as $key=>$value){ 
			if($value['parent'] == 0){
				$children = $this->getChildren($value['parent']);
			}
		}
		
		return $children ;
	}
	
	
	public function getChildren($parent){
		$children = array();
 		foreach($this->tempArray as $key=>$value){
			
			if($value['parent'] == $parent){
				
				if($value['color'] == ''){
					$color = '#3c8dbc' ;
				}else{
					$color = $value['color'];
				}
				
				$name = $value['name'];
				$itemStyle= array(
					'color'=>$color,
					 
					'borderColor'=>$color,
					'borderWidth'=>2.5,
				);
				
				 
				
				$lineStyle = array(
					'color'=>'#b9c9d7' 
				);
			 
				unset($this->tempArray[$key]);
				$grandChildrens = $this->getChildren( $key ); 
				
				if($grandChildrens){
					$children[] = array('name'=>$name,'itemStyle'=>$itemStyle,'lineStyle'=>$lineStyle,'children'=>$grandChildrens);
				}else{
					$children[] = array('name'=>$name,'itemStyle'=>$itemStyle,'lineStyle'=>$lineStyle   );
				}

				
			}
			
		}
		
 		return $children ;
	}
	
	
	public function makeDeserializeTree( $chartId ){
		
		$userId = Auth::getUser()->id;
		// We have to get all the nodes and childs to create the json for the chart.
		$children = array();
		$elements = treeChartElements::where('chartId','=',$chartId)->
			where('userId','=',$userId)->
			orderBy('parentId' )->
			get()->toArray(); 

		$treeArray = array();
		foreach($elements as $key=>$element){
			$treeArray[$element['id']] = array('parent'=>$element['parentId'], 'name'=>$element['name'] ) ;
		}
		
		$this->tempArray = $treeArray;
		
		
		$returnArray = array();
		// look for the parent node		
		foreach($this->tempArray as $key=>$value){ 
			 
				//print "get String for ". $value['parent'] . "\n";
				$children[$key] = $this->getUnserializeChild( $key );
				//print_r($children);
			 
		}
		
		 
		return $children ;
		
	}
 
	
	public function getUnserializeChild( $childKey  ){
		
		
		
		$children ='';
	 
 		foreach($this->tempArray as $key=>$value){
			
		 
			if( $key == $childKey ){
				
			 
				$name = $value['name'];
				
				$papa = $this->getUnserializeChild( $value['parent'] ); 
				 
				 
				if($papa){
					$children .= "$papa > ".$name   ;
				 
				}else{
					$children .= $name;
					 
				}

				
			}
			
		}
		
		 
		
 		return $children ;
		
		
		
		
	}
	
}
