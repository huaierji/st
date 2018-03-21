<?php 
namespace Home\Controller;
use Home\Controller\HomeController;
use Think\Page;
use Think\Upload;

class UserController extends HomeController {
    //空操作
    
    public function _initialize(){
        parent::_initialize();
    }
    public function _empty(){
    	
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }
    public function index(){
        $where['member_id']=$_SESSION['USER_KEY_ID'];
        $currency_user=M('Currency_user')
            ->field(''.C('DB_PREFIX').'currency_user.*,('.C('DB_PREFIX').'currency_user.num+'.C('DB_PREFIX').'currency_user.forzen_num) as count,'.C('DB_PREFIX').'currency.currency_name,'.C('DB_PREFIX').'currency.currency_mark')
            ->join("left join ".C('DB_PREFIX')."currency on ".C('DB_PREFIX')."currency.currency_id=".C('DB_PREFIX')."currency_user.currency_id")
            ->where($where)->order('sort')->select();
    //    $allmoneys = null;
        foreach ($currency_user as $k=>$v){
            $Currency_message=$this->getCurrencyMessageById($v['currency_id']);
            $allmoney=$currency_user[$k]['count']*$Currency_message['new_price'];
       //     $allmoneys+=$allmoney;
        }
        $member_rmb=$this->member;
       // $allmoneys=$allmoneys+$member_rmb['count'];
		
        $u_info = $this->member;
        $this->assign('u_info',$u_info);
//         $this->assign('allmoneys',$allmoneys);
        $this->assign('currency_user',$currency_user);
        $this->display();
    }

    /**
     * 修改会员信息
     */
    public function updateMassage(){
		
        header("Content-type: text/html; charset=utf-8");
        $member_id = session('USER_KEY_ID');
        $M_member = M('Member');
        $list = $this->member;
        $list['area_name_city'] = M("Areas")->where(array('area_id'=>$list['city']))->find()['area_name'];
        $list['area_name_province'] = M("Areas")->where(array('area_id'=>$list['province']))->find()['area_name'];
        if(IS_POST){
			//dump($_POST);die;
			//echo 123;die;
            $member_id = I('post.member_id','','intval');
            $data['nick'] = I('post.nick');
            $data['province'] = I('post.province','','intval');
            $data['city'] = intval(I('city'));
            $data['job'] = I('post.job');
            $data['head'] = I('post.head');
            $data['profile'] =I('post.profile','','html_entity_decode');
            if($data['nick']!=$list['nick']){
                $where = null;
                $where['member_id']  = array('NEQ',$member_id);
                $where['nick'] = $data['nick'];
                if($M_member->field('nick')->where($where)->select()){
                    $data['status'] = 2;
                    $data['info'] = '昵称重复';
                    $this->ajaxReturn($data);
                }
            }
			//echo 123;die;
            if(empty($data['province'])){
                $data['status'] = 2;
                $data['info'] = '请填写所在省份';
                $this->ajaxReturn($data);
            }
            if(empty($data['city'])){
                $data['status'] = 2;
                $data['info'] = '请填写所在城市';
                $this->ajaxReturn($data);
            }
            $r = $M_member->where(array('member_id'=>$member_id))->save($data);
            if($r===false){
                $data['status'] = 2;
                $data['info'] = '服务器繁忙,请稍后重试';
                $this->ajaxReturn($data);
            }
			//echo 123;
            $arr['status'] = 1;
            $arr['info'] = '修改成功';
            $this->ajaxReturn($arr);
			
        }else{

            $areas = M("Areas")->where('area_type = 1')->select();
            $this->assign('areas',$areas);
            $this->assign('list',$list);
            $this->display('update_massage');
        }
    }
	
