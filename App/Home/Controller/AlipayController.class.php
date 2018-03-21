<?php
namespace Home\Controller;
use Common\Controller\CommonController;
use Home\Controller\PaymentController;
class AlipayController extends CommonController {

    public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }

    public function index(){
        /*if($this->config['DISANFANG'] != 1){
             $this->error("第三方充值未开启！");
        }*/
        $this->User_status();
        $where="";
        if(!empty($_SESSION['USER_KEY_ID'])){
            $where['member_id']=$_SESSION['USER_KEY_ID'];
        }
        $list =  M('Payment')
              -> Field('payment_id,member_id,price,status,pay_no,add_time,end_time')
              -> where($where)
              -> order("add_time desc")
              -> select();
        $this->assign('list',$list);
        $this->display();
    }

    public function aliPagePay(){
        //dump($_POST);
        $this->User_status();
        $member=$this->member;
        //dump(($member['name'] != $_POST['member_name']));
        if($member['name'] != $_POST['member_name']){
            $this->error('姓名不正确');
        }
        $data['member_name']=I('post.member_name');
        $data['money']=I('post.p3_Amt');
        $data['type']=intval(I('post.type'));
        $data['account']=I('post.account');
        $data['count']=I('post.p3_Amt') - $_SESSION['USER_KEY_ID']/100;
        //dump(empty($data['member_name'])||empty($data['money']));die;
        if(empty($data['member_name'])||empty($data['money'])){
                // $arr['info']='请填写全部信息';
                $this->error('请填写全部信息');
        }
        if(strlen($data['account'])<11||strlen($data['account'])>20){
                // $arr['info']='请输入正确的银行卡号或支付宝账号';
                $this->error('请输入正确的支付宝账号');
        }
        if($data['type'] == 1 && $data['money'] < $this->config['pay_min_money']){
            $this->error('支付宝充值金额最小为'.$this->config['pay_min_money'].'元');

        }
        if($data['type'] == 1){
                $type_r = 1;
        }
        $data['member_id'] = session('USER_KEY_ID');
        $data['add_time']=time();
        $data['status']=0;
        //商户订单号，商户网站订单系统中唯一订单号，必填
        //返回当前 Unix 时间戳的微秒数
        $b=microtime() * 1000000;
        //订单号
        /*@param    $b            规定要填充的字符串
          @param    3             规定新的字符串长度
          @param    0             规定供填充使用的字符串。默认是空白
          @param    STR_PAD_LEFT  填充字符串的左侧
        */
        $out_trade_no =  date('Ymd').str_pad($b, 3, '0', STR_PAD_LEFT);
            //订单名称，必填
            //dump($out_trade_no);die;
            $proName = '账户充值'; //trim($_POST['WIDsubject']);
            //付款金额，必填
            $total_amount = trim($_POST['p3_Amt']);
            //商品描述，可空
            $body = '充值';//trim($_POST['WIDbody']);
            //记录到数据库
            $data['pay_id'] = $out_trade_no;
            $model = M('Pay');
            $insert_success = $model->add($data);
            //dump($data);die;
            Vendor('Alipay.aop.AopClient');
            Vendor('Alipay.aop.request.AlipayTradePagePayRequest');
            //请求支付宝
            $c = new \AopClient();
            $config = C('ALIPAY_CONFIG');
            $c->gatewayUrl = $config['gatewayUrl'];
            $c->appId = $config['app_id'];
            $c->rsaPrivateKey = $config['merchant_private_key'];
            $c->format = $config['format'];
            $c->charset= $config['charset'];
            $c->signType= $config['sign_type'];
            $c->alipayrsaPublicKey = $config['alipay_public_key'];
            $request = new \AlipayTradePagePayRequest();
            //dump($config);die;
            $request->setReturnUrl($config['return_url']);
            $request->setNotifyUrl($config['notify_url']);
            $request->setBizContent("{" .
                "    \"product_code\":\"FAST_INSTANT_TRADE_PAY\"," .
                "    \"subject\":\"$proName\"," .
                "    \"out_trade_no\":\"$out_trade_no\"," .
                "    \"total_amount\":$total_amount," .
                "    \"body\":\"$body\"" .
                "  }");
            $result = $c->pageExecute ($request);

            if($insert_success){
                //输出
                echo $result;
            }
            else{
            echo "系统错误，请稍后再试";
            }
    }

    //存放调式日志
    private function writeLog($content, $path = '')
    {
        if(!$path) $path = './'. date('Y-m-d'). '.log'; 
        $content = date('Y-m-d H:i:s'). ' '. $content. "\r\n";
        file_put_contents($path, $content, FILE_APPEND);
    }


    public function notify_alipay(){
        Vendor('Alipay.pagepay.service.AlipayTradeService');
        $arr=$_POST;
        //日志
        $this->writeLog(json_encode($arr));       
        $config = C('ALIPAY_CONFIG');
        $alipaySevice = new \AlipayTradeService($config);
        //$alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        /* 验证过程。
        1、要验证该通知数据中的out_trade_no是否为系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，可能有多个seller_id/seller_email）
        4、验证app_id是否为该本身。
        */
        if($result) {//验证成功
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
            //如果有做过处理，不执行商户的业务程序
            //注意：
            //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
            //如果有做过处理，不执行商户的业务程序
            //注意：
                $model = M('Pay');
            //查询数据库,是否存在订单号
                $where['pay_id'] = $arr['out_trade_no'];
                $list = $model->where($where)->find();
            //本地系统的订单状态是否为审核中
                    if($list['status'] == 0 && $list['money'] == $total_amount && $config['app_id'] == $app_id){
                        $where['money'] = $total_amount;
                        //设置订单状态为成功
                        $model->where($where)->setField('status','1');
                        if($list['money']>=$this->congif['pay_reward_limit']){
                            $list['count']=$list['count']+$list['money']*$this->config['pay_reward']/100;
                        }
                            //修改member表钱数
                            $rs=M('Member')->where("member_id='".$list['member_id']."'")->setInc('rmb',$list['money']*0.995);
                            if(!$rs){
                                $this->error("操作失败");
                            }
                            //添加财务日志
                            $this->addFinance($list['member_id'],6,"支付宝充值".$list['money']."。",$list['money'],1,0);
                            //添加信息表
                            $this->addMessage_all($list['member_id'], -2, '支付宝充值成功', '您充值已成功，充值金额为'.$list['money']);
                        if($rs){
                            echo "success"; //请不要修改或删除
                        }
                        //付款完成后，支付宝系统发送该交易状态通知
                        }else {
                            //验证失败
                            echo "fail";
                        }
                        }else {
                            //验证失败
                            echo "fail";
                        }
                        }else {
                            //验证失败
                                echo "fail";
                        }
    }

    public function return_alipay(){
        Vendor('Alipay.pagepay.service.AlipayTradeService');
        // $config = C('ALIPAY_CONFIG');
        require('./ThinkPHP/Library/Vendor/Alipay/config.php');
        $arr=$_GET;
        //dump($arr);die;
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result ) {//验证成功
            $model = M('Pay');
            //查询数据库,是否存在订单号
            $where['pay_id'] = $arr['out_trade_no'];
            $where['status'] = 0;
            $list = $model->where($where)->find();
            //dump($list);die;
            //商户订单号
            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            //订单总金额
            $total_amount = htmlspecialchars($_GET['total_amount']);
            //商户的支付宝app_id
            $app_id = htmlspecialchars($_GET['app_id']);
            //验证信息
                if($list['pay_id'] == $out_trade_no && $list['money'] == $total_amount && $config['app_id'] == $app_id){
                    $where['money'] = $total_amount;
                    //设置订单状态为成功
                    $model->where($where)->setField('status','1');
                    if($list['money']>=$this->congif['pay_reward_limit']){
                        $list['count']=$list['count']+$list['money']*$this->config['pay_reward']/100;
                    }
                    //修改member表钱数
                    $rs=M('Member')->where("member_id='".$list['member_id']."'")->setInc('rmb',($list['money']*0.995));
                    if(!$rs){
                        $this->error("操作失败");
                    }
                    //添加财务日志
                    $this->addFinance($list['member_id'],6,"支付宝充值".$list['money']."。",$list['money'],1,0);
                    //添加信息表
                    $this->addMessage_all($list['member_id'], -2, '支付宝充值成功', '您充值已成功，充值金额为'.$list['money']);
                    if($rs){
                        $data['status'] = 1;
                        $data['info'] = "充值成功,已经到账,支付宝交易号：".$trade_no;
                        $this->assign('data',$data);
                        $this->display();
                    }
                    }else{
                        $data['status'] = 2;
                        $data['info'] = "充值失败,系统中无此订单,请联系客服。支付宝交易号：".$trade_no;
                        $this->assign('data',$data);
                        $this->display();
                    }
                }else{
                        $data['status'] = 2;
                        $data['info'] = "充值失败,验证不成功。支付宝交易号：".$trade_no;
                        $this->assign('data',$data);
                        $this->display();
                    }
    }
//文件为结尾
}
