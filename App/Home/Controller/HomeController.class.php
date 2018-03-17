<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class HomeController extends CommonController {
 	//protected $member;  //因为与Common中的member重复 所以注释掉了
 	//protected $trade;
 	protected $auth;
	protected $table_f;
	
	public function _initialize(){
 		parent::_initialize();
		//公告显示
		//$this->assign("gonggao","gonggao");
  		if(empty($_SESSION['USER_KEY_ID'])){
  			$this->redirect("Login/index");
  		}
  		 // 添加用户真实姓名等
  		//$this->auth = M('Member')->where('member_id ='.$_SESSION['USER_KEY_ID'])->find();  //此代码为下面代码原来代码  不知道做什么用 感觉可以替代
  		$this->auth = $this->member;
		
  		if (empty($this->auth)){
  		    $this->redirect("Login/index");
  		}
  		$this->assign('auth',$this->auth);
 		$this->table_f = $_SESSION['USER_KEY_ID']%10;
  		//$this->trade=M('Trade'); //也是不知道什么用处
 		//修正会员各个币种信息  currency_user
		//这里移到Login方法
 		
 		//$this->jiedong();
		
		
	}

	
	//获取会员有多少人工充值订单
	public function getPaycountByName($name){
		$list=M('Pay')->Field('pay_id')->where("member_name='".$name."'")->count();
		if($list){
			return $list;
		}else{
			return false;
		}
	}
	
	//获取个人账户指定币种金额
	public function getUserMoneyByCurrencyId($user,$currencyId){
	    return M('Currency_user')->field('num,forzen_num,chongzhi_url')->where("Member_id={$this->member['member_id']} and currency_id=$currencyId")->find();
	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	
	
	/**
	 * 解冻程序
	 * 现方法为根据众筹设置的解冻比例解冻
	 * Ps:注释掉部分为 默认配置项一个解冻比例
	 * @return boolean
	 */
	private function jiedong(){ //不知道实现什么  所以暂未改动
		
		$time=time();
		$bili = null;
		
		$list=M('Issue_log')->Field('iid,remove_forzen_bili,remove_forzen_cycle,id,add_time')->where("deal>0 and add_time<{$time}-60*60*24  and status=0")->select();
		if(!$list){
			return false;
		}
		foreach ($list as $k=>$v){
			$list=$this->getIssueRemoveForzenBiLiByIssueId($v['iid']);
			$v['remove_forzen_bili'] = $list['remove_forzen_bili']/100;
			$v['remove_forzen_cycle'] = $list['remove_forzen_cycle'];
			if($v['add_time']>$time-$v['remove_forzen_cycle']*60*60*24){
				continue;
			}
			M('Issue_log')->where("id={$v['id']}")->setDec('deal',$v['num']*$v['remove_forzen_bili']);
			M('Issue_log')->where("id={$v['id']}")->setField('add_time',time());
			M('Currency_user')->where("member_id={$v['uid']} and currency_id={$v['cid']}")->setInc('num',$v['num']*$v['remove_forzen_bili']);
			M('Currency_user')->where("member_id={$v['uid']} and currency_id={$v['cid']}")->setDec('forzen_num',$v['num']*$v['remove_forzen_bili']);
			if($v['deal']==0){
				M('Issue_log')->where("id={$v['id']}")->setField('status',1);
			}
// 			M('Issue_log')->where("id={$v['id']}")->setDec('deal',$v['num']*$bili);
// 			M('Issue_log')->where("id={$v['id']}")->setField('add_time',time());
// 			M('Currency_user')->where("member_id={$v['uid']} and currency_id={$v['cid']}")->setInc('num',$v['num']*$bili);
// 			M('Currency_user')->where("member_id={$v['uid']} and currency_id={$v['cid']}")->setDec('forzen_num',$v['num']*$bili);
// 			if($v['deal']==0){
// 				M('Issue_log')->where("id={$v['id']}")->setField('status',1);			
// 			}
		}
	}
	/**
	 * 根据认筹id查找解冻比例
	 * @param int $id Issue Id
	 * @return 解冻比例
	 */
	private function getIssueRemoveForzenBiLiByIssueId($id){
		$list =  M('Issue')->field('is_forzen,remove_forzen_bili')->where("id = $id")->find();
		if($list['is_forzen']==0){
			return $list;
		}else{
			return 0;
		}
	}
	
	
}