    /**
     * 修改账号密码
     */
    public function updatePassword(){
         header("Content-type: text/html; charset=utf-8");
        if(IS_POST){
            $oldPwd = I('post.oldpwd','','md5');
            $newPwd = I('post.pwd','','md5');
            $rePwd = I('post.repwd','','md5');
            $M_member = D('Member');
            if(!$M_member->checkPwd($_POST['oldpwd']) || !$M_member->checkPwd($_POST['pwd']) || !$M_member->checkPwd($_POST['repwd']) ){
                $data['status']=2;
                $data['info']='请输入6-20位密码';
                $this->ajaxReturn($data);
            }
            if($rePwd!=$newPwd){
                $data['status']=2;
                $data['info']='两次输入的密码不一致';
                $this->ajaxReturn($data);
            }
            $r = $M_member->where(array('member_id'=>session('USER_KEY_ID'),'pwd'=>$oldPwd))->find();
            if(!$r){
                $data['status']=2;
                $data['info']='原始密码输入错误';
                $this->ajaxReturn($data);
            }

            //修改密码不能与交易密码相同
            $arr['member_id'] = session('USER_KEY_ID');
            $dt = $M_member -> where($arr) -> find();
            if( $newPwd === $dt['pwdtrade'] ){
                $data['status']= 2;
                $data['info'] = '登录密码不能与支付密码一致';
                $this->ajaxReturn($data);
            }

            if($newPwd===$oldPwd){
                $data['status']=2;
                $data['info']='新密码不能和密码一样';
                $this->ajaxReturn($data);
            }

            $data['pwd'] = $newPwd;
            $s = $M_member->where(array('member_id'=>session('USER_KEY_ID')))->save($data);
            if(!$r){
                $data['status']=2;
                $data['info']='服务器繁忙请稍后重试';
                $this->ajaxReturn($data);
            }
            $data['status']=1;
            $data['info']='修改成功..请重新登录';
            session_destroy();
            $this->ajaxReturn($data);
        }else{
            // $this->User_status();
            $this->display('update_password');
        }
    }

