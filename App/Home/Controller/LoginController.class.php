<?php
/**
 * Created by PhpStorm.
 * User: "姜鹏"
 * Date: 16-3-9
 * Time: 上午9:06
 */

namespace Home\Controller;
use Common\Controller\CommonController;
use Think\Verify;

class LoginController extends CommonController{
    /**
     * 展示界面
     */
    
    public function index(){        
        if(session('')){
            $this->redirect('Index/index');
            return;
        }
		$this->assign("style",1);
        $this->display();
    }
	
    public function login(){
    	$this->redirect(U('Login/index','','',false));
    }
    /**
     * 处理登录请求
     * 全部用ajax提交
     */
    public function checkLog(){
        $email = I('post.email');
        $pwd = md5(I('post.pwd'));
        $M_member = D('Member');
        //再次判断       
        if((checkEmail($email) || checkMobile($email))==false){
            $data['status']=2;
            $data['info']="请输入正确的手机或者用户名";
            $this->ajaxReturn($data);
        }
        //判断传值是手机还是email
        $info = checkEmail($email)?$M_member->logCheckEmail($email):$M_member->logCheckMo($email);      
        if($info['status']==2){
        	$_SESSION['NUM']++;
            $data['status']=2;
            $data['info']="账号或密码错误";
            $this->ajaxReturn($data);           
        }
        //验证手机或用户名
        if($info==false){
        	$_SESSION['NUM']++;
            $data['status']=2;
            $data['info']="账号或密码错误";
            $this->ajaxReturn($data);           
        }
        //验证密码
        if($info['pwd']!=$pwd){
        	$_SESSION['NUM']++;
        	//dump($_SESSION['NUM']);
            //$this->error('密码输入错误');
            $data['status']=2;
            $data['info']="账号或密码错误";
            $this->ajaxReturn($data);
          
        }       
        if(empty($_POST['captcha'])){
            $data['status']=2;
            $data['info']="请填写验证码";
            $this->ajaxReturn($data);
        }
        $verify = new Verify();
        if(!$verify->check($_POST['captcha'])){
            $data['status']=2;
            $data['info']="验证码输入错误";
            $this->ajaxReturn($data);
        }
        
        //获取下方能用到的参数
         $new_ip = get_client_ip();
//         $old_login_ip = $info['login_ip']?$info['login_ip']:$info['ip'];
//         $card = I('post.year').I('post.month').I('post.day');
//         $idcard = substr($info['idcard'],6,8);
//         //验证身份信息如果身份证存在并且 当前IP 和上次登录Ip不一样
//         if($old_login_ip != $new_ip && $info['idcard'] ){
//             if($card != $idcard){
//                 $data['status']=2;
//                 $data['info']="生日与您当前填写不符";
//                 $this->ajaxReturn($data);
//             }
//         }
//        $this->pullMessage($info['member_id'],$info['login_time']?$info['login_time']:$info['reg_time'] );
        if($this->pullMessage($info['member_id'],$info['login_time']?$info['login_time']:$info['reg_time'])==false){
            $data['status']=2;
            $data['info']="服务器繁忙,请稍后重试12";
            $this->ajaxReturn($data);
        }
        //如果当前操作Ip和上次不同更新登录IP以及登录时间
        $data['login_ip'] = $new_ip;
        $data['login_time']= time();
        $where['member_id'] = $info['member_id'];
        $r = $M_member->where($where)->save($data);
        if($r===false){
            $data['status']=2;
            $data['info']="服务器繁忙,请稍后重试";
            $this->ajaxReturn($data);
        }
        session(array());
        session('USER_KEY_ID',$info['member_id']);
        session('USER_KEY',$info['email']);//用户名
        session('STATUS',$info['status']);//用户状态
		//修正币种信息
		
		$currency= $this->currency;
 		foreach ($currency as $k=>$v){
			
			$trade = S('trade_info_'.$v['currency_id']);
			if(date('Y-m-d',$trade['time']) != date('Y-m-d')){
				$trade = S('trade_info_'.$v['currency_id'],null);
			}			
 			$rs=$this->getCurrencyUser($_SESSION['USER_KEY_ID'], $v['currency_id']);
 			if(!$rs){
 				$this->addCurrencyUser($_SESSION['USER_KEY_ID'],$v['currency_id']);
 			}
 		}
		
		
		//发放奖励
		$list = M('Reward_reg')->where("member_id={$info['member_id']} AND status=0")->select();
		if(!empty($list)){
			//首先检测上次发放时间
			$info = M('Reward_log')->where("reward_id={$list[0]['id']}")->order('add_time desc')->find();
			//计算间隔发放时间
			if(!empty($info)){
				$time_jiange = max(0,time() - $info['add_time']) ;				
				$num = floor($time_jiange / (24 * 60 * 60 ));
			}else{
				if(date('Y-m-d',$list[0]['add_time']) != date('Y-m-d',time())){
					$num = 1;
				}else{
					$num = 0;
				}
				
			}
			
			for($i= $num ; $i>0 ; $i-- ){
				$add_time = time() - ( ($i - 1) * (24 * 60 * 60 ) );  //添加时间
				foreach($list as $k=>$v){
					$data['reward_id'] = $v['id'];
					$data['money'] = floor($v['money']/$v['sum_day']*1000000)/1000000;
					$data['currency_id'] = $v['currency_id'];
					$data['add_time'] = strtotime(date("Y-m-d 00:00:00",$add_time));
					$data['status'] = 0;
					$res = M('Reward_log')->add($data);
					
					if($res){ //如果添加成功则发放奖励且添加日志
						M('Currency_user')->where("member_id={$v['member_id']} AND currency_id={$v['currency_id']}")->setInc('num',$data['money']);
						
						//加入日志
						if($v['down_id'] == 0){
							$this->addFinance($v['member_id'], 25, '被推荐奖励',$data['money'],1,$v['currency_id'],$data['add_time']);
						}else{
							$name = M('Member')->where("`member_id`={$v['down_id']}")->find();
							$name_format = substr($name['email'],0,-2).'**';
							$this->addFinance($v['member_id'], 25, '推荐'.$name_format.'奖励',$data['money'],1,$v['currency_id'],$data['add_time']);
						}
						//修改剩余次数
						if($v['surplus_day'] <= 1){
							M('Reward_reg')->where("id={$v['id']}")->setField(array('surplus_day'=>0,'status'=>1));
							//unset($list);continue;
						}else{
							M('Reward_reg')->where("id={$v['id']}")->setDec('surplus_day',1);
						}
						unset($name);
						unset($data);
					}
					
				}
			}
		}
		
		$_SESSION['NUM']=0;
        $data['status']=1;
        $data['info']="登录成功";
        $this->ajaxReturn($data);
    }
	/**
		 * 添加currency_user表方法
		 * @param int $uid 会员id
		 * @param int $cid 币种id
		 */
		 public function addCurrencyUser($uid,$cid){
			$data['member_id']=$uid;
			$data['currency_id']=$cid;
			$data['num']=0;
			$data['forzen_num']=0;
			$data['status']=0;
			$rs=M('Currency_user')->add($data);
			if($rs){
				return true;
			}else{
				return false;
			}
		 }
    public function qqLogin(){
    	$app_id = C('SZ_QQ_APP_ID');
    	$app_key = C('SZ_QQ_APP_KEY');
    	$callback = C('SZ_QQ_CALLBACK');
    	$qq = new \Common\Api\QQConnect;
    	/* callback返回openid和access_token */
    	$back = $qq->callback($app_id , $app_key, $callback);
    	//防止刷新
    	empty($back) && $this->error("请重新授权登录",U('Login/index'));
    	$user_info = $qq->get_user_info($app_id,$back['token'],$back['openid']);
    	$Member = M('Member');
    	$where['threepwd']=$back['openid'];
    	$MemberArray = $Member->where("threepwd='".$back['openid']."'")->field('member_id,username,status')->find();
    	if($MemberArray['member_id']!=""){
    		session('USER_KEY_ID',$MemberArray['member_id']);
    		session('USER_KEY',$MemberArray['username']);//用户名
    		session('STATUS',$MemberArray['status']);//用户状态
    		$this->error("登陆成功",U('Index/index'));
    			
    	}else{
    			
    		$add['username'] = $back['openid'];
    		$add['threepwd'] = $back['openid'];
    		$add['pwdtrade'] = md5('111111');
    			
    			
    		if($Member->create($add)){
    			$userid = $Member->add();
    			if($userid){
    				session('USER_KEY_ID',$userid);
    				session('USER_KEY',$back['openid']);//用户名
    				session('STATUS',0);//用户状态
    				$this->error("登陆成功",U('Index/index'));
    			}else{
    				$this->error("登陆失败",U('Login/index'));
    			}
    		}
    	}
    }
    /**
     * 忘记密码
     */
    public function findpwd(){
        if(IS_AJAX){
            if(empty($_POST['email'])){
                $data['status']=2;
                $data['info']="请填写手机号码";
                $this->ajaxReturn($data);
            }
            if(!checkMobile($_POST['email'])){
                $data['status']=2;
                $data['info']="请输入正确的手机号码";
                $this->ajaxReturn($data);
            }
            if(empty($_POST['captcha'])){
                $data['status']=2;
                $data['info']="请填写验证码";
                $this->ajaxReturn($data);
            }
            $verify = new Verify();
            if(!$verify->check($_POST['captcha'])){
                $data['status']=2;
                $data['info']="验证码输入错误";
                $this->ajaxReturn($data);
            }
            $info = M('Member')->where(array('phone'=>$_POST['email']))->find();
            if($info==false){
                $data['status']=2;
                $data['info']="用户不存在";
                $this->ajaxReturn($data);
            }
			$_SESSION['find_pwd_phone'] = $_POST['email'];			
			$ip = get_ip();
			$v = S('ip_phone'.$ip);
			S('limit_phone_ip',null);
			$sss = S('limit_phone_ip');
			if($v > 10){
				//$sss[$ip] = 1;
				//S('limit_phone_ip',$sss);
			}
			if($sss[$ip] == 1){
				$data['status'] = -8;
				$data['info'] = '此IP已经被禁止发送短信';
				$this->ajaxReturn($data);
			}
			
            //$r = sandPhone($_POST['email'],$this->config['CODE_NAME'],$this->config['CODE_USER_NAME'],$this->config['CODE_USER_PASS']);
			$A_Sms = new \SmsApi();
			$r = $A_Sms ->send($_POST['email']);
			if(!$r){
				$data['status']=0;
				$data['info'] = $r;
				$this->ajaxReturn($data);
			}else{
				$data['status']=1;
				$data['info'] = '发送成功';
				$this->ajaxReturn($data);
			}
        }else{
            $this->display();
        }
    }
    /**
     * 根据发送用户名地址显示修改密码界面
     */
    public function resetPwd(){
        if (empty($_SESSION['find_pwd_phone'])) {
        	//M('findpwd')->delete($findpwd_info['id']);
            $this->success('无效链接', U('Index/index'));
            return;
        }
        if(IS_POST){
        	$phone = $_SESSION['find_pwd_phone'];
        	$code = $_POST['captcha'];
        	$A_Sms = new \SmsApi();
        	$re = $A_Sms->checkAppCode($phone, $code);
          // $code = session('code');
            if(!$re || empty($code)){
                $data['status']=2;
                $data['info']="验证码输入错误";
                $this->ajaxReturn($data);
            }
            $A_Sms->deleteSendSmsApp($phone);
            if(empty($_POST['pwd'])){
                $data['status']=2;
                $data['info']="请输入密码";
                $this->ajaxReturn($data);
            }
            if(!checkPwd($_POST['pwd'])){
                $data['status']=2;
                $data['info']="密码长度在6-20个字符之间";
                $this->ajaxReturn($data);
            }
            if($_POST['repwd'] != $_POST['pwd']){
                $data['status']=2;
                $data['info']="确认密码和密码不一致";
                $this->ajaxReturn($data);
            }
			
            $member_info = M('member')->where(array('phone'=>$_SESSION['find_pwd_phone']))->find();
            if(!empty($member_info['idcard'])){
                if($_POST['idcard']!=$member_info['idcard']){
                    $data['status']=2;
                    $data['info']="身份证输入错误";
                    $this->ajaxReturn($data);
                }
            }
            $member_newPwd = I('pwd','','md5');
            $r = M('member')
                ->where(array('member_id'=>$member_info['member_id']))
                ->setField('pwd',$member_newPwd);
            if($r===false){
                $data['status']=2;
                $data['info']="服务器繁忙,请稍后重试";
                $this->ajaxReturn($data);
            }else{
                //M('findpwd')->delete($findpwd_info['id']);
                $data['status']=1;
                $data['info']="修改成功";
                $this->ajaxReturn($data);
            }
        }else{
            //$this->assign('key',$token);
            $this->display();
        }
    }
    
