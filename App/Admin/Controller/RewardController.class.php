<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
use Home\Controller\PublicController;
class RewardController extends AdminController {
	//空操作
	public function _initialize(){
		parent::_initialize();
	}
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	
	/**
	*推荐奖励 
	*/
	public function index(){
		//首先查看有多少币种
		$currency = M('Currency')->Field('currency_id,currency_name')->select();
		//查看现有奖金设置
		foreach($currency as $k => $v){
			$up_currency[$k] = $v;
			$down_currency[$k] = $v;
			$up_reward = M('Reward_conf')->where("`currency_id`={$v['currency_id']} AND `type`=1")->find();
			$down_reward = M('Reward_conf')->where("`currency_id`={$v['currency_id']} AND `type`=2")->find();
			
			$up_currency[$k]['money'] = ($up_reward['money']) ? $up_reward['money'] : 0;
			$up_currency[$k]['day'] = ($up_reward['day']) ? $up_reward['day'] : 0;
			$up_currency[$k]['sum'] = ($up_reward['sum']) ? $up_reward['sum'] : 0;
			
			$down_currency[$k]['money'] = ($down_reward['money']) ? $down_reward['money'] : 0;
			$down_currency[$k]['day'] = ($down_reward['day']) ? $down_reward['day'] : 0;
			$down_currency[$k]['sum'] = ($down_reward['sum']) ? $down_reward['sum'] : 0;
			
			unset($up_reward);
			unset($down_reward);
			
			
		}
		//dump($down_currency);die;
		//统计新人发放奖励的总数
		
		$list_r = M('Reward_reg')->where("down_id = 0")->select(); //新人被推荐奖励
		$reward_down = array();
		
		foreach($list_r as $k=>$v){
			if(array_key_exists($v['currency_id'],$reward_down)){
				$reward_down[$v['currency_id']]['ok'] += (1 - $v['surplus_day']/$v['sum_day'] ) * $v['money'];
				$reward_down[$v['currency_id']]['will'] += $v['money'];
			}else{
				$name = M('Currency')->Field('currency_name')->where("`currency_id`={$v['currency_id']}")->find();
				$reward_down[$v['currency_id']]['name'] = $name['currency_name'];
				$reward_down[$v['currency_id']]['ok'] = (1 - $v['surplus_day']/$v['sum_day'] ) * $v['money'];
				$reward_down[$v['currency_id']]['will'] = $v['money'];
			}
		}
		//dump($reward_down);die;
		//统计发放奖励的总数
		
		$list_d = M('Reward_reg')->where("down_id <> 0")->select(); //新人被推荐奖励
		$reward_up = array();
		
		foreach($list_d as $k=>$v){
			if(array_key_exists($v['currency_id'],$reward_up)){
				$reward_up[$v['currency_id']]['ok'] += (1 - $v['surplus_day']/$v['sum_day'] ) * $v['money'];
				$reward_up[$v['currency_id']]['will'] += $v['money'];
			}else{
				$name = M('Currency')->Field('currency_name')->where("`currency_id`={$v['currency_id']}")->find();
				$reward_up[$v['currency_id']]['name'] = $name['currency_name'];
				$reward_up[$v['currency_id']]['ok'] = (1 - $v['surplus_day']/$v['sum_day'] ) * $v['money'];
				$reward_up[$v['currency_id']]['will'] = $v['money'];
			}
		}
			
		$this->assign('reward_up',$reward_up);
		$this->assign('reward_down',$reward_down);
		$this->assign('currency',$down_currency);
		$this->assign('up_currency',$up_currency);
		$this->display();
	}
	
	/**
	*对推荐奖金进行设置
	*/
	public function save(){
		if(IS_AJAX){
			$money = I('post.money');
			$day = I('post.day');
			$currency_id = I('post.currency_id');
			$type = I('post.type');
			$sum = I('post.sum');
			
			if(empty($money) || empty($type)){
				$data['status'] = -1004;
				$data['info'] = '奖励金额要大于0';
			}
			if(empty($day)){
				$data['status'] = -1004;
				$data['info'] = '返奖天数要大于0';
			}
			if(empty($currency_id)){
				$data['status'] = -1004;
				$data['info'] = '参数丢失';
			}
			
			
			//判断yang_reward_conf里面是否有当前币种的设置
			$info = M('Reward_conf')->where("`currency_id`={$currency_id} AND `type`={$type}")->find();
			//dump($info);
			if(empty($info)){
				//没有此币种设置则进行添加操作
				$arr['currency_id'] = $currency_id;
				$arr['money'] = $money;
				$arr['day'] = (int)($day);
				$arr['type'] = $type;
				$arr['status'] = 0;
				$arr['sum'] = $sum;
				$res = M('Reward_conf')->add($arr);
				if($res){
					$data['status'] = 1;
					$data['info'] = '设置成功';
					$this->ajaxReturn($data);
				}else{
					$data['status'] = -10002;
					$data['info'] = '设置失败';
					$this->ajaxReturn($data);
				}
			}else{
				//若是有则进行修改
				$res = M('Reward_conf')->where("`id`={$info['id']}")->setField(array('money'=>$money,'day'=>$day,'sum'=>$sum));
				//dump($res);die;
				if($res){
					$data['status'] = 1;
					$data['info'] = '设置成功';
					$this->ajaxReturn($data);
				}else{
					$data['status'] = -10003;
					$data['info'] = '设置失败';
					$this->ajaxReturn($data);
				}
			}
			
		}else{
			$data['status'] = -10000;
			$data['info'] = '异常请求';
			$this->ajaxReturn($data);
		}
	}
	/**
	*设置推荐排行奖励活动时间
	*/
	public function set_time(){
		$start_time = strtotime($_POST['start']);
		$end_time = strtotime($_POST['end']);
		if(empty($start_time) || empty($end_time)){
			$data['status'] = -1;
			$data['info'] = '请输入正确日期';
			$this->ajaxReturn($data);
		}
		
		
		$re[] = M('Config')->where("yang_config.key = 'reward_start_time'")->setField('value',$start_time);
		$re[] = M('Config')->where("yang_config.key = 'reward_end_time'")->setField('value',$end_time);
		$re[] = M('Config')->where("yang_config.key = 'list_switch'")->setField('value',1);
		if(in_array(0,$re)){
			$data['status'] = 1;
			$data['info'] = '开启失败';
			$this->ajaxReturn($data);
		}else{
			$data['status'] = 2;
			$data['info'] = '开启成功';
			$this->ajaxReturn($data);
		}
	}
	/**
	*设置推荐排行奖励关闭
	*/
	public function close(){
		
		$re[] = M('Config')->where("yang_config.key = 'reward_start_time'")->setField('value',null);
		$re[] = M('Config')->where("yang_config.key = 'reward_end_time'")->setField('value',null);
		$re[] = M('Config')->where("yang_config.key = 'list_switch'")->setField('value',0);
		if(in_array(0,$re)){
			$data['status'] = 2;
			$data['info'] = '关闭失败,或已经处于关闭状态';
			$this->ajaxReturn($data);
		}else{
			$data['status'] = 1;
			$data['info'] = '关闭成功';
			$this->ajaxReturn($data);
		}
	}
}