    /**
     * 币种与人民币兑换
     * @param number $rmb 人民币数量
     * @param number $bili 兑换比例
     * @param unknown $currency_id 兑换币种ID
     */
    public function rmbChangeCurrency($rmb=0,$bili=1,$currency_id){
    	$r[]=M('Member')->where('member_id='.$_SESSION['USER_KEY_ID'])->setDec('rmb',$rmb);
    	$r[]=M('Currency_user')->where('member_id='.$_SESSION['USER_KEY_ID'].' and currency_id='.$currency_id)->setInc('num',$rmb/$bili);
		if(!empty($r)){
			$this->success('兑换成功');
		}else{
			$this->error('兑换失败');
		}
    }
    /**
     * 修改支付密码
     */
    public function updatePwdTrade(){
        if(IS_POST){
            //用户id
            $member_id = session('USER_KEY_ID');
            //dump($member_id);die;
            //获取用户原来信息
            $info = $this->member; 
            //用户原密码          
            $data['pwd'] = I('post.oldpwd_b');
            $oldpwdtrade = I('post.oldpwdtrade_b');
            //用户新交易密码
            $data['pwdtrade'] = I('post.pwdtrade');
            //用户第二次输入交易密码
            $repwdtrade = I('post.repwdtrade');
            if(!checkPwd($data['pwd'])){
            	$rdata['status']= -1;
            	$rdata['info'] = '密码输入位数不正确';
                $this->ajaxReturn($rdata);
            }          
            if(!checkPwd($data['pwdtrade'])){
            	$rdata['status']= -3;
            	$rdata['info'] = '新密码输入位数不正确';
            	$this->ajaxReturn($rdata);
            }
            if($data['pwdtrade'] != $repwdtrade){
            	$rdata['status']= -4;
            	$rdata['info'] = '两次支付密码输入不一致';
            	$this->ajaxReturn($rdata);
            }
            if($info['pwd'] != md5($data['pwd'])){
            	$rdata['status']= -4;
            	$rdata['info'] = '密码输入错误';
            	$this->ajaxReturn($rdata);
            }
            //获取用户手机号码
            $phone = $info['phone'];
            //验证码
            $code  = $_POST['code'];
            //实例化短息类
            $A_Sms = new \SmsApi();
            //验证验证码
            $re = $A_Sms->checkAppCode($phone, $code);
            if(!$re){
                $data['status']=2;
                $data['info']="验证码输入错误";
                $this->ajaxReturn($data);
            }
            $A_Sms->deleteSendSmsApp($phone);
            if($info['pwd'] == md5($data['pwdtrade'])){
            	$rdata['status']= -4;
            	$rdata['info'] = '支付密码不能与登录密码一致';
            	$this->ajaxReturn($rdata);
            }
            $r = M('Member')->where(array('member_id'=>$member_id))->setField('pwdtrade',md5($data['pwdtrade']));
            if(!$r){
            	$rdata['status']= -5;
            	$rdata['info'] = '服务器繁忙,请稍后重试';
            	$this->ajaxReturn($rdata);
            }else{
	            $rdata['status']= 1;
	            $rdata['info'] = '修改成功';
	            $this->ajaxReturn($rdata);
            }
            
            
//             $upload = new Upload();// 实例化上传类
//             $upload->maxSize   =     3145728  ;// 设置附件上传大小
//             $upload->exts      =     array('jpg', 'gif', 'png');// 设置附件上传类型
//             $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
//             $upload->savePath  =     'User/Authentication/'; // 设置附件上传（子）目录
//             $upload->saveName  =     array('getRandom','15');

//             // 上传文件
//             $info   =   $upload->upload();
//             if(!$info) {// 上传错误提示错误信息
//                 $this->error($upload->getError());
//                 return;
//             }
//             if(empty($info['pic_1'])){
//                 $this->error('图片1'.$upload->getError());
//                 return;
//             }
//             if(empty($info['pic_2'])){
//                 $this->error('图片2'.$upload->getError());
//                 return;
//             }
//             if(empty($info['pic_3'])){
//                 $this->error('图片3'.$upload->getError());
//                 return;
//             }
//             $idcardPositive = ltrim($upload->rootPath.$info['pic_1']["savepath"].$info['pic_1']["savename"],'.');
//             $idcardSide = ltrim($upload->rootPath.$info['pic_2']["savepath"].$info['pic_2']["savename"],'.');
//             $idcardHold = ltrim($upload->rootPath.$info['pic_3']["savepath"].$info['pic_3']["savename"],'.');
//             $data['pwdtrade'] = I('post.pwdtrade','','md5');
//             $data['idcardPositive'] = $idcardPositive;//判断后赋值
//             $data['idcardSide'] = $idcardSide;//判断后赋值
//             $data['idcardHold'] = $idcardHold;

//             $r = M('Examine_pwdtrade')->add($data);
//             if($r ===false){
//                 $this->error('服务器繁忙,请稍后重试');
//             }
            $this->success('申请成功,审核后会以系统通知通知您',U('User/index'));
        }
    }
    /**
     * 邀请好友
     */
    public function invit(){
        if(IS_POST){
            $emails = I('post.emails');
            dump($email);die;
            $list = explode(";",$emails);
            $arr = array();
            if($list){
                foreach ($list as $k=>$vo) {
                    if(M('Member')->where(array('email'=>$vo))->find()){
                        $data['status'] = 0;
                        $data['info'] = "您输入的".$vo."用户名已经注册";
                        $this->ajaxReturn($data);
                    }else{
                        $arr[] = $vo;
                    }
                }
                foreach ($arr as $vo) {
                    $url = "http://".$_SERVER['SERVER_NAME'].U('Reg/Reg',array('Member_id'=>session('USER_KEY_ID')));
                    $content = "<div>";
                    $content.= "您好，<br><br>请点击链接：<br>";
                    $content.= "<a target='_blank' href='{$url}' >完成注册邀请</a>";
                    $content.= "<br><br>如果链接无法点击，请复制并打开以下网址：<br>";
                    $content.= "<a target='_blank' href='{$url}' >{$url}</a>";
                    if(setPostEmail($this->config['EMAIL_HOST'],$this->config['EMAIL_USERNAME'],$this->config['EMAIL_PASSWORD'],$this->config['name'].'团队',$vo,$this->config['name'].'团队[注册邀请]',$content)){
                        $data['status']=0;
                        $data['info']="用户名".$vo."发送失败";
                        $this->ajaxReturn($data);
                    }
                }
                $data['status']=1;
                $data['info']="发送成功";
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 0;
                $data['info'] = "请输入发送用户名";
                $this->ajaxReturn($data);
            }
        }
        //我的邀请
//         $my_invit = M('Member')->field('email,status,reg_time')->where(array('pid'=>session('USER_KEY_ID')))->select();

		//邀请获得总金额
		$myid = session('USER_KEY_ID');
		$list_d = M('Reward_reg')->Field('currency_id,money,surplus_day,sum_day')->where("down_id <> 0 AND member_id={$myid}")->select(); //新人被推荐奖励
		$currency_list = array();
		foreach($list_d as $k=>$v){
			if(array_key_exists($v['currency_id'],$currency_list)){
				$currency_list[$v['currency_id']]['ok'] += sprintf('%.2f',(1 - $v['surplus_day']/$v['sum_day'] ) * $v['money']);
				$currency_list[$v['currency_id']]['will'] += sprintf('%.2f',$v['money']);
			}else{
				$name = M('Currency')->Field('currency_name')->where("`currency_id`={$v['currency_id']}")->find();
				$currency_list[$v['currency_id']]['name'] = $name['currency_name'];
				$currency_list[$v['currency_id']]['ok'] = sprintf('%.2f',(1 - $v['surplus_day']/$v['sum_day'] ) * $v['money']);
				$currency_list[$v['currency_id']]['will'] = sprintf('%.2f',$v['money']);
			}
		}
       
	   
	  /* $count = M('Reward_reg')->Field('id')->where("down_id <> 0 AND member_id={$myid}")->count();//根据分类查找数据数量
        $page = new \Think\Page($count,5);//实例化分页类，传入总记录数和每页显示数
        $show = $page->show();//分页显示输出性
        $my_invit = M('Reward_reg')->Field('surplus_day,down_id,currency_id,sum_day,money')->where("down_id <> 0 AND member_id={$myid}")->limit($page->firstRow.','.$page->listRows)->select();//时间降序排列，越接近当前时间越高
        //dump($my_invit);die;
        foreach ($my_invit as $k=>$vo) {
        	$name = M('Member')->Field('email,reg_time')->where("member_id={$vo['down_id']}")->find();
			//dump($name);
			$my_invit[$k]['email'] = $this->email_format($name['email']);
			$my_invit[$k]['reg_time'] = date("Y-m-d H:i:s",$name['reg_time']);
			$currency_name = M('Currency')->Field('currency_name')->where("`currency_id`={$vo['currency_id']}")->find();
			$my_invit[$k]['currency_name'] = $currency_name['currency_name'];
			$my_invit[$k]['ok'] = sprintf('%.2f',(1 - $vo['surplus_day']/$vo['sum_day'] ) * $vo['money']);
        }
        $this->assign('page',$show);
        $this->assign('my_invit',$my_invit);*/   
	   
	   
        $count = M('Member')->where(array('pid'=>session('USER_KEY_ID')))->count();//根据分类查找数据数量
        $page = new \Think\Page($count,5);//实例化分页类，传入总记录数和每页显示数
        $show = $page->show();//分页显示输出性
        $my_invit = M('Member')
                  ->field('email,status,reg_time,phone')
                  ->where(array('pid'=>session('USER_KEY_ID')))
                  ->limit($page->firstRow.','.$page->listRows)
                  ->select();//时间降序排列，越接近当前时间越高
        foreach ($my_invit as $k=>$vo) {
        	$my_invit[$k]['status_name'] = $vo['status']?"已填写个人信息":"未填写个人信息";
			$my_invit[$k]['reg_time'] = date("Y-m-d H:i:s",$vo['reg_time']);
            $my_invit[$k]['phone'] = $vo['phone']?$vo['phone']:未填写;
        }       
        $this->assign('page',$show);
        $this->assign('my_invit',$my_invit);
        $info = M('Article')->Field('title,article_id,content')->where('position_id = 121')->find();
        $info1 = M('Article')->Field('title,article_id,content')->where('position_id = 122')->find();
        $info['title']=html_entity_decode($info['title']);
        $info['content']=html_entity_decode($info['content']);
        $info1['title']=html_entity_decode($info1['title']);
        $info1['content']=html_entity_decode($info1['content']);
        $this->assign('info',$info);
        $this->assign('info1',$info1);			       
		$count_reward =  M('Member')->Field('member_id')->where(array('pid'=>session('USER_KEY_ID'),'status'=>1))->count();//根据分类查找数据数量        
		$currency_list_d = M('Currency')->Field('currency_id,currency_name')->select();
		foreach($currency_list_d as $k => $v){			
			$count_fee= M('Finance_'.$this->table_f)->Field('money')->where("member_id={$_SESSION['USER_KEY_ID']} and type=24 AND `currency_id`={$v['currency_id']}")->sum('money');
			$currency_fee[$k] = $v;
			$currency_fee[$k]['money'] = sprintf("%.2f", $count_fee);		
			unset($count_fee);
		}
		$counta = $this->daishu(session('USER_KEY_ID'),20);
		$count = count($counta);		
		$this->assign('currency_list',$currency_list);
        $this->assign('currency_fee',$currency_fee);
        $this->assign('count',$count);
        $this->assign('count_reward',$count_reward);
        $this->display();
    }


