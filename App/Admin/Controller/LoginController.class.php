<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
use Think\Verify;
class LoginController extends CommonController {
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	public function login(){
        $this->display();
    }
    //登录验证
    public function checkLogin(){
    	$username=trim(I('post.username'));
    	$pwd=trim(I('post.pwd'));
     	if(empty($username)||empty($pwd)){
     		$this->error('请填写完整信息', 'login');
     	}
     	$admin=M('Admin')->where("username='$username'")->find();
     	if($admin['password']!=md5($pwd)){
     		$_SESSION['ADMIN_NUM']++;
     		$this->error('登录密码不正确','login');
     	}
     	
     	if($_SESSION['ADMIN_NUM']>=3){
     		if(empty($_POST['captcha'])){
     			$this->error('请输入验证码','login');
     		}
     		$verify = new Verify();
     		if(!$verify->check($_POST['captcha'])){
     			$this->error('验证码输入错误','login');
     		}
     	}
     	
    	$_SESSION['admin_userid']=$admin['admin_id'];
    	$_SESSION['ADMIN_NUM']=0;
		$ip = get_ip();
		
		$arrs['ip'] = $ip;
		$arrs['add_time'] = date('Y-m-d H:i:s',time());
		$arrs['username'] = $username;
		M('Admin_log')->add($arrs);
		
    	$this->redirect('Index/index');
    }

    //登出
    public function loginout(){
    	$_SESSION['admin_userid']=null;
    	$this->redirect('Login/login');
    }
    
    public function showVerify(){
    	$config =	array(
    			'fontSize'  =>  18,              // 验证码字体大小(px)
    			'useCurve'  =>  true,            // 是否画混淆曲线
    			'useNoise'  =>  true,            // 是否添加杂点
    			'imageH'    =>  40,               // 验证码图片高度
    			'imageW'    =>  150,               // 验证码图片宽度
    			'length'    =>  4,               // 验证码位数
    			'fontttf'   =>  '4.ttf',              // 验证码字体，不设置随机获取
    	);
    	$Verify =     new Verify($config);
    	$Verify->entry();
    }
     
}