<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-3-8
 * Time: 下午4:29
 */

namespace Home\Controller;

use Common\Controller\CommonController;
use Think\Upload;
use OSS\Core\OssException;


class ModifyMemberController extends CommonController {
    /**
     * 添加个人信息
     */
    public function modify(){
        //判断是否是已经完成reg基本注册

       $login=$this->checkLogin();
     
       if(!$login){
      	 	$this->redirect('User/index');
       		return;
       }
       if(session('STATUS')!=0){
            $this->redirect('User/index');
            return;
        }

        if(IS_POST){
			
            $M_member = D('Member');
            $id = session('USER_KEY_ID');
            $_POST['member_id']=$id;
            $_POST['status'] = 3;//0=有效但未填写个人信息1=有效并且填写完个人信息2=禁用;3=填写了基本信息未审核;4实名认证不通过;5实名认证
            $phone = I('phone');
            $code = I('code');
            $A_Sms = new \SmsApi();
            $re = $A_Sms->checkAppCode($phone, $code);
            //dump($re);
$re=1;
            if(!$re){
            	$arr['status']=11;
            	$arr['info']="验证码不正确";
            	$this->ajaxReturn($arr);exit;
            }
            $A_Sms->deleteSendSmsApp($phone);
			$res = M('Member')->save($_POST);
            if (!$res){ // 创建数据对象
                 // 如果创建失败 表示验证没有通过 输出错误提示信息
                $data['status'] = 0;
                $data['info'] = '失败';
                $this->ajaxReturn($data);
                return;
            }else {
                $where['member_id'] = $id;
                $r = $M_member->where($where)->save(array('status'=>1));
                $info['bname']=I("bname");
                $info['address']=I("address");
                $info['cardnum']=I("cardnum");
                $info['bankname'] = I("bankname");
                $info['cardname'] = I("name");
                $info['uid'] = session('USER_KEY_ID');
                $re = M("bank")->add($info);
                if($r){
                    session('procedure',2);//SESSION 跟踪第二步
                    session('STATUS',1);
					//产生推荐奖励
					$info = M('Member')->where("`member_id`={$id}")->find();
	//				$this->reward_reg($id,$info['pid']);  //本控制器内方法		
					//$this->tuijianreward($id);
                    $data['status'] = 1;
                    $data['info'] = "提交成功";
                    //加入推荐奖
                   // $this->tuijianreward($id);
                    $this->ajaxReturn($data);
//                    $this->redirect('Reg/regSuccess');
                }else{
                    $data['status'] = 0;
                    $data['info'] = '服务器繁忙,请稍后重试';
                    $this->ajaxReturn($data);
//                    $this->error('服务器繁忙,请稍后重试');
//                    return;
                }
            }
        }else{
			$login=$this->checkLogin();
		   if(!$login){
				$this->redirect('User/index');
				return;
		   }
		   if(session('STATUS')!=0){
				$this->redirect('User/index');
				return;
			}
            $addr=M("areas")->where("area_type=1")->select();
            //dump($addr);exit;
            $this->assign("addr",$addr);
            $this->display();
        }
    }
	
	/*********************************为上级ID进行奖励   GS 2016.12.27*******************/
	/*参数  $id是下级id  对下级进行奖励  $pid 为上级ID  若是存在则进行奖励 不存在则都不进行奖励*/
	function reward_reg($id,$pid){
		//给上级进行奖励
		
		if(!empty($pid)){  //只有存在上级的时候才会进行奖励
			$list_reward = M('Reward_conf')->Field('id,currency_id,money,day,type,status,sum')->select();
			
			
			
			foreach($list_reward as $k=>$v){
				//统计总量总和
				if($v['type'] == 1){
					$rer = M('Reward_reg')->Field('sum(money)')->where("currency_id={$v['currency_id']} AND down_id <> 0")->select();
				}else{
					$rer = M('Reward_reg')->Field('sum(money)')->where("currency_id={$v['currency_id']} AND down_id = 0")->select();
				}
				
				//若是总量超过总量则进行下一次循环处理
				if( ( $rer[0]['sum(money)'] + $v['money'] ) > $v['sum']){
					continue;
				}					
				
				
				if($v['type'] == 1){ //若是type等于1则是上级奖励
					if(!empty($v['money']) && !empty($v['day'])){
						$arr['member_id'] = $pid;
						$arr['currency_id'] = $v['currency_id'];
						$arr['money'] = $v['money'];
						$arr['surplus_day'] = $v['day'];
						$arr['sum_day'] = $v['day'];
						$arr['down_id'] = $id;
						$arr['add_time'] = time();
						$arr['status'] = 0;
						M('Reward_reg')->add($arr);
					}  
				}else{   //若是不等于1则是下级奖励
					if(!empty($v['money']) && !empty($v['day'])){
						$arr['member_id'] = $id;
						$arr['currency_id'] = $v['currency_id'];
						$arr['money'] = $v['money'];
						$arr['down_id'] = 0;
						$arr['add_time'] = time();
						$arr['surplus_day'] = $v['day'];
						$arr['sum_day'] = $v['day'];
						$arr['status'] = 0;
						M('Reward_reg')->add($arr);
					}
				}
				
			}
		}
	}
					
					
					