	//查询团队人数
	protected function daishu($items,$lv){
		for($i=0;$i<$lv;$i++){
			$where['pid']=array("in",$items);
			$user=M("Member")->where($where)->select();
			if(empty($user)){
				continue;
			}
			foreach ($user as $key=>$vo){
				$name[] =  (string)($vo['member_id']);
			}
			$r = $items = $name;
		}
		return array_unique($r);
	}
	

	/**
	*格式化隐藏用户名信息
	*/
	public function email_format($email){
		return substr($email,0,-2).'**';
		
	}
    /**
     * 系统消息
     */
    public function sysMassage(){
    	if($_GET['type']=='all'){
    		$this->redirect("User/sysMassage");
    	}/*
    	if($_GET['type']=='xtxx'){
    		$where['type'] = 4;
    	}
    	if($_GET['type']=='grxx'){
    		$where['type'] = -2;
    	}*/
        $member_id = session('USER_KEY_ID');
        $M_member = M('Message');
		$where['type'] = array('neq',-2);
        $where['member_id'] = $member_id;
        $count      = $M_member->where($where)->count();// 查询满足要求的总记录数
        $Page       = new Page($count,9);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $M_member
            ->alias('a')
            ->field('a.message_id,a.message_all_id,a.title,a.type,a.add_time,a.status,b.name type_name')
            ->where($where)
            ->join(''.C('DB_PREFIX').'message_category as b on a.type = b.id')
            ->order(" a.status asc, a.add_time desc")
            ->limit($Page->firstRow.','.$Page->listRows)->select();
        //查询消息类型
        //$this->assign('count',$count);
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('system_massage');
    }
    /**
     *显示详细系统消息界面
     */
    public function showSysTem(){
        $message_id = I('message_id','','intval');
        $message_all_id = I('message_all_id','','intval');
        $member_Id = session('USER_KEY_ID');
        $where['member_id'] = $member_Id;
        $where['message_id'] = $message_id;
        $where['message_all_id'] = $message_all_id;
        $list = M('Message')
            ->alias('a')
            ->field('a.message_id,a.title,a.type,a.add_time,a.content,b.name type_name')
            ->where($where)
            ->join(C('DB_PREFIX').'message_category as b on a.type = b.id')
            ->find();
        //判断状态为0则是 未读 执行语句否则不执行标为已读
        if($list['status']==0){
            $status = M('Message')->where($where)->save(array('status'=>1));
            if($status===false){
                $this->error('服务器繁忙请稍后重试');
            }
        }
        if($list==false){
            header("HTTP/1.0 404 Not Found");
            $this->display('Public:404');
            return;
        }
        //右侧部分
        $where = null;
        $where['member_id'] = $member_Id;
        $right = M('Message')
            ->alias('a')
            ->field('a.message_id,a.message_all_id,a.title,a.type,a.add_time,a.content,b.name type_name')
            ->where($where)
            ->join(C('DB_PREFIX').'message_category as b on a.type = b.id')
            ->order(' a.add_time desc ')
            ->limit(4)
            ->select();
//下一页
//        $after=M('Message')->where("message_id > ".$message_id. " and member_id = ".$member_Id)->order('message_id desc')->limit('1')->find();
//        $this->assign('after',$after);
        $this->assign('list',$list);
        $this->assign('right',$right);
        $this->display();
    }