    /**
     * 显示验证码
     */
    public function showVerify(){
        $config =	array(
            'fontSize'  =>  18,              // 验证码字体大小(px)
            'useCurve'  =>  true,            // 是否画混淆曲线
            'useNoise'  =>  true,            // 是否添加杂点
            'imageH'    =>  40,              // 验证码图片高度
            'imageW'    =>  150,             // 验证码图片宽度
            'length'    =>  4,               // 验证码位数
            'fontttf'   =>  '4.ttf',         // 验证码字体，不设置随机获取
        );
        $Verify = new Verify($config); 
        $Verify -> entry();
    }
    /**
     * ajax判断Ip
     * @param $email
     */
    public function checkIp($email){
        //判断传过来的是手机号还是email
        $data = array();
        if(!checkEmail($email) && !checkMobile($email)){
            $data['status'] = 2;
            $data['msg'] = '请输入正确的用户名或手机号码';
            $this->ajaxReturn($data);
        }

        if(checkEmail($email)){
            $where['email'] = $email;
        }else{ 
            $where['phone'] = $email;
        } 

        //检查用户是否存在
        $info =  M('Member')->where($where)->find();
        if(!$info){
            $data['status'] = 2;
            $data['msg'] = '用户不存在';
            $this->ajaxReturn($data);
        }
        //检查是否做了身份认证
        if($info['idcard']){
            //如果login_ip不存在那么就是第一次登录取注册IP
            $old_login_ip = $info['login_ip']?$info['login_ip']:$info['ip'];
            $new_ip = get_client_ip();
            if($old_login_ip != $new_ip){
                $data['status'] = 1;
                $data['msg'] = '系统监测到您的账号本次登录IP和上次不同，为了保障您的账户资产安全，请输入您在'.$this->config['name'].'预留的身份证上的出生日期；如还未实名认证，请联系客服认证。';
                $this->ajaxReturn($data);
            }
        }
        $data['status'] = 0;
        $data['msg'] = '';
        $this->ajaxReturn($data);
    }
    /**
     * 退出
     */
    public function loginOut(){
//      $_SESSION['USER_KEY_ID']=null;
//      $_SESSION['USER_KEY']=null;
//      $_SESSION['STATUS']=null;
    	session_destroy();
        $this->redirect('Index/index');
    }

