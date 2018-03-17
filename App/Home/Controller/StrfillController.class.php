<?php
/**
 * Created by PhpStorm.
 * User: "GS"
 * Date: 17-03-07
 * Time:
 */

namespace Home\Controller;
use Think\Controller;


class StrfillController extends Controller{
   
	public function strtofill(){
		$config = $this->getConfig();
		//file_put_contents('./123.txt',$config);
		$content = $_POST['message'];
		$from = $_POST['from'];
		$secret = $_POST['secret'];
		
		//die;
		if($from == $config['STR_PHONE1']){
			$preg_match = $config['Preg_match1'];
			$type = 1;
		}else{
			$preg_match = $config['Preg_match2'];
			$type = 2;
		}
		foreach($_POST as $k=>$v){
			$ss .= $k . ' = ' .$v;
		}
		file_put_contents('./duanxincanshu.txt',$ss);
		$sms_w['add_time'] = $_POST['sent_timestamp'];
		$sms_w['from'] = $from;
		$sms_w['type'] = $type == 1 ? '支付宝' : '银行卡';
		$sms_w['content'] = $content;
		$rerr = M('Sms')->where($sms_w)->find();
		if($rerr){
			$contentsss = date('Y-m-d H:i') . '收到重复短信|';
			file_put_contents('./smslog.txt',$contentsss,FILE_APPEND);
			return false;
		}
		
		if($from != $config['STR_PHONE1'] && $from != $config['STR_PHONE2']){
			
			$data['content'] = $content;
			$data['from'] = $from;
			$data['type'] = $type == 1 ? '支付宝' : '银行卡';
			$data['add_time'] = $_POST['sent_timestamp'];
			$data['status'] = '失败 来源号码不明';
			
			M('Sms')->add($data);
			return;
		}
		if($secret != $config['STR_secret']){
			$data['content'] = $content;
			$data['from'] = $from;
			$data['type'] = $type == 1 ? '支付宝' : '银行卡';
			$data['add_time'] = $_POST['sent_timestamp'];
			$data['status'] = '失败 来源密钥不正确';
			
			M('Sms')->add($data);
			return;
		}
		
		//'/账户(.*?)存入￥(.*?)元，(.*?)。(.*?)支付宝转账。【(.*?)】/'   1
		//您账户(.*?)转入人民币(.*?)，(.*?)，付方(.*?)。(.*?)。    2
		
		$re = preg_match($preg_match , $content, $matchs);
		if($type == 2){
			file_put_contents('./testsms.txt',$matchs[4].'|||||'.$matchs[2]);
			
		}
		
		
		if($re){
			$where['member_name'] = $matchs[4];
			$where['money'] = $matchs[2];
			$where['type'] = $type;
			$where['status'] = 0;
			file_put_contents('./test.txt',$where);
			
			$list = M('Pay')->where($where)->order('add_time desc')->select();
			//file_put_contents('./teaast.txt',$list);
			if(count($list) > 0){
				//匹配成功进行状态修改
				M('Pay')->where("pay_id = {$list[0]['pay_id']}")->setField('status',1);
				M('Member')->where("member_id={$list[0]['member_id']}")->setInc('rmb',$list[0]['money']);
				
				$data['content'] = $content;
				$data['from'] = $from;
				$data['type'] = $type == 1 ? '支付宝' : '银行卡';
				$data['add_time'] = $_POST['sent_timestamp'];
				if(count($list) > 1){
					foreach($list as $k => $v){
						if($k != 0){
							M('Pay')->where("pay_id = {$v['pay_id']}")->setField('status',-1);
						}
					}
					$data['status'] = count($list).'条订单，成功一条';
				}else{
					
					$data['status'] = '成功';
				}
				
				M('Sms')->add($data);
				
				return;
				
			}else{
				
				$data['content'] = $content;
				$data['from'] = $from;
				$data['type'] = $type == 1 ? '支付宝' : '银行卡';
				$data['status'] = '失败 没有此订单';
				$data['add_time'] = $_POST['sent_timestamp'];
				M('Sms')->add($data);
				
				return;
			}
			
		}else{
			$data['content'] = $content;
			$data['from'] = $from;
			$data['type'] = $type == 1 ? '支付宝' : '银行卡';
			$data['status'] = '失败 正则匹配错误';
			$data['add_time'] = $_POST['sent_timestamp'];
			M('Sms')->add($data);
			return;
		}
		
		
		
		
		
		
		
	}
	
	private function getConfig(){
		$list=M("Config")->select();
		foreach ($list as $k=>$v){
            $list[$v['key']]=$v['value'];
        }
		return $list;
	}
	
}