    /**
     * 添加提现银行信息
     * post
     * return ajax
     */
    public function insert(){
        $bank = M('Bank');
        $member = M('Member');
        $area = M('Areas');

        $where['uid'] = session('USER_KEY_ID');
        //判断post是否为空
        if(IS_POST){
            $info['bname']=I("new_label");
            $info['address']=I("shi");
            $info['cardnum']=I("account");
            $info['bankname'] = I("bank");
            $info['cardname'] = $this->auth['name'];
            $info['uid'] = session('USER_KEY_ID');
			if(empty($info['bname'])){
                $data['status']  = 0;
                $data['info'] = '请填写标签';
                $this->ajaxReturn($data);
            }
            if(empty($info['bankname'])){
                $data['status']  = 2;
                $data['info'] = '请选择银行';
                $this->ajaxReturn($data);
            }
            if(empty($info['address'])){
                $data['status']  = 3;
                $data['info'] = '请选择开户地址';
                $this->ajaxReturn($data);
            }
            if(empty($info['cardnum'])){
                $data['status']  = 4;
                $data['info'] = '请输入银行卡号';
                $this->ajaxReturn($data);
            }
            /*if(16 > strlen(I("account")) || strlen(I("account")) > 19){
            	
                $data['status']  = 5;
                $data['info'] = '请输入有效银行卡号';
                $this->ajaxReturn($data);
            }*/
            $re = $bank->add($info);
            if($re>0){
                $data['status']  = 1;
                $data['info'] = '操作成功';
                $this->ajaxReturn($data);
            }else{
                $data['status']  = 6;
                $data['info'] = '服务器繁忙,请稍后重试';
                $this->ajaxReturn($data);
            }
        }
    }


