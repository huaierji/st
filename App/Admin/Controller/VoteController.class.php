<?php 
namespace Admin\Controller;
use Common\Controller\CommonController;
use Think\Controller;
use Think\Page;
use Think\Upload;

class VoteController extends AdminController {
	public function vote(){
		$info=D('Currency_new')->select();
        $this->assign('info',$info);
        $this->display();

	}

	public function addvote(){		
		/*$m = D('Currency_new');
		$this->assign('info',$info);*/
		$this->display();

	}

	public function add() {
		if(is_POST){
			$m = D('Currency_new');
			$data['vote_name'] = I('post.title'); 
			$data['vote_start_time'] = strtotime(I('post.start_time'));
			$data['vote_end_time'] = strtotime(I('post.end_time'));
			$data['buy_start_time'] = strtotime(I('post.buy_start_time'));
			$data['buy_end_time'] = strtotime(I('post.buy_end_time'));
			$data['total'] = I('post.total');
			$data['surplus'] = I('post.surplus');	
			$data['price'] = I('post.price');			
			//dump($data);die;	
			$upload = new \Think\Upload();// 实例化上传类
			$upload -> maxSize = 3145728;   //设置上传图片大小		
			$upload -> rootPath = './Uploads/';		//设置上传图片的根目录
			$upload -> savePath = '';					//设置上传图片的子目录
			$upload -> saveName = array('uniqid','');	
			$upload -> exts     = array('jpg', 'gif', 'png', 'jpeg');//设置上传类型
			$upload -> autoSub  = true;
			$upload -> subName  = array('date','Y-m-d');
			$arr = $upload ->upload();
				if(!$arr){
					$this->error($upload->getError());
				}
			//dump($arr);die;
			$data['logo'] = $arr['pic']['savename'];
			$data['logo'] = '/Uploads/'.$arr['pic']['savepath'].$arr['pic']['savename'];
			//dump($data);die;								
			try{	
				$result=$m->add($data);
			}catch (Exception $e){
				echo $e;
				die;
			}
			//dump($result);die;
			if($result){
				$this->success('新增成功',U('Vote'));
			}else{
				$this->error('新增失败,请稍后再试');
			}
    	}else{
    		$this->error($m->getError());
    	}
       
	}
	public function updatevote() {
		$m = D('Currency_new');
		$arr['id'] = I('get.id');
		$info=$m->where($arr)->find();
		$this->assign('info',$info);
		$this->display();		
	}
	
	public function update() {
		//dump($_POST);die;	
		$m = D('Currency_new');
		if(is_POST){
			$data['vote_id'] = I('post.vote_id');
			$data['vote_name'] = I('post.title'); 
			$data['vote_start_time'] = strtotime(I('post.start_time'));
			$data['vote_end_time'] = strtotime(I('post.end_time'));
			$data['buy_start_time'] = strtotime(I('post.buy_start_time'));
			$data['buy_end_time'] = strtotime(I('post.buy_end_time'));
			$data['total'] = I('post.total');
			$data['surplus'] = I('post.surplus');
			$data['price'] = I('post.price');		
			$data['support'] = I('post.support');
			$data['nonsupport'] = I('post.nonsupport');			
			//dump($data);die;
			$upload = new \Think\Upload();// 实例化上传类
			$upload->maxSize = 3145728;   //设置上传图片大小		
			$upload->rootPath = './Uploads/';		//设置上传图片的根目录
			$upload->savePath = '';					//设置上传图片的子目录
			$upload->saveName = array('uniqid','');	
			$upload->exts     = array('jpg', 'gif', 'png', 'jpeg');//设置上传类型
			$upload->autoSub  = true;
			$upload->subName  = array('date','Y-m-d');
			$info = $upload ->upload();
				if(!$info){
					$this->error($upload->getError());
				}
			//$data['logo'] = $info['pic']['savename'];
			$data['logo'] = '/Uploads/'.$info['pic']['savepath'].$info['pic']['savename'];
			$arr['id'] = $data['vote_id'];
			try{	
				$result = $m->where($arr)->save($data);
			}catch (Exception $e){
				echo $e;
				die;
			}						
			if($result){
				$this->success('修改成功',U('Vote'));
			}else{
				$this->error('修改失败,请稍后再试');
			}
    	}else{
    		$this->error($m->getError());
    		return;
    	}
       
	}
	public function deletevote() {
		$vote_id = I('get.id','','intval');
		//echo $vote_id;die;
        $m = M('Currency_new');
        $result = $m->delete($vote_id);
        if($result){
            $this->success('删除成功',U('Vote/vote'));
            return;
        }else{
            $this->error('删除失败');
            return;
        }
	}	

	public function buy(){
		$info = D('Currency_new')->select();
        $this->assign('info',$info);
        $this->display();
	}

	public function addcoin() {
		$this->display();
	}

}
?>