	/****************************************************************************************/
	
	
	
	
    /**
     * ajax验证昵称是否存在
     */
    public function ajaxCheckNick($nick){
        $nick = urldecode($nick);
        $data =array();
        $M_member = M('Member');
        $where['nick']  = $nick;
        $r = $M_member->field('member_id')->where($where)->find();
        if($r){
            $data['msg'] = "昵称已被占用";
            $data['status'] = 0;
        }else{
            $data['msg'] = "";
            $data['status'] = 1;
        }
        $this->ajaxReturn($data);
    }

   /**
     * ajax身份证验证
     * cuiwei 20171202
     */
    public function ajaxCheckIdcard($idcard){
        $idcard = urldecode($idcard);
        $data = array();
        $M_member = M('Member');
        $where['idcard'] = $idcard;
        $result = $M_member->field('member_id')->where($where)->find();
        if($result){
            $data['msg'] = "此身份证号已经注册";
            $data['status'] = 0;
        }
        else{
            $data['msg'] = "";
            $data['status'] = 1;
        }
        $this->ajaxReturn($data);
    }

    /**
     * ajax手机验证
     */
    function ajaxCheckPhone($phone) {
        $phone = urldecode($phone);
        $data = array();
        if(!checkMobile($phone)){
            $data['msg'] = "手机号不正确！";
            $data['status'] = 0;
        }else{
            $M_member = M('Member');
            $where['phone']  = $phone;
            $r = $M_member->field('member_id')->where($where)->find();
            if($r){
                $data['msg'] = "此手机已经绑定过！请更换手机号";
                $data['status'] = 0;
            }else{
                $data['msg'] = "";
                $data['status'] = 1;
            }
        }
        $this->ajaxReturn($data);
    }

