<?php
namespace Home\Controller;
use Home\Controller\HomeController;
use Think\Page;
use Think\Upload;

class PayinterfaceController extends HomeController {
	//商户号
   private $mer_code = '10129601';

   //商户证书
   private $mer_key = '0JWNyOQxYzwKln2cIXcnddQEk40mqbOVNqoOLYhtKTe4UjdsbUy9kWxwTrDTxEgWB1XUZDqKQI2jZvKb1ilwDpLQTRV4vnWgNhT3lisxDEI14nx76B2p2KtwFgbhHMzC';

    //空操作
    public function _initialize(){
      header('Content-type:text/html;charset=utf-8');
      parent::_initialize();
    }

    public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }
    /**
     * 定义参数
     */
	public function orderPay(){
		$config=$this->config;
		$data['money']=intval(I('post.money'));
		if($data['money'] < $config['pay_min_money']){
			$arr['info']="充值金额不能小于{$config['pay_min_money']}元";
			$arr['status']=0;
			$this->ajaxReturn($arr);
		}
		if($data['money']>$config['pay_max_money']){
			$arr['info']="充值金额不能大于{$config['pay_max_money']}元";
			$arr['status']=0;
			$this->ajaxReturn($arr);
		}
      $Attach   = session('USER_KEY_ID');
      if (empty($Attach) || !is_numeric($Attach)) {
         $arr['info']="请登陆后再充值";
         $arr['status']=0;
         $this->ajaxReturn($arr);
      }
		$Mer_code = $this->mer_code;
		$Mer_key  = $this->mer_key;
		$Billno   = date('YmdHis') . mt_rand(100000,999999);
		$Amount   = 0.1;
		$Date     = date('Ymd');
		$Currency_Type = 'RMB';
		$Gateway_Type  = '01';
		$Lang =  'GB';

		$Merchanturl = 'http://www.g-valley123.com/home/payinterface/orderreturn';
		$OrderEncodeType = '2';
		$RetEncodeType = '12';
		$ServerUrl =  'http://www.g-valley123.com/home/payinterface/nireturn';
		$form_url = 'https://payment.cai1pay.com/gateway.aspx';
//    		$form_url = 'http://testpay.cai1pay.com/gateway.aspx';
		$SignMD5 = md5($Billno . $Amount . $Date . $Currency_Type . $Mer_key);

$return_html =<<<EOF
<form action="$form_url" method="post" id="frm1" target="_blank">
<input type="hidden" name="MerCode" value="$Mer_code">
<input type="hidden" name="MerOrderNo" value="$Billno">
<input type="hidden" name="Amount" value="$Amount" >
<input type="hidden" name="OrderDate" value="$Date">
<input type="hidden" name="Currency" value="$Currency_Type">
<input type="hidden" name="GatewayType" value="01">
<input type="hidden" name="Language" value="GB">
<input type="hidden" name="ReturnUrl" value="$Merchanturl">
<input type="hidden" name="GoodsInfo" value="$Attach">
<input type="hidden" name="OrderEncodeType" value="2">
<input type="hidden" name="RetEncodeType" value="12">
<input type="hidden" name="Rettype" value="1">
<input type="hidden" name="ServerUrl" value="$ServerUrl">
<input type="hidden" name="SignMD5" value="$SignMD5">
<input type="hidden" name="DoCredit" value="" />
</form>
<script language="javascript">
document.getElementById("frm1").submit();
</script>
EOF;
		$arr['info']=$return_html;
		$arr['status']=1;
		$this->ajaxReturn($arr);
	}

	/**
	 * 同步方法
	 */
	public function orderreturn(){
      $param = array();
      $param['order_id'] = I('get.MerOrderNo');
      $param['amount'] = I('get.Amount');
      $param['mydate'] = I('get.OrderDate');
      $param['succ'] = I('get.Succ');
      $param['msg'] = I('get.Msg');
      $param['attach'] = I('get.GoodsInfo');
      $param['sysorderno'] = I('get.SysOrderNo');
      $param['retencodeType'] = I('get.RetencodeType');
      $param['currency'] = I('get.Currency');
      $param['signature'] = I('get.Signature');
      file_put_contents('y-aa.txt', serialize($param));
      if ($this->signature($param)) {
         file_put_contents('y-bb.txt', serialize($param));
         if ($param['succ'] == 'Y') {
            $status = !empty($param['attach']) && is_numeric($param['attach']) ? 1 : 2;
            $ores = M('Pay')->where(array('member_name' => $param['order_id']))->find();
            if ($ores) {
               if ($ores['status'] == 1) {
                  $this->redirect('User/index');
               }else {
                  exit('订单异常，充值失败');
               }
            }
            
            $data = array();
            $data['member_id'] =  $param['attach'];
            $data['member_name']  =  $param['order_id'];
            $data['money']     =  $param['amount'];
            $data['account'] = "网银充值";
            $data['count'] = $param['amount'];
            $data['add_time'] = time();
            $data['status'] = $status;

            $pay_id = M('Pay')->add($data);

            if (!$pay_id) exit('系统异常，充值失败！');

            if ($status == 1) {
               $r[] =M('Member')->where(array('member_id' => $data['member_id']))->setInc('rmb', $data['count']);
               //添加财务日志
               $r[] = $this->addFinance($data['member_id'], 6, "在线充值".$data['count']."。", $data['count'], 1, 0);
               //添加信息表
               $r[] = $this->addMessage_all($data['member_id'], -2, '在线充值成功', '您申请的在线充值已成功，充值金额为'.$data['count']);
            }else {
               //$r[] = M('Member')->where(array('member_id' => $data['member_id']))->setInc('rmb',$data['count']);
               //添加信息表
               $r[] = $this->addMessage_all($data['member_id'], -2, '在线充值未通过', '您申请的在线充值未通过,请重新处理');
            }
            //#################################################
            //交易成功，此处可增加商户逻辑
            //#################################################
            $this->redirect('User/index');
         }else {
            exit('系统繁忙，充值失败，请稍后再试');
         }
      }else {
         exit('系统繁忙，充值失败，请稍后再试');
      }  
	}

	public function nireturn(){
		$param = array();
      $param['order_id'] = I('post.MerOrderNo');
      $param['amount'] = I('post.Amount');
      $param['mydate'] = I('post.OrderDate');
      $param['succ'] = I('post.Succ');
      $param['msg'] = I('post.Msg');
      $param['attach'] = I('post.GoodsInfo');
      $param['sysorderno'] = I('post.SysOrderNo');
      $param['retencodeType'] = I('post.RetencodeType');
      $param['currency'] = I('post.Currency');
      $param['signature'] = I('post.Signature');
      file_put_contents('t-aa.txt', serialize($param));
      if ($this->signature($param)) {
         file_put_contents('t-bb.txt', serialize($param));
         if ($param['succ'] == 'Y') {
            $status = !empty($param['attach']) && is_numeric($param['attach']) ? 1 : 2;
            $ores = M('Pay')->where(array('member_name' => $param['order_id']))->find();
            if ($ores) {
               if ($ores['status'] == 1) {
                  return false;
               }else {
                  return false;
               }
            }
            
            $data = array();
            $data['member_id'] =  $param['attach'];
            $data['member_name']  =  $param['order_id'];
            $data['money']     =  $param['amount'];
            $data['account'] = "网银充值";
            $data['count'] = $param['amount'];
            $data['add_time'] = time();
            $data['status'] = $status;

            $pay_id = M('Pay')->add($data);

            if (!$pay_id) return false;

            if ($status == 1) {
               $r[] =M('Member')->where(array('member_id' => $data['member_id']))->setInc('rmb', $data['count']);
               //添加财务日志
               $r[] = $this->addFinance($data['member_id'], 6, "在线充值".$data['count']."。", $data['count'], 1, 0);
               //添加信息表
               $r[] = $this->addMessage_all($data['member_id'], -2, '在线充值成功', '您申请的在线充值已成功，充值金额为'.$data['count']);
            }else {
               //$r[] = M('Member')->where(array('member_id' => $data['member_id']))->setInc('rmb',$data['count']);
               //添加信息表
               $r[] = $this->addMessage_all($data['member_id'], -2, '在线充值未通过', '您申请的在线充值未通过,请重新处理');
            }
            //#################################################
            //交易成功，此处可增加商户逻辑
            //#################################################
            $this->redirect('User/index');
         }else {
            return false;
         }
      }else {
         return false;
      }
   }

   /**
    * 签名验证
    */
   private function signature($param = array()) {
      if (empty($param) || !is_array($param)) return false;
      $signature = md5($param['order_id'] . $param['amount'] . $param['mydate'] . $param['succ'] . $param['sysorderno'] . $param['currency'] . $this->mer_key);
      if ($param['signature'] == $signature) {
         return true;
      }else {
         return false;
      }
   }
}