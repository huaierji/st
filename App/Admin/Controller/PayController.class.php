<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
use Think\Page;

class PayController extends AdminController {
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
	
     //人工充值审核页面
    public function payByMan(){
            $addTime = I('addTime');
            $endTime = I('endTime');
            $addTime = str_replace('+',' ',$addTime );
            $endTime = str_replace('+',' ',$endTime );
            $addTime=empty($addTime)?0:strtotime($addTime);//开始时间
            $endTime=empty($endTime)?0:strtotime($endTime);//结束时间
            if(!empty($addTime) && empty($endTime)){
                $where[C('DB_PREFIX')."pay.add_time"] = array('egt',$addTime);
            }else if (empty($addTime) && !empty($endTime)){
                $where[C('DB_PREFIX')."pay.add_time"] = array('elt',$endTime);
            }else if (!empty($addTime) && !empty($endTime)){
                $where[C('DB_PREFIX')."pay.add_time"] = array('between',$addTime.','.$endTime);
            }else {
                $where = array();
            }
    		$status=I('status');
    		$member_name=I('member_name');
    		$type = I('type');
    		if(!empty($type) || $type==="0"){
    		    $where[C("DB_PREFIX")."pay.type"] = $type;
            }else{
				$where[C("DB_PREFIX")."pay.type"] = array('in','1,2');
			}
    		if(!empty($status)||$status==="0"){
    			$where[C("DB_PREFIX")."pay.status"]=$status;
    		}
    		if(!empty($member_name)){
    			$where[C("DB_PREFIX")."pay.member_name"]=array('like',"%".$member_name."%");
    		}
    	$count =  M('Pay')->where($where)->count();// 查询满足要求的总记录数
    	$Page  = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	//给分页传参数
    	setPageParameter($Page, array('status'=>$status,'member_name'=>$member_name,'type'=>$type,'addTime'=>I('addTime'),'endTime'=>I('endTime')));
    	
    	$show       = $Page->show();// 分页显示输出

    	$list= M('Pay')
    	->field(C("DB_PREFIX")."pay.*,".C("DB_PREFIX")."member.email")
    	->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id=".C("DB_PREFIX")."pay.member_id")

    	->where($where)
    	->limit($Page->firstRow.','.$Page->listRows)
    	->order('add_time desc')
    	->select();
//     	echo M("Pay")->getLastSql();die;
    	foreach ($list as $k=>$v){
    		$list[$k]['status']=payStatus($v['status']);
    		$list[$k]['pay_reward']=$v['money']*$this->config['pay_reward']/100;
    	}
    	$moneyNum = M('Pay')->where( $where )->order('add_time desc')->sum( 'money' );//查看总钱
        $this->assign('moneyNum',$moneyNum);
    	$this->assign('page',$show);
    	$this->assign('list',$list);
    	$this->assign('empty','暂无数据');
     	$this->display();
     }
     //人工充值审核处理
     public function payUpdate(){
     	$pay=M('Pay');
     	$where['pay_id']=$_POST['pay_id'];
     	$list=$pay->where($where)->find();
     	if($list['status']!=0){
     		$data['status'] = -1;
     		$data['info'] = "请不要重复操作";
     		$this->ajaxReturn($data);
     	}
     	$member_id=M('Member')->where("member_id='".$list['member_id']."'")->find();
     	if($_POST['status']==1){
     		$pay->where($where)->setField('status',1);
     		if($list['money']>=$this->congif['pay_reward_limit']){
     			$list['count']=$list['count']+$list['money']*$this->config['pay_reward']/100;
     		}
     		//修改member表钱数
     		$rs=M('Member')->where("member_id='".$list['member_id']."'")->setInc('rmb',$list['money']);
     		//添加财务日志
     		$this->addFinance($member_id['member_id'],6,"线下充值".$list['money']."。",$list['money'],1,0);
     		//添加信息表
     		$this->addMessage_all($member_id['member_id'], -2, '人工充值成功', '您申请的人工充值已成功，充值金额为'.$list['money']);
     	}elseif($_POST['status']==2){
     		$rs=$pay->where($where)->setField('status',2);
     		//添加信息表
      		$this->addMessage_all($member_id['member_id'], -2, '人工充值审核未通过', '您申请的人工充值审核未通过,请重新处理');
     	}else{
     		$data['status'] = 0;
     		$data['info'] = "操作有误";
     		$this->ajaxReturn($data);
     	}
     	if($rs){
     		$data['status'] = 1;
     		$data['info'] = "修改成功";
     		$this->ajaxReturn($data);
     	}else{
     		$data['status'] = 2;
     		$data['info'] = "修改失败";
     		$this->ajaxReturn($data);
     	}
     }
     /**
      * 添加管理员充值
      */
     public function admRecharge(){
     	if(IS_POST){
     		$admin=M("Admin")->where("admin_id='{$_SESSION['admin_userid']}'")->find();
     		if(empty($_POST['password'])){
     			$this->error("请输入管理员密码");
     		}
     		if(md5($_POST['password'])!=$admin['password']){
     			$this->error("您输入的管理员密码错误");
     		}
     		if(empty($_POST['member_id'])){
     			$this->error('请输入充值人员');
     		}
     		if(!isset($_POST['currency_id'])){
     			$this->error('请输入币种');
     		}
     		if(empty($_POST['money'])){
     			$this->error('请输入充值金额');
     		}
     		$data['member_id'] = I('member_id','','intval');
     		$member=M('Member')->where('member_id='.$data['member_id'])->find();
     		if(!$member){
     			$this->error('用户不存在');
     		}
     		$data['member_name'] = $member['name'];
     		$data['currency_id'] = I('currency_id','','intval');
     		$data['money'] = I('money');
     		$data['status'] = 1;
     		$data['add_time']  = time();
     		$data['type'] = 3;//管理员充值类型
     		M()->startTrans();//开启事务
     		$r[] = M('Pay')->add($data);
     		if($data['currency_id']==0){
     			$r[] = M('Member')->where(array('member_id'=>$data['member_id']))->setInc('rmb',$data['money']);
     		}else{
     			$r[] = M('Currency_user')->where(array('member_id'=>$data['member_id'],array('currency_id'=>$data['currency_id'])))->setInc('num',$data['money']);
     		}
     		$r[] = $this->addFinance($data['member_id'], 3, "管理员充值", $data['money'], 1, $data['currency_id']);
     		$r[] = $this->addMessage_all($data['member_id'], -2, "管理员充值", "管理员充值".getCurrencynameByCurrency($data['currency_id']).":".$data['money']);
     		if(!in_array(false,$r)){
     			M()->commit();
     			$this->success('添加成功');
     			
     		}else{
     			M()->rollback();
     			$this->error('添加失败');
     		}
     	}else{
	     	$type_id=I('type_id');
			$email=I('email');
			$member_id=I('member_id');
			if(!empty($type_id)){
				$where['currency_id']=$type_id;
			}
			if(!empty($email)){
				$uid=M('Member')->where("email like '%{$email}%'")->find();
				$where["member_id"]=$uid['member_id'];
			}
	        if(!empty($member_id)){
	            $where["member_id"]=$member_id;
	        }
	        $where['type']=3;
     		$count =  M('pay')->where($where)->count();// 查询满足要求的总记录数
     		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
     		//给分页传参数
     		setPageParameter($Page, array('type_id'=>$type_id,'email'=>$email,'member_id'=>$member_id));
     		
     		$show       = $Page->show();// 分页显示输出
     		
     		$list= M('Pay')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('add_time desc')->select();
     		//筛选
     		$type=M('Currency')->select();
     		$this->assign('type',$type);
     		$this->assign('page',$show);
     		$this->assign('list',$list);
     		$this->display();
     	}
     }
	  /**
      * 导出excel文件
      */
     public function derivedExcel(){

     	//时间筛选
     	$add_time=I('get.add_time');
     	$end_time=I('get.end_time');
     	$add_time=empty($add_time)?0:strtotime($add_time);
     	$end_time=empty($end_time)?0:strtotime($end_time);

     	$where[C("DB_PREFIX").'pay.add_time'] = array('lt',$end_time);
     	$list= M('Pay')
    	->field(C("DB_PREFIX")."pay.pay_id,"
		.C("DB_PREFIX")."member.email,"
		.C("DB_PREFIX")."member.name,"
		.C("DB_PREFIX")."pay.account,"
		.C("DB_PREFIX")."pay.money,"
		.C("DB_PREFIX")."pay.status,"
		.C("DB_PREFIX")."pay.add_time")
    	->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.name=".C("DB_PREFIX")."pay.member_name")

    	->where($where)
    	->where(C("DB_PREFIX").'pay.add_time>'.$add_time)
    	->order('add_time desc')
    	->select();
//     	echo M("Pay")->getLastSql();die;
    	foreach ($list as $k=>$v){
    		$list[$k]['status']=payStatus($v['status']);
    		$list[$k]['add_time']=date('Y-m-d H:i:s',$list[$k]['add_time']);
    	}
     	$title = array(
     		'订单号',
     		'汇款人账号',
     		'汇款人',
     		'银行卡号',
     		'充值钱数',
     		'实际打款',
     		'状态',
     		'时间',
     	);
     	$filename= $this->config['name']."人工充值日志-".date('Y-m-d',time());
     	$r = exportexcel($list,$title,$filename);
     }
	  //人工充值审核页面
    public function fill(){
            $addTime = I('addTime');
            $endTime = I('endTime');
            $addTime = str_replace('+',' ',$addTime );
            $endTime = str_replace('+',' ',$endTime );
            $addTime=empty($addTime)?0:strtotime($addTime);//开始时间
            $endTime=empty($endTime)?0:strtotime($endTime);//结束时间
            if(!empty($addTime) && empty($endTime)){
                $where['ctime'] = array('egt',$addTime);
            }else if (empty($addTime) && !empty($endTime)){
                $where['ctime'] = array('elt',$endTime);
            }else if (!empty($addTime) && !empty($endTime)){
                $where['ctime'] = array('between',$addTime.','.$endTime);
            }else {
                $where = array();
            }
			//dump($_POST);
			$member_name = I('member_name');
			if(!empty($member_name)){
				$member = M('Member')->Field('member_id')->where("name like '%{$member_name}%'")->find();
				$where['uid'] = $member['member_id'];
			}

			//dump($where);
     		$count =  M('Fill')->where($where)->count();// 查询满足要求的总记录数
     		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            setPageParameter($Page, array('member_name'=>$member_name,'addTime'=>I('addTime'),'endTime'=>I('endTime')));
     		$show       = $Page->show();// 分页显示输出

     		$list= M('Fill')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
			$where['status'] = 1;
			$fill = M("Fill")->Field("sum(num) as total_all,sum(actual) as total_act")->where($where)->select();
			//dump($fill);
			$this->assign('fill',$fill);
			$this->assign('post',I());
     		$this->assign('page',$show);
     		$this->assign('list',$list);
     		$this->display();
     }
	 