    /**
     * ajax验证手机验证码
     */
    public function ajaxSandPhone(){
    	$A_Sms = new \SmsApi();
    	$phone = urldecode($_POST['phone']);
    	if(empty($phone)){
    		$data['status']=0;
    		$data['info'] = "参数错误";
    		$this->ajaxReturn($data);
    	}
    	if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)){
    		$data['status']=-1;
    		$data['info'] = "手机号码不正确";
    		$this->ajaxReturn($data);
    	}
    	$user_phone=M("Member")->field('phone')->where("phone='$phone'")->find();
    	if (!empty($user_phone)){
    		$data['status']=-2;
    		$data['info'] = "手机号码已经存在";
    		$this->ajaxReturn($data);
    	}
    	$ip = get_ip();
    	$v = S('ip_phone'.$ip);
    	$sss = S('limit_phone_ip');
    	if($v > 10){
    		$sss[$ip] = 1;
    		S('limit_phone_ip',$sss);
    	}
    	if($sss[$ip] == 1){
    		$data['status'] = -8;
    		$data['info'] = '此IP已经被禁止发送短信';
    		$this->ajaxReturn($data);
    	}
    	//发送短信
    	$res = $A_Sms ->send($phone);
    	if($res['status']!=1){
    		$time = S('ip_phone_time'.$ip);
    		if( $time == null || time() - $time > 60 ){
    			S('ip_phone_time'.$ip,time());
    			S('ip_phone'.$ip,1);
    		}else{
    			++$v;
    			S('ip_phone'.$ip,$v);
    			S('ip_phone_time'.$ip,time());
    		}
    		$data['status']=$res['status'];
    		$data['info'] = $res['info'];
    		$this->ajaxReturn($data);
    	}else{
    		$data['status']=$res['status'];
    		$data['info'] = $res['info'];
    		$this->ajaxReturn($data);
    	}
    }
    
    /**
     * 身份证照片认证页面
     */
    public function userAuthentication(){
        $login=$this->checkLogin();
        if(!$login){
            $this->redirect('User/index');
            return;
        }
        if(session('STATUS')==0){
            $this->redirect('ModifyMember/modify');
            return;
        }else if(session('STATUS')==5 && session('STATUS') != 2){
            	$this->redirect('Safe/index');
            	return;
           	}
	

        header("Content-type: text/html; charset=utf-8");
        $member_id = session('USER_KEY_ID');
        $M_member = M('Member');
        if(IS_POST){
            $upload = new upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     'userID/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
			// dump($upload);
			 //die;
            if(!$info['user_id_P'] || !$info['user_id_N'] || !$info['user_id_S']){
                // $a = $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$info['user_id_P']['savepath'].$info['user_id_P']['savename'];
                //dump($a);die();
                //如果上传不成功就删除
                if($info['user_id_P']){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$info['user_id_P']['savepath'].$info['user_id_P']['savename']);//图片绝对路径
                }
                if($info['user_id_N']){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$info['user_id_N']['savepath'].$info['user_id_N']['savename']);//图片绝对路径
                }
                if($info['user_id_S']){
                    unlink($_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$info['user_id_S']['savepath'].$info['user_id_S']['savename']);//图片绝对路径
                }
                $this->redirect('ModifyMember/userAuthentication','',1,"<script>alert('请上传证件照')</script>");
                die();
            }
            if(!$info) {// 上传错误提示错误信息
                $this->redirect('ModifyMember/userAuthentication','',1,"<script>alert('".$upload->getError()."')</script>");
                die();
            }else{
	    	Vendor('Aliyun.autoload');
	    	$aliyun_config = C('ALIYUN_CONFIG');//获取配置信息
	    	$accessKeyId = $aliyun_config['OSS_ACCESS_ID'];
	    	$accessKeySecret = $aliyun_config['OSS_ACCESS_KEY'];
	    	$endpoint = $aliyun_config['OSS_ENDPOINT'];
	    	$bucket = $aliyun_config['OSS_BUCKET'];
            $ossclient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);//创建实例
	   	$member_id = session('USER_KEY_ID'); //要保存的用户信息
		$prefix = 'user_id/';
		$object_1 = $prefix.$member_id."正面";
		$object_2 = $prefix.$member_id."反面";
		$object_3 = $prefix.$member_id."手持";
		$file_1 = $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$info['user_id_P']['savepath'].$info['user_id_P']['savename'];
		$file_2 = $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$info['user_id_P']['savepath'].$info['user_id_N']['savename'];
		$file_3 = $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$info['user_id_P']['savepath'].$info['user_id_S']['savename'];
		$options = array();
		try{
		    $ossclient->uploadFile($bucket,$object_1,$file_1,$options);
		    $ossclient->uploadFile($bucket,$object_2,$file_2,$options);
		    $ossclient->uploadFile($bucket,$object_3,$file_3,$options);
		    $data['user_id_P'] = 'https://'.$bucket.'.'.$endpoint.'/'.$object_1;
		    $data['user_id_N'] = 'https://'.$bucket.'.'.$endpoint.'/'.$object_2;
		    $data['user_id_S'] = 'https://'.$bucket.'.'.$endpoint.'/'.$object_3;
           	$data['uploaded_time'] = time();
           	$data['status'] = 3;	
            $userRes = $M_member->where(array('member_id'=>session('USER_KEY_ID')))->save($data);
		    if($userRes === false){
		    //上传成功，把本地文件删除
		    unlink($file_1);
		    unlink($file_2);
		    unlink($file_3);
                $this->redirect('ModifyMember/userAuthentication','',1,"<script>alert('提交失败!')</script>");
                die();
            }else{
		    //上传成功，把本地文件删除
		    unlink($file_1);
		    unlink($file_2);
		    unlink($file_3);
                session('STATUS',3);
                $this->redirect('Safe/index','',1,"<script>alert('提交成功!')</script>");
                die();
		    } 
		}catch(OssException $e) {
		    //上传失败,
		    //printf($e->getMessage() . "\n");die;
		    //把本地文件删除
		    unlink($file_1);
		    unlink($file_2);
		    unlink($file_3);
            $this->redirect('ModifyMember/userAuthentication','',1,"<script>alert('提交失败.')</script>");
			die();	
		  }

            }
            
        }
	$user_id_P = $this->config['idcard_1'];
	$user_id_N = $this->config['idcard_2'];
	$user_id_S = $this->config['idcard_3'];
	$this->assign('user_id_P',$user_id_P);
	$this->assign('user_id_N',$user_id_N);
	$this->assign('user_id_S',$user_id_S);
        $this->display();
    }
}