    /**
     * 读取消息库中有自己消息的列表并且存储至个人消息库中
     * @param $id 用户ID
     * @param $login_time 用户最后一次登录时间
     * @return bool 返回 成功失败
     */
    public function pullMessage($id,$login_time){
        if(empty($id)){
            return false;
        }
        if(empty($login_time)){
            return false;
        }
        //消息库
        $M_message_all = M('message_all');
        //用户消息库
        $M_message = M('message');
        $messageAllWhere['add_time'] = array('EGT',$login_time);
        $messageAllWhere['_string'] = " u_id= -1 or  u_id = $id";
        $message_info = $M_message_all->where($messageAllWhere)->select();
        if($message_info){
            foreach ($message_info as $vo) {
                $data[] = array(
                    'member_id'=>$id,
                    'title'=>$vo['title'],
                    'type' => $vo['type'],
                    'content'=> $vo['content'],
                    'add_time'=> $vo['add_time'],
                    'status' => 0,//未读
                    'message_all_id'=> $vo['id'],
                );
            }
            if($M_message->addAll($data)===false){
                return false;
            }
        }
        return true;
    }
	
	public function bankone() {
		$this->assign('strsubmitxml',I('pGateWayReq'));
		$this->display ();
	}
	
	
	/**
	 * 外部接口
	 */
	public function firstOrderInterface(){
// 		$mark = I('mark');
		$mark = 'BNTB';
		$where['id_lock'] = 0;
		$where['currency_mark'] = $mark;
		$currency = M('Currency')->field('currency_id')->where($where)->find();
		$buy = $this->getOrdersByType($currency['currency_id'],'buy', 1, 'desc');
		$sell = $this->getOrdersByType($currency['currency_id'],'sell', 1, 'asc');
		$list['buy'] = $buy[0]['price'];
		$list['sell'] = $sell[0]['price'];
		echo json_encode($list);
	}

	
	
}
