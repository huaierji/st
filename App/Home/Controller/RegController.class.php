<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class RegController extends CommonController {
    public function success(){		
        $this->display();
    }

    /**
     * 显示注册界面
     */
    public function reg(){
        if(session('USER_KEY_ID')){
            $this->redirect('User/index');
            return;
        }
        $pid = I('get.Member_id','','intval');
        $this->assign('pid',$pid);
		$this->assign("style",2);
        $this->display();
    }

    /**
     * 显示服务条款
     */
    public function terms(){
        //$list = M('Article')->where(array('article_id'=>125))->find();
        //$this->assign('list',$list);
        $this->display();
    }

    /**
     * 添加注册用户
     */
    public function addReg(){
        if(IS_POST){
            //增加添加时间,IP
            $_POST['reg_time'] = time();
            $_POST['ip'] = get_client_ip();
			//增加条件  每个IP只能注册一个账号  2017.02.04  GS
			/*$reg_ip = S('reg_ip_limit');
			if(!empty($reg_ip)){
				if(in_array($_POST['ip'],$reg_ip)){
					$data['status'] = 0;
					$data['info'] = "每个IP只能注册一个账号";
					$this->ajaxReturn($data);return;
				}
			}
			$reg_ip[] = $_POST['ip'];
			S('reg_ip_limit',$reg_ip);
			*/
            $pid= intval(I("post.pid"));
            $M_member = D('Member');
            if(!empty($pid)){
                //查询推荐人是否存在
                //$resss = M('Member')->where([`member_id` => I('post.pid'),'status' => ['neq',2] ])->find();
                //推荐人必须已经实名认证
                //$resss = M('Member')->where("`member_id`={$_POST['pid']} AND status!=2")->find();               
                //推荐人必须已经实名认证更改为注册用户都能推荐，但只有被实名认证后才会有奖励
                $where['member_id'] = $pid;
                $where['status'] = array('neq',2);
                $resss = M('Member')
                       -> where($where)
                       -> find();                           
                //推荐人必须已经实名认证
                if(empty($resss)){
                    $data['status'] = 0;
                    $data['info'] = "找不到推荐人，请重新确认填写";
                    $this->ajaxReturn($data);return;                   
                }
            }
            if($_POST['pwd']==$_POST['pwdtrade']){
            	$data['status'] = 0;
            	$data['info'] = "交易密码不能和密码一样";
            	$this->ajaxReturn($data);           	
            	//$this->error($M_member->getError());
            	return;
            }
			$verify = new \Think\Verify();
            if(!$verify->check($_POST['code'])){
                $data['status']=0;
                $data['info']="验证码输入错误";
                $this->ajaxReturn($data);
				return;
            }
            if (!$M_member->create()){
                //如果创建失败 表示验证没有通过 输出错误提示信息
                $data['status'] = 0;
                $data['info'] = $M_member->getError();
                $this->ajaxReturn($data);
                //$this->error($M_member->getError());
                return;
            }else{
                $r = $M_member->add();
                if($r){
                    session('USER_KEY_ID',$r);//传入session避免直接进入个人信息界面
                    session('USER_KEY',$_POST['email']);//用户名
                    session('STAUTS',0);
                    session('procedure',1);//SESSION 跟踪第一步
					//file_put_contents('./1.txt',$_POST['email'].'&&'.date('Y-m-d H:i:s',time()).'|',FILE_APPEND);					
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
                    
                    
                    $data['status'] = 1;
                    $data['info'] = '提交成功';
                    $this->ajaxReturn($data);
                    //$this->redirect('ModifyMember/modify');
                }else{
                    $data['status'] = 0;
                    $data['info'] = '服务器繁忙,请稍后重试';
                    $this->ajaxReturn($data);
                    //$this->error('服务器繁忙,请稍后重试');
                    //return;
                }
            }
        }else{
            $this->display('Reg/reg');
        }
    }

    /**
     * 注册成功
     */
    public function regSuccess(){

        if(session('USER_KEY_ID')){
            $this->redirect('User/index');
            return;
        }
        //判断步骤并重置
        if(session('procedure')==2){
            session('procedure',null);
            $this->display();
        }
        if(session('procedure')==1){
            $this->redirect('Reg/reg');
        }

    }
					
    /**
     * ajax验证用户名
     * @param string $email 规定传参数的结构
     * 
     */
    public function ajaxCheckEmail($email){
        $email = urldecode($email);
        $data = array();
        if(!checkEmail($email)){
            $data['status'] = 0;
            $data['msg'] = "用户名格式错误";
        }else{
            $M_member = M('Member');
            $where['email']  = $email;
            $r = $M_member->where($where)->find();
            if($r){
                $data['status'] = 0;
                $data['msg'] = "用户名已存在";
            }else{
                $data['status'] = 1;
                $data['msg'] = "";
            }
        }
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
    
    
    
}