	/**
	 *  提现显示信息及添加信息
	 */
    public function draw(){
        //dump(C('DRAW'));die;
        if (C('DRAW') === false) {
            $this -> error ("提现功能已关闭");
        }else{
    
        $bank = M('Bank');
        $member = M('Member');
        $article = M('article');
        $area = M('Areas');
        $withdraw =M('Withdraw');

        $where['uid'] = session('USER_KEY_ID');
        $where[C('DB_PREFIX').'bank.status'] = 0;
        //提示文章显示
		if(S('art_content_tishi_user')){
			$art['content'] = S('art_content_tishi_user');
		}else{
			$art['content'] = html_entity_decode($article->Field('content')->where(C('DB_PREFIX').'article.position_id = 120')->find()['content']);
			S('art_content_tishi_user',$art['content']);
		}
        //查找省份
        $province = $area->where('parent_id = 1')->select();
        //查找当前登录人的提现地址
        $field=C('DB_PREFIX')."bank.*,b.area_name as barea_name,a.area_name as aarea_name";
        $bank_info = $bank->field($field)->join(C('DB_PREFIX')."areas as b ON b.area_id =".C('DB_PREFIX')."bank.address")
            ->join(C('DB_PREFIX')."areas as a ON a.area_id = b.parent_id ")->where($where)->select();
        //检测是否有10个地址
        $count = $bank->where($where)->count();
        if($count<10){
            $this->assign("num",1);
        }else{
            $this->assign("num",2);
        }
        //显示提现记录
        $draw_info = $withdraw
            ->field(C('DB_PREFIX')."withdraw.withdraw_id, ".C('DB_PREFIX')."bank.cardnum, ".C('DB_PREFIX')."withdraw.all_money, ".C('DB_PREFIX')."withdraw.money, ".C('DB_PREFIX')."withdraw.add_time, ".C('DB_PREFIX')."withdraw.status")
            ->join(C('DB_PREFIX')."bank ON ". C('DB_PREFIX')."withdraw.bank_id =".C('DB_PREFIX')."bank.id")
            ->where(C('DB_PREFIX')."withdraw.uid ={$_SESSION['USER_KEY_ID']}")
            ->order(C('DB_PREFIX')."withdraw.add_time desc")
            ->limit(10)
            ->select();
        //显示可用余额
        $rmb = $member->field('rmb')->where("member_id ={$_SESSION['USER_KEY_ID']}")->find();
        
        $this->assign('rmb',$rmb);
        $this->assign('draw_info',$draw_info);
        $this->assign('bank_info',$bank_info);
        $this->assign('auth', $this->auth['name']);//传递真实姓名
        $this->assign('areas',$province);
        $this->assign('art',$art);
        $this->display();
    }
    }
    /**
     * 删除提现地址
     */
    public function delete(){
        $bank = M('Bank');
        $id=intval(I('post.id'));
        $re = $bank->where("`id`={$id}")->setField('status',-1);

        if($re){
            $arr['status']=1;
            $arr['info']="操作成功";
            $this->ajaxReturn($arr);
        }else{
            $arr['status']=0;
            $arr['info']="服务器繁忙,请稍后重试";
            $this->ajaxReturn($arr);
        }
    }

