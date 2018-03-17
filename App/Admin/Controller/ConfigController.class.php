<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
class ConfigController extends AdminController {
	public function _initialize(){
		parent::_initialize();
	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	
	public function index(){
		$list=M('Config')->select();
		foreach ($list as $k=>$v){
               $list[$v['key']]=$v['value'];
				
		}
		$this->assign('config',$list);
       	$this->display();
     }
     
     public function customerService(){
     	
     	$this->display();
     }
     
     public function shortMessage(){
     	$this->display();
     }
     public function finance(){
		
     	$this->display();
     }
     public function information(){
     	$this->display();
     }
     
     
     public function websiteBank(){
     	$this->display();
     }
     
     public function updateCofig(){
         if($_FILES["logo"]["tmp_name"]){
                $_POST['logo']=$this->upload($_FILES["logo"]);
                if (!$_POST['logo']){
                    $this->error('非法上传');
                }
         }
		 if($_FILES["index_logo_footer"]["tmp_name"]){
                $_POST['index_logo_footer']=$this->upload($_FILES["index_logo_footer"]);
                if (!$_POST['index_logo_footer']){
                    $this->error('非法上传');
                }
         }
		 if($_FILES["weixin_pay"]["tmp_name"]){
                $_POST['weixin_pay']=$this->upload($_FILES["weixin_pay"]);
                if (!$_POST['weixin_pay']){
                    $this->error('非法上传');
                }
         }
         if($_FILES["weixin"]["tmp_name"]){
              $_POST['weixin']=$this->upload($_FILES["weixin"]);
              if (!$_POST['weixin']){
                  $this->error('非法上传');
              }
         }
         if (!empty($_POST['friendship_tips'])){
     	      $_POST['friendship_tips'] = I('post.friendship_tips','','html_entity_decode');
         }
         if (!empty($_POST['huanxun_fee'])){
     	      $_POST['huanxun_fee'] = I('post.huanxun_fee','')/100;
         }
         if (!empty($_POST['withdraw_warning'])){
        	$_POST['withdraw_warning'] = I('post.withdraw_warning','','html_entity_decode');
         }
         if (!empty($_POST['risk_warning'])){
             $_POST['risk_warning'] = I('post.risk_warning','','html_entity_decode');
         }
         if (!empty($_POST['VAP_rule'])){
             $_POST['VAP_rule'] = I('post.VAP_rule','','html_entity_decode');
         }
         if (!empty($_POST['disclaimer'])){
         	$_POST['disclaimer'] = I('post.disclaimer','','html_entity_decode');
         }
         if (!empty($_POST['list_reward_rule'])){
         	$_POST['list_reward_rule'] = I('post.list_reward_rule','','html_entity_decode');
         }
         if (!empty($_POST['FWTK'])){
         	$_POST['FWTK'] = I('post.FWTK','','html_entity_decode');
         }
         if (!empty($_POST['new_coin_up'])){
         	$_POST['new_coin_up'] = I('post.new_coin_up','','html_entity_decode');
         }
		 if (!empty($_POST['list_reward_rule'])){
         	$_POST['list_reward_rule'] = I('post.list_reward_rule','','html_entity_decode');
         }
		 if (!empty($_POST['wenxin_tishi'])){
         	$_POST['wenxin_tishi'] = I('post.wenxin_tishi','','html_entity_decode');
         }
		 
		 if (!empty($_POST['souyexiaoxi'])){
         	$_POST['souyexiaoxi'] = I('post.souyexiaoxi','','html_entity_decode');
         }
         if (!empty($_POST['record'])){
         	$_POST['record'] = I('post.record','','html_entity_decode');
         }
         if (!empty($_POST['qq1'])){
         	$_POST['qq1'] = I('post.qq1','','html_entity_decode');
         }
         if (!empty($_POST['email'])){
         	$_POST['email'] = I('post.email','','html_entity_decode');
         }
         if (!empty($_POST['business_email'])){
         	$_POST['business_email'] = I('post.business_email','','html_entity_decode');
         }
         if (!empty($_POST['suggest_email'])){
         	$_POST['suggest_email'] = I('post.suggest_email','','html_entity_decode');
         }
		 if (!empty($_POST['daifee'])){
         	$_POST['daifee'] = I('post.daifee','','html_entity_decode');
         }
		 if (!empty($_POST['daicoin'])){
         	$_POST['daicoin'] = I('post.daicoin','','html_entity_decode');
         }
	if (!empty($_POST[micromsgname])){
	    $_POST['micromsgname'] = I('post.micromsgname','','html_entity_decode');
	}
	if (!empty($_POST['qqkefuname'])){
	    $_POST['qqkefuname'] = I('post.qqkefuname','','html_entity_decode');
	}
//增加身份证照片示例照片设置项目    cuiwei 20180110
         if($_FILES["idcard_1"]["tmp_name"]){
                $_POST['idcard_1']=$this->upload($_FILES["idcard_1"]);
                if (!$_POST['idcard_1']){
                    $this->error('非法上传');
                }
         } 
         if($_FILES["idcard_2"]["tmp_name"]){
                $_POST['idcard_2']=$this->upload($_FILES["idcard_2"]);
                if (!$_POST['idcard_2']){
                    $this->error('非法上传');
                }
         }
         if($_FILES["idcard_3"]["tmp_name"]){
                $_POST['idcard_3']=$this->upload($_FILES["idcard_3"]);
                if (!$_POST['idcard_3']){
                    $this->error('非法上传');
                }
         }


     	foreach ($_POST as $k=>$v){
     		$rs[]=M('Config')->where(C("DB_PREFIX")."config.key='{$k}'")->setField('value',$v);
     	}
     	if($rs){
			S('config',null);
     		$this->success('配置修改成功');
     	}else{
     		$this->error('配置修改失败');
     	}
     }
}
