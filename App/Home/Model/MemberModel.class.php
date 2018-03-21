<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-3-8
 * Time: 下午2:23
 */

namespace Home\Model;
use Think\Model;

class MemberModel extends Model{

    protected $_validate = array(
        //array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('agree','1','条款必须同意',1,'equal',1),//同意条款
        //array('email','email','用户名格式不正确',1,'',1), //验证邮箱
        array('pwd','require','登录密码不能为空',1,'',1),
        array('pwd','checkPwd','密码格式不正确',1,'function',1), //函数认证密码(同样要求)
        array('repwd','pwd','确认密码不正确',1,'confirm',1), //二次密码是否一样
    	array('email','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
    	array('phone','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证phone字段是否唯一
    	array('idcard','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证idcard字段是否唯一
        array('pwdtrade','require','交易密码不能为空',1,'',1),
        array('pwdtrade','checkPwd','交易密码不正确',1,'function',1), // 函数认证支付密码(同样要求)
        array('repwdtrade','pwdtrade','确认交易密码不正确',1,'confirm',1), // 二次密码是否一样
        //modify验证
        array('nick','require','昵称不能为空',1,'',2),
        array('name','require','真实姓名不能为空',1,'',2),
        array('idcard','require','身份证号码必须填写',1,'',2),
	    array('idcard','idcard','身份证号已经注册',1,'function',1),
        array('phone','require','手机号码必须填写',1,'',2),
    );

    protected $_auto = array(
        //array(完成字段1,完成规则,[完成条件,附加规则]),
        array('pwd','md5',1,'function'), //加密登录密码
        array('pwdtrade','md5',1,'function'), // 加密支付密码
    );

    /**
     * 验证密码长度在6-20个字符之间
     * @param $pwd
     * @return bool
     */
    public function checkPwd($pwd){
        $pattern="/^[a-zA-Z0-9]{6,20}$/";
        if(preg_match($pattern, $pwd)){
            return true;
        }else{
            return false;
        }
    }
   //验证用户名是否重复
    public function logCheckEmail($email){
        $where['email'] = $email;
        $info = $this->where($where)->find();
        if($info){
            return $info;
        }else{
            return false;
        }
    }
    //验证手机号码重复
    public function logCheckMo($mo){
        $where['phone'] = $mo;
        $info = $this->where($where)->find();
        if($info){
            return $info;
        }else{
            return false;
        }
    }
    //验证码验证
    function checkPhoneCode($code){
        if (session('code')!=$code || session('code') == '' || session('phone_code') != $_POST['phone']){
            return  false;
        }else {
			session('code',null);
            return true;
        }
    }
	//验证身份证号码是否重复
	function idcard($idcard){
		$where['idcard'] = $idcard;
        $info = $this->where($where)->find();
        if($info){
            return $info;
        }else{
            return false;
        }
	}

}