    /**
     * 提现金额
     */
    public function withdraw(){
        $withdraw = M('Withdraw');
        $member = M('Member');
        $da['key']="fee";
        //查询手续率所在表
        $list=M("Config")->where($da)->find();
        //查找member_id对应的交易密码
        $where['member_id'] = session('USER_KEY_ID');
        //查找member表uid对应信息（交易密码，可以金额，冻结金额）
        $mem_data = $member->field('pwdtrade,rmb,forzen_rmb,phone')->where($where)->find();
        //交易密码
        if(IS_POST){
            $data['bank_id'] = I('post.select_bank');
            $data['all_money'] = floatval(I('post.money'));//提现金额
            $data['pwdtrade'] = md5(I('post.pwdtrade'));
            $code = $_POST['code'];
            $phone = $mem_data['phone'];
            $A_Sms = new \SmsApi();
            $re = $A_Sms->checkAppCode($phone, $code);
           	if(!$re){
           		$info['status']=11;
           		$info['info']='验证码不正确';
           		$this->ajaxReturn($info);
           	}
           	
           	$A_Sms->deleteSendSmsApp($phone);
           	
           	
            if(empty($data['all_money'])){
                $info['status'] = 0;
                $info['info'] = "请填写提现金额";
                $this->ajaxReturn($info);
            }
            //单笔在100至50000在之间
            if($data['all_money']<100||$data['all_money']>50000){
                $info['status'] = 2;
                $info['info'] = "提现金额超出限制";
                $this->ajaxReturn($info);
            }
            //单日是否超出50W限制
            $res = $this->maxwithdeaw_oneday(floatval(I('post.money')));
            if($res == false){
                $info['status'] = 3;
                $info['info'] = "本次提现金额超出单日提现金额最大金额";
                $this->ajaxReturn($info);
            }
            //验证密码
            if(empty($data['pwdtrade'])){
                $info['status'] = 4;
                $info['info'] = "请填写交易密码";
                $this->ajaxReturn($info);
            }
            if($data['pwdtrade'] != $mem_data['pwdtrade']){
                $info['status'] = 5;
                $info['info'] = "交易密码填写错误";
                $this->ajaxReturn($info);
           }
           
            //验证是否选取地址
            if(empty($data['bank_id'])){
                $info['status'] = 6;
                $info['info'] = "请选择提现地址";
                $this->ajaxReturn($info);
            }
            
            if($data['all_money']>$mem_data['rmb']){
            	$info['status'] = 6;
            	$info['info'] = "账户余额不足";
            	$this->ajaxReturn($info);
            }

            if($mem_data['rmb']<100){
                $info['status'] = 7;
                $info['info'] = "现金少于100，不能提现";
                $this->ajaxReturn($info);
            }
            //应付手续费
            $data['withdraw_fee'] = floatval(I('post.money'))*	$list['value']*0.01;
            //实际金额
            $data['money'] = floatval(I('post.money')) - $data['withdraw_fee'];
            //加时间
            $data['add_time'] = time();
            //加订单号
            $data['order_num'] = session('USER_KEY_ID')."-".$data['add_time'];
            //加uid辨明身份
            $data['uid'] = session('USER_KEY_ID');
            //保存可用金额修改信息
            $data_mem['rmb'] = $mem_data['rmb'] - floatval(I('post.money'));
			//保存冻结金额的修改信息
            $data_mem['forzen_rmb'] = $mem_data['forzen_rmb'] + floatval(I('post.money'));
            
            $res = $member->where($where)->save($data_mem);

            $re = $withdraw->add($data);

            if($re){
                if($res){
                    $info['status'] = 1;
                    $info['info'] = "提现成功，24小时内到账 ";
                    $this->ajaxReturn($info);
                }else{
                    $info['status'] = 8;
                    $info['info'] = "服务器繁忙,请稍后重试";
                    $this->ajaxReturn($info);
                }
            }else{
                $info['status'] = 9;
                $info['info'] = "服务器繁忙,请稍后重试";
                $this->ajaxReturn($info);
            }
        }
    }
    /**
     * 限制单日最多提现50万
     * @param float $num
     * @return boolean
     */
    private function maxwithdeaw_oneday($num){
        //单日0时0分
        $time = strtotime(date('Y-M-d',time()));
        //从0时0分到当前时间
        $where['add_time']=array("between",array($time,time()));

        $where['uid'] = $_SESSION['USER_KEY_ID'];//
        //总钱数
        $money = M('Withdraw')->where($where)->sum('all_money');
// 		dump($money);
// 		die;
        //之前的总提现数是否超过了500000
        if($money>=500000){
            return false;
        }
        //本次提现是否会超出500000
        $money_now = $money+$num;
        if($money_now>=500000){
            return false;
        }
        return true;
    }
    