	//比特币充币申请界面
	public function btc_recharge(){
		
		$currency = M('Currency')->Field('currency_id')->where("currency_name='比特币'")->find();
		
		$where[C("DB_PREFIX").'tibi.status'] = array('in','2,3,7');
		$where[C("DB_PREFIX").'tibi.currency_id'] = $currency['currency_id'];
		
		$count =  M('Tibi')->where($where)->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$list= M('Tibi')->Field(C("DB_PREFIX")."tibi.*,".C("DB_PREFIX")."member.name as name")
						->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id = ".C("DB_PREFIX")."tibi.user_id")
						->where($where)->limit($Page->firstRow.','.$Page->listRows)
						->order(C("DB_PREFIX").'tibi.status')
						->select();
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	
	//每日首次提币进行审核方法
	public function s_tibi(){
		$status = I('status');
		$name = I('name');
		$currency_id=I('currency_id');
		$email = I('email');
		$url = I('url');
		
		//读取币种表
		$curr=M("Currency")->select();
		$this->assign("currency",$curr);
		
		if(!empty($status)){
			if($status==2){
				$data[C("DB_PREFIX")."tibi.status"] = 0;
			}else{
				$data[C("DB_PREFIX")."tibi.status"] = $status;
			}
		}
		if(!empty($name)){
			$data[C("DB_PREFIX")."member.name"] =  array('like','%'.$name.'%');
		}
		if(!empty($currency_id)){
			$data[C("DB_PREFIX")."currency.currency_id"] = array("EQ",$currency_id);
		}
		if(!empty($email)){
			$data[C("DB_PREFIX")."member.email"] =  array('like','%'.$email.'%');
		}
		if(!empty($url)){
			$data[C("DB_PREFIX")."tibi.url"] =  array("EQ",$url);
		}
		
		$where['ti_id'] = '';
		
		$count =  M('Tibi')->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id = ".C("DB_PREFIX")."tibi.user_id")
		->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id = ".C("DB_PREFIX")."tibi.currency_id")
		->where($data)->where('( ti_id = \'\' OR '.C("DB_PREFIX").'tibi.type= 1 )  ')
		->count();// 查询满足要求的总记录数
		
		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		setPageParameter($Page,array('status'=>$status,'username'=>$name,'currency_id'=>$currency_id,'email'=>$email,'url'=>$url));
		$show    = $Page->show();// 分页显示输出
		$list= M('Tibi')->Field(C("DB_PREFIX")."tibi.*,".C("DB_PREFIX")."member.name as username,".C("DB_PREFIX")."member.email,".C("DB_PREFIX")."currency.currency_name as currencyname")
						->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id = ".C("DB_PREFIX")."tibi.user_id")
						->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id = ".C("DB_PREFIX")."tibi.currency_id")
						->where($data)
						->where('(ti_id = \'\' OR '.C("DB_PREFIX").'tibi.type=1 ) ')
						->limit($Page->firstRow.','.$Page->listRows)
						->order(C("DB_PREFIX")."tibi.id desc")
						->select();
		$currency = M('Currency')->field('currency_name,currency_id')->select();
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	
	//比特币提币界面
	public function btc_tibi(){
		$currency = M('Currency')->Field('currency_id')->where("currency_name='比特币'")->find();
		
		$where[C("DB_PREFIX").'tibi.status'] = array('in','0,1,8');
		$where[C("DB_PREFIX").'tibi.currency_id'] = $currency['currency_id'];
		
		$count =  M('Tibi')->where($where)->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$list= M('Tibi')->Field(C("DB_PREFIX")."tibi.*,".C("DB_PREFIX")."member.name as username")
						->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id = ".C("DB_PREFIX")."tibi.user_id")
						->where($where)->limit($Page->firstRow.','.$Page->listRows)
						->order(C("DB_PREFIX").'tibi.status')
						->select();
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	
	//比特币充币申请审核通过处理
	public function btc_shenhe_success(){
		$id = I('post.id','');
		if(empty($id)){
			$data['status'] = -1;
			$data['info'] = '重要参数丢失';
			$this->ajaxReturn($data);
		}
		$info = M('Tibi')->where("`id`={$id}")->find();
		if($info['status'] == 2){
			$re = M('Tibi')->where("`id`={$id}")->setField(array('status'=>3,'check_time'=>time()));
		}else if($info['status'] == 0){
			$re = M('Tibi')->where("`id`={$id}")->setField(array('status'=>1,'check_time'=>time()));
		}else{
			$re = false;
		}
		
		if($re){
			$data['status'] = 1;
			$data['info'] = '充币已通过';
			M('Currency_user')->where("`currency_id`={$info['currency_id']} AND `member_id`= {$info['user_id']}")->setInc('num',$info['num']);
			$this->ajaxReturn($data);
		}else{
			$data['status'] = -2;
			$data['info'] = '操作异常';
			$this->ajaxReturn($data);
		}
		
	}
	//比特币充币申请审核不通过处理
	public function btc_shenhe_false(){
		$id = I('post.id','');
		if(empty($id)){
			$data['status'] = -1;
			$data['info'] = '重要参数丢失';
			$this->ajaxReturn($data);
		}
		$info = M('Tibi')->where("`id`={$id}")->find();
		if($info['status'] == 2){
			$re = M('Tibi')->where("`id`={$id}")->setField(array('status'=>7,'check_time'=>time()));
		}else if($info['status'] == 0){
			$re = M('Tibi')->where("`id`={$id}")->setField(array('status'=>8,'check_time'=>time()));
		}else{
			$re = false;
		}
		if($re){
			$data['status'] = 1;
			$data['info'] = '充币已驳回';
			$this->ajaxReturn($data);
		}else{
			$data['status'] = -2;
			$data['info'] = '操作异常';
			$this->ajaxReturn($data);
		}
		
	}
	
	public function s_shenhe_success(){
		$list = M('Tibi')->where('id='.I('post.id'))->find();
		$currency=M("Currency")->where("currency_id='{$list['currency_id']}'")->find();//这个是货币
		// dump($list);
		// dump($currency);
		
		$bool = $this->check_qianbao_address($list['url'],$currency);
		// dump($bool);
		if(!$bool){
			$data['status']=-1;
			$data['info']="钱包地址不正确";
			$this->ajaxReturn($data);exit();
		}
		// echo '<<<<<<<<<<<<<<<<<<<<<<<<<<<<';
		$tibi=$this->qianbao_tibi($list['url'],$list['actual'],$currency);//提币程序
		// if($tibi == 1){
			// $data['status']=-2;
			// $data['info']="钱包余额不足";
			// $this->ajaxReturn($data);exit();
		// }
		// dump($tibi);die;
		if($tibi){
			
			M('Tibi')->where('id='.I('post.id'))->setField('ti_id',$tibi);
			M('Tibi')->where('id='.I('post.id'))->setField('status',0);
			M('Tibi')->where('id='.I('post.id'))->setField('type',1);
			$data['status'] = 1;
			$data['info'] = '审核通过';
			$this->ajaxReturn($data);
		}else{
		
			$data['status'] = -2;
			$data['info'] = '提币失败，可能余额不足';
			$this->ajaxReturn($data);
		}
	}
	
	public function s_shenhe_false(){
		$list = M('Tibi')->where('id='.I('post.id'))->find();
		
		if($list['status'] == 12){
			$res = M('Tibi')->where('id='.I('post.id'))->setField('status',-1);
			M("Currency_user")->where("member_id='{$list['user_id']}' and currency_id='{$list['currency_id']}'")->setInc("num",$list['num']);
			M('Tibi')->where('id='.I('post.id'))->setField('type',1);
			$data['status'] = 1;
			$data['info'] = '驳回成功';
			$this->ajaxReturn($data);
		}else{
			$data['status'] = -2;
			$data['info'] = '操作失败';
			$this->ajaxReturn($data);
		}
	}
	
	
	  /**
     * 提币引用的方法
     * @param unknown $url 钱包地址
     * @param unknown $money 提币数量
     * 
     * 需要加密 *********************
     */
    private function qianbao_tibi($url,$money,$currency){
		$money = floatval($money);
		// dump($money);
    	require_once 'App/Common/Common/easybitcoin.php';
    	$bitcoin = new \Bitcoin($currency['rpc_user'],$currency['rpc_pwd'],$currency['rpc_url'],$currency['port_number']);
    	//$result = $bitcoin->getinfo();
		// dump($result);
		//if($result['balance']<$money){
		//	return 1;
		//}
		//var_dump($result);die;
    	$bitcoin->walletlock();//强制上锁
    	$bitcoin->walletpassphrase($currency['qianbao_key'],20);
    	$id=$bitcoin->sendtoaddress($url,$money);
    	$bitcoin->walletlock();
    	return $id;
    }
      /**
     * 检测地址是否是有效地址
     *
     * @return boolean 如果成功返回个true
     * @return boolean 如果失败返回个false；
     *  @param unknown $url
     *  @param $port_number 端口号 来区分不同的钱包
     */
    private function check_qianbao_address($url,$currency){
    	
    	require_once 'App/Common/Common/easybitcoin.php';
 	    $bitcoin = new \Bitcoin($currency['rpc_user'],$currency['rpc_pwd'],$currency['rpc_url'],$currency['port_number']);
    	$address = $bitcoin->validateaddress($url);
    	if($address['isvalid']){
    		return true;
    	}else{
    		return false;
    	}
    }
	
	
}
