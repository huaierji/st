<?php
namespace Home\Controller;
use Home\Controller\HomeController;

class AlidirectController extends HomeController {
	
    //空操作
    public function _initialize(){
    }
    public function index(){
        //您在www.zfbjk.com的商户ID
		$alidirect_pid = "22471";
		//您在www.zfbjk.com的商户密钥
		$alidirect_key = "43e3e540de90339fd960d00aba42136d";
		
		$tradeNo = isset($_POST['tradeNo'])?$_POST['tradeNo']:'';
		$Money = isset($_POST['Money'])?$_POST['Money']:0;
		$title = isset($_POST['title'])?$_POST['title']:'';
		$memo = isset($_POST['memo'])?$_POST['memo']:'';
		$alipay_account = isset($_POST['alipay_account'])?$_POST['alipay_account']:'';
		$Gateway = isset($_POST['Gateway'])?$_POST['Gateway']:'';
		$Sign = isset($_POST['Sign'])?$_POST['Sign']:'';
		if(strtoupper(md5($alidirect_pid . $alidirect_key . $tradeNo . $Money . iconv("UTF-8", "GB2312//IGNORE", $title) . iconv("UTF-8", "GB2312//IGNORE", $memo))) == strtoupper($Sign))
		{
			$m = M('Pay');
			$where['money'] = $Money;
			$where['status'] = 0;
			$order = $m->where($where)->limit(1)->order('pay_id desc')->select();
			if(!$order){exit("IncorrectOrder");}
			$member_id = $order[0]["member_id"];
			$data = array();
			$data['member_id'] = $member_id;
			$data["count"] = $Money;
			$m->execute("update yang_pay set status = 1,count = $Money where money = $Money and status = 0 order by pay_id desc limit 1");
			$r[] =M('Member')->where(array('member_id' => $data['member_id']))->setInc('rmb', $data['count']);
			//添加财务日志
			$r[] = $this->addFinance($data['member_id'], 6, "在线充值".$data['count']."。", $data['count'], 1, 0);
			//添加信息表
			$r[] = $this->addMessage_all($data['member_id'], -2, '在线充值成功', '您申请的在线充值已成功，充值金额为'.$data['count']);
			//print_r($r);
			exit("Success");
		}
		else
		{
			exit("Fail");
		}
    }
}