	public function chexiaoByid(){
		$withdraw = M('Withdraw');
		$member = M('Member');
		$id = I("post.id");
		if(empty($id)){
			$data['status'] = 0;
			$data['info'] = "参数错误";
			$this->ajaxReturn($data);
		}
		//查询出对应id的提现金额,对应会员的会员id
		$money = $withdraw->field('uid,all_money')->where("withdraw_id = {$id}")->find();
		//对应会员的可用金额和冻结金额
		$rmb = $member->field('rmb,forzen_rmb')->where("member_id = {$money['uid']}")->find();
		//加回可用金额
		$money_back['rmb'] = $rmb['rmb'] + $money['all_money'];
		//减去冻结金额
		$money_back['forzen_rmb'] = $rmb['forzen_rmb'] - $money['all_money'];
		//修改数据库
		$re = $member->where("member_id = {$money['uid']}")->save($money_back);
		if(!$re){
			$data['status'] = 2;
			$data['info'] = "撤销失败";
			$this->ajaxReturn($data);
		}
		$res = $withdraw->where("withdraw_id = {$id}")->delete();
		if(!$res){
			$data['status'] = 3;
			$data['info'] = "撤销失败";
			$this->ajaxReturn($data);
		}
			$data['status'] = 1;
			$data['info'] = "撤销成功";
			$this->ajaxReturn($data);
	}
    
    
    /**
     * 充值
     */
    public function pay(){
        $config=$this->config;
        $member=$this->member;

        $order_num=$this->getPaycountByName($member['name']);
        //随机数
        //$num = 0.01*rand(10,99);+0.01*$order_num+$num
        $fee=floatval($config['pay_fee']);
        //dump($config['pay_fee']);die;              
        //支付表
        $where['member_name']=$member['name'];
        $where['member_id']=$member['member_id'];
        $where['type'] = array('NEQ',3);
        //分页
		// dump($where);die;
        $pay = M('Pay'); // 实例化User对象
        $count = $pay->where($where)->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        $list=$pay->where($where)->order('pay_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        // foreach ($list as $k=>$v){
        //     $list[$k]['status']=payStatus($v['status']);
        // }
        // dump($list);die;
        $bank = M('Website_bank')->where('status = 1')->select();
        $bank = M('Website_bank')->order('status asc')->select();
        //充值说明
		// dump($bank);die;
        $art=M('Article')->where('article_id=102')->find();
        $art['content']=html_entity_decode($art['content']);
		// dump($art);die;
        $this->assign('art',$art);
        $this->assign('page',$show);
        $this->assign('bank',$bank);
        $this->assign('list',$list);
        $this->assign('fee',$fee);
        $this->assign('member',$member);
        $this->display();
    }

    public  function alipay(){
    	if($_GET){
            //用户充值金额
        	$money  = $_GET['money'];
            //用户支付id
        	$pay_id = $_GET['pay_id'];	
            //主题   用户支付id最后6位
        	$title  = substr($pay_id,-6);
            //bank_id           8 
            //bank_name         潇云居
            //bank_address      支付宝地址
            //bank_no           支付宝账号
            //status             1
            $bank   = M('Website_bank')->where('status = 1')->select();
            //dump($bank);die;
            //dump($pay_id);die;
            //dump($title);die;
        	$this->assign('bank',$bank);
        	$this->assign('title',$title);
        	$this->assign('money',$money);	
        	$this->display();
	   }
    }
    /*
     * 省级联动
     */
    public function getCity(){
        $area=M("Areas");
        $area_id=intval($_POST['id']);
        if(!empty($area_id)){
            $city_list=$area->where("parent_id='$area_id'")->select();
//           foreach ($city_list as $vo) {
//               $op[] = '<option value="'.$vo['area_id'].'">'.$vo['area_name'].'</option>'; ;
//           }
//           $this->ajaxReturn($op);
            $this->ajaxReturn($city_list);
        }
    }
    /**
     * ajax上传图片方法
     */
    function addPicForAjax(){
		//dump($_FILES);
		//dump($_GET);
		//dump($_POST);
        //头像上传
//         $upload = new Upload();// 实例化上传类
//         $upload->maxSize   =     3145728 ;       // 设置附件上传大小
//         $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
//         $upload->rootPath  =     './Uploads/';   // 设置附件上传根目录
//         $upload->savePath  =     'Member/Head/'; // 设置附件上传（子）目录
//         // 上传文件
//         $info   =   $upload->upload();
//         if(!$info) {// 上传错误提示错误信息
//             $arr['status']=0;
//             $arr['info']=$upload->getError();
//             $this->ajaxReturn($arr);
//         }else{
//             // 上传成功
//             $pic = ltrim('/Uploads/'.$info['file_upload1']["savepath"].$info['file_upload1']["savename"],'.');
// //            $pic_1=$info['head']['savepath'].$info['head']['savename'];
// //            $pic=ltrim($pic_1,".");
//             $arr['status']=1;
//             $arr['info']=$pic;
//             $this->ajaxReturn($arr);
//         }
        $arr['status']=0;
        $arr['info']="暂时不允许上传头像!";
        $this->ajaxReturn();
    }
    
    
   /**
    *  我的众筹显示页面
    */
    public function zhongchou(){   	
    	$member_id = $_SESSION['USER_KEY_ID'];    	
    	$count      = M('Issue_log')->where("uid = {$member_id}")->count();// 查询满足要求的总记录数
    	$Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性    	
    	$list = M('Issue_log')
        	->field(C('DB_PREFIX').'issue_log.*,'.C('DB_PREFIX').'issue.id,'.C('DB_PREFIX').'issue.currency_id,'.C('DB_PREFIX').'issue.title')
        	->join(C('DB_PREFIX').'issue on '.C('DB_PREFIX').'issue.id = '.C('DB_PREFIX').'issue_log.iid')
        	->where(C('DB_PREFIX')."issue_log.uid = {$member_id}")->limit($Page->firstRow.','.$Page->listRows)->select();    	
        	$this->assign('page',$show);// 赋值分页输出
        	$this ->assign('list',$list);
            $this->display();
    }
		
	public function substitution_center(){		
		$this->display();
	}
	public function duihuan(){	
		$this->display();
	}
}
