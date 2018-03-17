<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class HelpController extends CommonController {
	function _initialize(){
		parent::_initialize();
	}
	public function  _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	
	public function index(){

		//左侧标题，右侧文章
		//点击标题显示对应文章
    	$article = M('Article');
    	$art_cat = M('Article_category');
		//$id为header传来的文章id，
    	$id = I('get.id');
    	//$article_id为帮助中心内传来的文章id
    	$article_id = I('get.article_id');
    	if($id){
    	  $where['position_id'] = $id;
		}
		if($article_id){
			$where['article_id'] = $article_id;
		}
		//数据库是否有数，没有返回404
//     	$count = $article->where($where)->count();
// 		if($count==0){
// 			$this->display('Public:404');
// 			return;
// 		}
    	//查找到单一的文章
		
// 		if(S('artone_art'.$article_id)){
// 			$art_one = S('artone_art'.$article_id);
// 		}else{

			$art_one = $article->Field('article_id,position_id,title,content,add_time,status,type')->where($where)->find();
			$art_one['title']=html_entity_decode($art_one['title']);
			$art_one['content']=html_entity_decode($art_one['content']);
			S('artone_art'.$article_id,$art_one);
// 		}
	
		//将数据库中html标签字符串化，显示
			

		//查找6：帮助中心的title，遍历为左侧的显示title		
		
		if(S('art_list_art')){
			$art_list = S('art_list_art');
		}else{
			$art_list = $art_cat->Field('id,name,parent_id,keywords,sort,status')->where('parent_id = 6')->select();
			foreach($art_list as $k=>$v){
				$item= $article->Field('article_id,position_id,title,content,add_time,status,type,sign')->where('position_id = '.$v['id'])->select();
				$art_list[$k]['children']=$item;
			}
			S('art_list_art',$art_list);
		}
		
		
		
		$this->assign('art_one',$art_one);		
    	$this->assign('art_list',$art_list);
    	$this->display();	
    }

}