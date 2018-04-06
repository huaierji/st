<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
use Think\Controller;
use Think\Page;

class EmptionController extends AdminController {
	public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }

    public function emption(){
    	/*$amount = M('member');
    	$arr['status'] = 5;
    	//$Model -> query("select pid,count(pid) from yang_member where status=5 group by pid");
    	$total = $amount->field(count('pid'))->group('pid')->where($arr)->count();
    	//dump($total);die;*/
    	$this->assign('total',$total);
		$m = M('Buy_rules');
		$info = $m ->select();
		//dump($info);die;
		$this->assign('info',$info);
		$this->display();
	}
	public function addEmption(){
		$this->display();
	}
	public function emptionAdd(){
		$m = M('Buy_rules');
		if(is_POST){
			$data['currency_name'] = I('post.title');
			$data['currency_id'] = I('post.nu');
			$data['putong'] = I('post.putong');
			$data['tongpai'] = I('post.tongpai');
			$data['yinpai'] = I('post.yinpai');
			$data['jinpai'] = I('post.jinpai');
			$data['zuanshi'] = I('post.zuanshi');
			$data['status'] = I('post.activity');
			//$arr['currency_id'] = $data['currency_id'];
			//dump($data);die;
			try{
				$result = $m -> add($data);
			}catch (Exception $e){
				echo $e;
				die;
			}
			if($result){
				$this->success('新增成功',U('Emption/emption'));
			}else{
				$this->error('新增失败,请稍后再试');
			}
    	}else{
    		$this->error($m->getError());
    		return;
    	}
	}
	public function deleteEmption() {
		$emption_id = I('get.id','','intval');
        $m = M('Buy_rules');
        $result = $m->delete($emption_id);
        //dump($result);die;
        if($result){
            $this->success('删除成功',U('Emption/emption'));
            return;
        }else{
            $this->error('删除失败');
            return;
        }
	}
	public function updateEmption(){
		$m = M('Buy_rules');
		$arr['id'] = I('get.id');
		$info=$m->where($arr)->find();
		//dump($info);die;
		$this->assign('info',$info);
		$this->display();
	}

	public function update(){
		$m = M('Buy_rules');
		if(is_POST){
			$data['emption_id'] = I('post.emption_id');
			$data['currency_id'] = I('post.nu');
			$data['currency_name'] = I('post.title');
			$data['putong'] = I('post.putong');
			$data['tongpai'] = I('post.tongpai');
			$data['yinpai'] = I('post.yinpai');
			$data['jinpai'] = I('post.jinpai');
			$data['zuanshi'] = I('post.zuanshi');
			$data['status'] = I('post.activity');
			//dump($data);die;
			$arr['id'] = $data['emption_id'];
			try{
				$result = $m -> where($arr) -> save($data);
			}catch (Exception $e){
				echo $e;
				die;
			}
			if($result){
				$this->success('修改成功',U('Emption/emption'));
			}else{
				$this->error('修改失败,请稍后再试');
			}
    	}else{
    		$this->error($m->getError());
    		return;
    	}
	}
}
