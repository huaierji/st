<?php

namespace Api\Controller;

use Api\JWT\JWT;
use Think\Cache\Driver\Redis;

class LoginController extends ApiController
{

     //处理登录
    public function login(){
        $email = I('post.email');
        $pwd = md5(I('post.pwd'));
        $M_member = D('Member');
        //再次判断

        if((checkEmail($email) || checkMobile($email))==false){

            $data['status']=1;
            $data['info']="请输入正确的手机或者用户名";
            $this->ajaxReturn($data);
        }
        //判断传值是手机还是email
        $info = checkEmail($email)?$M_member->logCheckEmail($email):$M_member->logCheckMo($email);

        if($info['status']==2){
            $data['status']=2;
            $data['info']="账号或密码错误";
            $this->ajaxReturn($data);

        }
        //验证手机或用户名
        if($info==false){
            $data['status']=2;
            $data['info']="账号或密码错误";
            $this->ajaxReturn($data);

        }
        //验证密码
        if($info['pwd']!=$pwd){

            $data['status']=2;
            $data['info']="账号或密码错误";
            $this->ajaxReturn($data);

        }

        //获取下方能用到的参数
        $new_ip = get_client_ip();

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
        //加密token的key,解密时也要用到
        $key = "stjfw";
        $token = array(
            'uid' => $where['member_id'],
            'username' => $email
        );
        //存入redis缓存
        $redis = new Redis();

        $redis->set("token" . $where['member_id'], $token, 300);

        $response = array(
            "token" => JWT::encode($key, $token)
        );

        $info = array(
            "msg" => "登录成功",
            "response" => $response,
            'status' => 200
        );

        $this->ajaxReturn($info);
    }
}