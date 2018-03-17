<?php
namespace Home\Controller;
use Home\Controller\TradeFatherController;
use Common\Logic\TradeLogic;
class TradeController extends OrdersController {
	protected  $currency;
	protected  $entrust;
	protected  $trade;
	public function _initialize(){
		parent::_initialize();
		$this->currency=M('Currency');
		$this->entrust=M('Entrust');
		$this->trade=M('Orders');
	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	}
    
    //买入
    public function buy($p=array(),$l=1){		
    	if(!empty($p)){
    		$_POST=$p;
    	}
    	//checkLogin() 验证是否登录
        if(!$this->checkLogin()){
            $data['status']=0;
            $data['info']='请先登录再进行此操作';
            $this->ajaxReturn($data);
        }
		$m = M('Member')->where(array('member_id'=>$_SESSION['USER_KEY_ID']))->find();
		if($m['status'] == 0 ){
			$data['status']=-11;
            $data['info']='请去用户中心绑定手机号';
            $this->ajaxReturn($data);
		}
		//获取交易货币类型currency_id
        $currency=$this->getCurrencyByCurrencyId($_POST['currency_id']);
      	
        //交易时间段限制
        $time=strtotime(date('Y-m-d'));
			if(empty($currency['start_time_h']) || empty($currency['end_time_h'])){
				$start_time = 0;
				$over_time = time() + 60;
			}else{
				$start_time=$time+$currency['start_time_h']*3600+$currency['start_time_m']*60;
				$over_time=$time+$currency['end_time_h']*3600+$currency['end_time_m']*60;
			}
	        
	   		if(time()<$start_time||time()>$over_time){
				$data['status']=-10;
				$data['info']='交易未开启，请在交易时间内进行交易。';
				$this->ajaxReturn($data);
			}
		$week = date("l",time());  //获取当前日期是周几
			if($week == 'Saturday' && $currency['is_lock_6'] == 1){
				$data['status']=-10;
				$data['info']='交易未开启，请在交易时间内进行交易。';
				$this->ajaxReturn($data);
			}
			if($week == 'Sunday' && $currency['is_lock_7'] == 1 ){
				$data['status']=-10;
				$data['info']='交易未开启，请在交易时间内进行交易。';
				$this->ajaxReturn($data);
			}
		//获取交易价格、数量、交易密码   floatval()浮点型
        $buyprice=floatval(I('post.buyprice'));
        $buynum=floatval(I('post.buynum'));
        $buypwd=I('post.buypwd');
        $buycurrency_id=intval(I('post.currency_id'));

        //获取币的相关信息
        if ($currency['is_lock']){
            $data['status']=-5;
            $data['info']= '该币种暂时不允许交易';
            $this->ajaxReturn($data);
        }            
        if(!is_numeric($buyprice)||!is_numeric($buynum)){
            $data['status']=0;
            $data['info']='您的挂单价格或数量有误,请修改';
            $this->ajaxReturn($data);
        }
        if($buyprice<0){
        	$data['status']=-12;
        	$data['info']='您的挂单价格请输入正数';
        	$this->ajaxReturn($data);
        }
        if($buynum<0){
        	$data['status']=-12;
        	$data['info']='您的挂单数量请输入正数';
        	$this->ajaxReturn($data);
        }

        //涨停价格限制
		$price_time = strtotime(date('Y-m-d',time()));
		$count_price['add_time'] = array('lt',$price_time);		
		$price_last = M('Trade_'.$buycurrency_id)->Field('price')->where($count_price)->order('add_time desc')->find();
		//dump($price_last);die;
		if($currency['currency_name'] == 'G积分'){
			$price_last['price'] = $price_last['price'] ? $price_last['price'] : 12.25;
		}
        if ($currency['price_down']>0&&$buyprice < ( ( 1 - $currency['price_down']/100) * $price_last['price']  ) ){
            $msg['status']=-9;
            $msg['info']='交易价格超出了跌停价格限制';
            $this->ajaxReturn($msg);
        }       
        //涨停价格限制
        if ($currency['price_up']>0&&$buyprice >( ( 1 + $currency['price_up']/100) * $price_last['price']  )){
            $msg['status']=-7;
            $msg['info']='交易价格超出了涨停价格限制';
            $this->ajaxReturn($msg);
        }       
        if ($buyprice*$buynum<1){
            $data['status']=0;
            $data['info']='不能委托低于1元的订单';
            $this->ajaxReturn($data);
        }
        /*if (!is_int($buynum)){
            $data['status']=-1;
            $data['info']='交易数量必须是整数';
            $this->ajaxReturn($data);
        }*/
        if ($buynum<0){
            $data['status']=-2;
            $data['info']='交易数量必须是正数';
            $this->ajaxReturn($data);
        }
        $member=$this->member;
        if(md5(I('post.buypwd'))!=$member['pwdtrade']){
            $data['status']=-3;
            $data['info']='交易密码不正确';
            $this->ajaxReturn($data);
        }
        $_SESSION['tradepwd'] = I('post.buypwd');
        if ($this->checkUserMoney($buynum, $buyprice, 'buy', $currency)){
            $data['status']=-4;
            $data['info']='您账户余额不足';
            $this->ajaxReturn($data);
        }
      //  M()->query('lock tables yang_orders write, yang_currency_user write');
      
		//S('huancunsuo',null);
        if(S("huancunsuo")){
        	++$l;
        	if($l>=20){
        		$data['status']=-111;
        		$data['info']="服务器交易繁忙，请稍后再试";
        		$this->ajaxReturn($data);exit();
        	}
        	$this->buy($_POST,$l);       	 
        }
        //设置缓存100秒
        S('huancunsuo',"1",2);


        //开启事物
        M()->startTrans();       
        //计算买入需要的金额
        $trade_money=$buynum*$buyprice;//*(1+($currency['currency_buy_fee']/100));
		// dump($trade_money);die;
        //操作账户
        //当前账户数额减
        $r[]=$this->setUserMoney($this->member['member_id'],$currency['trade_currency_id'], $trade_money,'dec', 'num');
        //当前账户冻结金额加
		$r[]=$this->setUserMoney($this->member['member_id'],$currency['trade_currency_id'], $trade_money, 'inc','forzen_num');
        //挂单流程
        $r[]=$this->guadan($buynum, $buyprice, 'buy', $currency);
       
        //交易流程
        $r1[]=$this->trade($currency['currency_id'], 'buy', $buynum, $buyprice,$currency);
        foreach ($r1 as $v){
           $r[]=$v;
        }
   
		//M()->query("UNLOCK TABLES");
        if (in_array(false, $r)){
           M()->rollback();
           $msg['status']=-7;
           $msg['info']='操作未成功';
           //清除缓存
           S('huancunsuo',null);
           $this->ajaxReturn($msg);
        }else {
           M()->commit();
           $msg['status']=1;
           $msg['info']='操作成功';
           //清除缓存  清除已经交易完成的记录  GS 20170402		  
           S('huancunsuo',null);
           $this->ajaxReturn($msg);
        }
       
    }


	/*卖出
	 * 
	 * 1.是否登录
	 * 1.5 是否开启交易
	 * 2.准备数据
	 * 3.判断数据
	 * 4.检查账户
	 * 5.操作个人账户
	 * 6.写入数据库
	 * 
	 * 
	 * 
	 */
	
	public function sell($p=array(),$l=1){
		if(!empty($p)){
			$_POST=$p;
		}
		if(!$this->checkLogin()){
			$data['status']=-1;
			$data['info']='请先登录再进行此操作';
			$this->ajaxReturn($data);
		}
		$m = M('Member')->where(array('member_id'=>$_SESSION['USER_KEY_ID']))->find();
		if($m['status'] == 0 ){
			$data['status']=-11;
            $data['info']='请去用户中心绑定手机号';
            $this->ajaxReturn($data);
		}
		//获取币种信息
		$currency=$this->getCurrencyByCurrencyId($_POST['currency_id']);
      	//dump($currency);die;
        //交易时间段限制
        $time=strtotime(date('Y-m-d'));
			if(empty($currency['start_time_h']) || empty($currency['end_time_h'])){
				$start_time = 0;
				$over_time = time() + 60;
			}else{
				$start_time=$time+$currency['start_time_h']*3600+$currency['start_time_m']*60;
				$over_time=$time+$currency['end_time_h']*3600+$currency['end_time_m']*60;
			}		
	   		if(time()<$start_time||time()>$over_time){
				$data['status']=-101;
				$data['info']='交易未开启，请在交易时间内进行交易。';
				$this->ajaxReturn($data);
			}
		$week = date("l",time());  //获取当前日期是周几
			if($week == 'Saturday' && $currency['is_lock_6'] == 1){
				$data['status']=-102;
				$data['info']='交易未开启，请在交易时间内进行交易。';
				$this->ajaxReturn($data);
			}
			if($week == 'Sunday' && $currency['is_lock_7'] == 1 ){
				$data['status']=-103;
				$data['info']='交易未开启，请在交易时间内进行交易。';
				$this->ajaxReturn($data);
			}
		//获取卖出价格，卖出数量，支付密码
		$sellprice=I('post.sellprice');
		$sellnum=I('post.sellnum');
		$sellpwd=I('post.sellpwd');
		$currency_id=I('post.currency_id');

		//检查是否开启交易
	    if ($currency['is_lock']){
	       $msg['status']=-2;
	       $msg['info']='该币种暂时不能交易';
	       $this->ajaxReturn($msg);
	    }	   
		if (empty($sellprice)||empty($sellnum)){
		    $msg['status']=-3;
		    $msg['info']='卖出价格或在数量不能为空';
		    $this->ajaxReturn($msg);
		}
		if($sellprice<0){
			$data['status']=-12;
			$data['info']='价格必须大于0';
			$this->ajaxReturn($data);
		}
		if($sellnum<0){
			$data['status']=-13;
			$data['info']='数量必须大于0';
			$this->ajaxReturn($data);
		}
		if ($sellnum*$sellprice<1){
		    $data['status']=0;
		    $data['info']='不能委托低于1元的订单';
		    $this->ajaxReturn($data);
		}
		
		if (empty($sellpwd)){
		    $msg['status']=-4;
		    $msg['info']='交易密码不能为空';
		    $this->ajaxReturn($msg);
		}
		
		if ($this->member['pwdtrade']!=md5($sellpwd)){
		    $msg['status']=-5;
		    $msg['info']='交易密码不正确';
		    $this->ajaxReturn($msg);
		}
		$_SESSION['tradepwd'] = $sellpwd;

		//涨停价格限制
		$price_time = strtotime(date('Y-m-d',time()));
		$count_price['add_time'] = array('lt',$price_time);		
		$price_last = M('Trade_'.$currency_id)->Field('price')->where($count_price)->order('add_time desc')->find();
			if($currency['currency_name'] == 'G积分'){
				$price_last['price'] = $price_last['price'] ? $price_last['price'] : 12.25;
			}						
			if ($currency['price_down']>0 && $sellprice< (( 1 - $currency['price_down']/100) * $price_last['price'] )){
			    $msg['status']=-9;
			    $msg['info']='交易价格超出了跌停价格限制';
			    $this->ajaxReturn($msg);
			}		
		//涨停价格限制
		/* if ($currency['price_up']>0&&$sellprice>$currency['price_up']){ */
			if ($currency['price_up']>0&&$sellprice>(( 1 + $currency['price_up']/100) * $price_last['price']  )){
			    $msg['status']=-7;
			    $msg['info']='交易价格超出了涨停价格限制';
			    $this->ajaxReturn($msg);
			}
		//检查账户是否有钱
			if ($this->checkUserMoney($sellnum, $sellprice, 'sell', $currency)){
			    $msg['status']=-6;
			    $msg['info']='您的账户余额不足';
			    $this->ajaxReturn($msg);
			}

		//S('huancunsuo',null);
		if(S("huancunsuo")){
			++$l;
			if($l>=20){
				$data['status']=-111;
				$data['info']="服务器交易繁忙，请稍后再试";
				$this->ajaxReturn($data);exit();
			}
			$this->sell($_POST,$l);		
		}
		//设置缓存100秒
		S('huancunsuo',"1",2);

		//减可用钱 加冻结钱
		M()->startTrans();
		$r[]=$this->setUserMoney($this->member['member_id'],$currency['currency_id'], $sellnum, 'dec', 'num');
		$r[]=$this->setUserMoney($this->member['member_id'],$currency['currency_id'], $sellnum,'inc','forzen_num');
        //写入数据库
		$r[]=$this->guadan($sellnum, $sellprice, 'sell', $currency);
		//成交
		$r[]=$this->trade($currency['currency_id'], 'sell', $sellnum, $sellprice,$currency);		
		
		if (in_array(false, $r)){
		    M()->rollback();
		    $msg['status']=-7;
		    $msg['info']='操作未成功';
		    //清除缓存
		    S('huancunsuo',null);
		    $this->ajaxReturn($msg);
		}else {
		    M()->commit();
		    $msg['status']=1;
		    $msg['info']='操作成功';
		    //清除缓存
		    S('huancunsuo',null);
		    $this->ajaxReturn($msg);
		}
		
	} 

	//我的成交
	public function myDeal(){
	    if (!$this->checkLogin()){
	        $this->redirect(U('Index/index','',false));
	    }
	  	//获取主币种
		//$currency=$this->getCurrencyByCurrencyId();
		$currency=$this->currency();
		//dump($x);die;
		$this->assign('culist',$currency);
		$currencytype = I('currency');
	
		if(!empty($currencytype)){
		//$where['currency_id'] =$currencytype;
			$c_id=$currencytype;
		}else{
			$c_id=$currency[0]['currency_id'];
		}
		M("Trade_".$c_id)->where("currency_id <> '{$c_id}'")->delete();
		$where['member_id'] = $_SESSION['USER_KEY_ID'];
	    
	    $count  = M('Trade_'.$c_id)->where($where)->count();// 查询满足要求的总记录数
	    $Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
	    //给分页传参数
	    setPageParameter($Page, array('currency'=>$currencytype));
	    $show       = $Page->show();// 分页显示输出
	    //进行分页数据查询 注意limit方法的参数要使用Page类的属性
	    $list = M('Trade_'.$c_id)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
	    $this->assign('page',$show);// 赋值分页输出
	    $this->assign('list',$list);
	    $this->assign("c_id",$c_id);
	    $this->display();
	}
	

	
    /**
     *
     * @param int  $num 数量
     * @param float $price 价格
     * @param char $type 买buy 卖sell
     * @param $currency_id 交易币种
     */
        private function  checkUserMoney($num,$price,$type,$currency){        	
            //获取交易币种信息
            if ($type=='buy'){
                $trade_money=$num*$price*(1+$currency['currency_buy_fee']/100);
                $currency_id=$currency['trade_currency_id'];
            }else {
                $trade_money=$num;
                $currency_id=$currency['currency_id'];
            }
            //和自己的账户做对比 获取账户信息
            $money=$this->getUserMoney($currency_id, 'num');
	            if ($money<$trade_money){
	                return true;
	            }else{
	                return false;
	            }
        }

    /**
     * 挂单
     * @param int  $num 数量
     * @param float $price 价格
     * @param char $type 买buy 卖sell
     * @param $currency_id 交易币种
     */
    private function guadan($num,$price,$type,$currency){
        //获取交易币种信息
        switch ($type){
            case 'buy':
                $fee=$currency['currency_buy_fee']/100;
                $currency_trade_id=$currency['trade_currency_id'];
                break;
            case 'sell':
                $fee=$currency['currency_sell_fee']/100;
                $currency_trade_id=$currency['trade_currency_id'];
                break;
        }
        $data=array(
            'member_id'=>$this->member['member_id'],
            'currency_id'=>$currency['currency_id'],
            'currency_trade_id'=>$currency['trade_currency_id'],
            'price'=>$price,
            'num'=>$num,
            'trade_num'=>0,
            'fee'=>$fee,
            'type'=>$type,
        );
        if (D('Orders')->create($data)){
          $msg=D('Orders')->add();
          
        }else {
            $msg=0;
        }
        return $msg;       
    }        
 
    private function trade($currencyId,$type,$num,$price,$currency){
    	
        if ($type=='buy'){
            $trade_type='sell';
        }else {
            $trade_type='buy';
        }
        $memberId=$_SESSION['USER_KEY_ID'];
        //获取操作人一个订单
        $order=$this->getFirstOrdersByMember($memberId,$type ,$currencyId);
        //获取对应交易的一个订单     
        $trade_order=$this->getOneOrders($trade_type, $currencyId,$price);
       
        //如果没有相匹配的订单，直接返回
        if (empty($trade_order)){
            $r[]=true;
            return $r;
        }
              
        //如果有就处理订单
        $trade_num=min($num,$trade_order['num']-$trade_order['trade_num']);
        //增加本订单的已经交易的数量
        $r[]=M('Orders')->where("orders_id={$order['orders_id']}")->setInc('trade_num',$trade_num);
        $r[]=M('Orders')->where("orders_id={$order['orders_id']}")->setField('trade_time',time());
        //增加trade订单的已经交易的数量
        $r[]=M('Orders')->where("orders_id={$trade_order['orders_id']}")->setInc('trade_num',$trade_num);
        $r[]=M('Orders')->where("orders_id={$trade_order['orders_id']}")->setField('trade_time',time());
        
        //更新一下订单状态
        $r[]=M('Orders')->where("trade_num>0 and status=0")->setField('status',1);

        //当交易数量等于挂单数量，直接删除订单信息  GS 20170402
        //$r[]=M('Orders')->where("num=trade_num")->delete(); 
        
        /*
         * yuan 20180303 改
         * 当委托交易成功后，订单信息不被删除，修改订单状态为2
         * 
        */
        $r[]=M('Orders')->where("num=trade_num")->setField('status',2);
        
       
        //处理资金
        switch ($type){
           
            case 'buy':
                $trade_price=min($order['price'],$trade_order['price']);
                
				//bcsub 将两个高精度数字相减
                $fee = bcsub(1,$trade_order['fee'],10);
                //bcmul 将两个高精度数字相乘 
                $trade_money_notfee = bcmul($trade_num,$trade_price,10);
                $trade_order_money = bcmul($trade_money_notfee,$fee,10);          
                $order_money= $trade_num*$trade_price;
                $r[]=$this->setUserMoney($this->member['member_id'],$order['currency_id'], $trade_num*(1-$order['fee']), 'inc', 'num');
                $r[]=$this->setUserMoney($this->member['member_id'],$order['currency_trade_id'], $order_money+ $trade_num*($order['price']-$trade_price), 'dec', 'forzen_num');                
                $r[]=$this->setUserMoney($trade_order['member_id'],$trade_order['currency_id'],  $trade_num, 'dec', 'forzen_num');				
                $r[]=$this->setUserMoney($trade_order['member_id'],$trade_order['currency_trade_id'], $trade_order_money, 'inc', 'num');                
                //返还多扣除的部分
                $r[]=$this->setUserMoney($this->member['member_id'],$order['currency_trade_id'], $trade_num*($order['price']-$trade_price), 'inc', 'num');
              	//合并SQL语句  RZM 20170403
                //$r[]=$this->setUserMoney($this->member['member_id'],$order['currency_trade_id'], $trade_num*($order['price']-$trade_price), 'dec', 'forzen_num');
                //手续费
                $r[]=$this->addFinance($order['member_id'], 11, '交易手续费',$trade_num*$order['fee'], 2, $order['currency_id']);
                $r[]=$this->addFinance($trade_order['member_id'], 11, '交易手续费',$trade_num*$trade_price*$trade_order['fee'], 2, $order['currency_trade_id']);
                $r[]=$this->grandAward($order['member_id'],$order_money * $order['fee']);
                
                break;
            case 'sell':
            	//max() 返回参数中数值最大的值。
                $trade_price=max($order['price'],$trade_order['price']);
                $order_money= $trade_num*$trade_price*(1-$order['fee']);
                $trade_order_money= $trade_num*$trade_price;//*(1+$trade_order['fee']);             
               
                $r[]=$this->setUserMoney($this->member['member_id'],$order['currency_id'], $trade_num, 'dec', 'forzen_num');
                $r[]=$this->setUserMoney($this->member['member_id'],$order['currency_trade_id'], $order_money, 'inc', 'num');
                 
                $r[]=$this->setUserMoney($trade_order['member_id'],$trade_order['currency_id'], $trade_num*(1-$trade_order['fee']), 'inc', 'num');
                $r[]=$this->setUserMoney($trade_order['member_id'],$trade_order['currency_trade_id'],$trade_order_money, 'dec', 'forzen_num');
                //手续费  判断如果手续费等于0 不写入日志表
                if($trade_num*$trade_price*$order['fee'] != 0){
                $r[]=$this->addFinance($order['member_id'], 11, '交易手续费',$trade_num*$trade_price*$order['fee'], 2, $order['currency_trade_id']);
                }
                if($trade_num*$trade_order['fee'] != 0){
                $r[]=$this->addFinance($trade_order['member_id'], 11, '交易手续费',$trade_num*$trade_order['fee'], 2, $order['currency_id']);
                }
                $r[]=$this->grandAward($order['member_id'],$trade_order_money * $order['fee']);
                break;
        }
        //修正最终成交的价格   记录已经删除不需要再修正  RZM 20170403
        //$r[]=M('Orders')->where("num=trade_num and orders_id={$order['orders_id']}")->setField('price',$trade_price);
        //$r[]=M('Orders')->where("num=trade_num and orders_id={$trade_order['orders_id']}")->setField('price',$trade_price);
        
        $TLogic = new TradeLogic($currencyId);
        //写入成交表
		//$r[]=$this->addTrade($order['member_id'], $order['currency_id'], $order['currency_trade_id'],$trade_price, $trade_num, $order['type'],$order['fee']);
        $r[]=$TLogic->addTradeOne($order['member_id'], $order['currency_id'], $order['currency_trade_id'],$trade_price, $trade_num, $order['type'],$order['fee']);

		//$r[]=$this->addReward($order['member_id'],$currency,$trade_price*$trade_num*$order['fee']);
		//$r[]=$this->addTrade($trade_order['member_id'], $trade_order['currency_id'], $trade_order['currency_trade_id'], $trade_price, $trade_num, $trade_order['type'],$trade_order['fee']);         
                  
		/*最新成交改成不同时显示买和卖，以用户点击方向为准
		*cuiwei 20171208
		*/ 
	
    	//$r[]=$TLogic->addTradeOne($trade_order['member_id'],$trade_order['currency_id'], $trade_order['currency_trade_id'], $trade_price, $trade_num, $trade_order['type'],$trade_order['fee']);       
        $r[]=$this->addReward($order['member_id'],$currency,$trade_price*$trade_num*$trade_order['fee']);
		/************************************这里用于写入交易数据文件*******************************************************/
		//计算存储  该币种 最新价格，24小时涨跌，7D涨跌，24小时成交量，24小时成交额，最低价，最高价，总市值
		if(S('trade_info_'.$currencyId)){
			S('trade_info_'.$currencyId,null);
			
// 			$infos = S('trade_info_'.$currencyId);
			
// 			$infos['new_price'] = $trade_price;  //最新价格
// 			$infos['24H_change'] = intval(($trade_price - $infos['yes_price'])*10000/$infos['yes_price'])/100;//24小时涨跌
// 			$infos['7D_change'] =  intval(($trade_price - $infos['7D_price'])*10000/$infos['7D_price'])/100;//7D涨跌
// 			$infos['24H_done_num'] += $trade_num;//24小时成交量
// 			$infos['24H_done_money'] += $trade_num*$trade_price;//24小时成交额
// 			$infos['min_price'] = min($trade_price,$infos['min_price']);//最低价
// 			$infos['max_price'] = max($trade_price,$infos['min_price']);//最高价，
// 			$infos['currency_all_money'] = $currency['currency_all_num']*$trade_price;//总市值
// 			//dump($infos);
// 			S('trade_info_'.$currencyId,$infos);
			
		}else{
			//如果为空则进行添加价格  newprice,24H_change,24H_done_num,7D_change,24H_done_money,min_price,max_price,总市值，7D前价格，昨天价格
// 			$infos['new_price'] = $trade_price;  //最新价格
// 			$re_info = M('Trade_'.$currencyId)->Field('price')->order('trade_id')->find();
// 			$infos['yes_price'] = $re_info['price'];//昨日收盘价
// 			$infos['24H_change'] = intval(($trade_price - $re_info['price'])*10000/$re_info['price'])/100;//24小时涨跌
// 			$re_info2 = M('Trade_'.$currencyId)->Field('price')->where('add_time < '.time()-60*60*24*7 )->order('trade_id')->find();
// 			$infos['7D_price'] = $re_info2['price']; //7D 前收盘价
// 			$infos['7D_change'] =  intval(($trade_price - $re_info2['price'])*10000/$re_info2['price'])/100;//7D涨跌
// 			$infos['24H_done_num'] = $trade_num;//24小时成交量
// 			$infos['24H_done_money'] = $trade_num*$trade_price;//24小时成交额
// 			$infos['min_price'] = $trade_price;//最低价
// 			$infos['max_price'] = $trade_price;//最高价，
// 			$infos['currency_all_money'] = $currency['currency_all_num']*$trade_price;//总市值
// 			$infos['currency_name'] = $currency['currency_name'];
// 			$infos['currency_market'] = $currency['currency_mark'];
// 			$infos['currency_logo'] = $currency['currency_logo'];
// 			$infos['currency_digit_num'] = $currency['currency_digit_num'];
// 			$infos['time'] = time();
			$this->getCurrencyMessageById($currencyId);
		}
		//存入600条交易记录
		/*$trade_log = file_get_contents('./trade_log_'.$currencyId);
		if($trade_log){
			
			$trade_log = json_decode($trade_log,true);
			if(count($trade_log) >= 600 ){
				array_pop($trade_log);
				array_pop($trade_log);
			}
			$trade_log1 = array(
						    'date'         => time(),
							'date_ms'      => time() * 1000,
							'price'        => $trade_price,
							'amount'       => $trade_num,
							'tid'          => $trade_order['currency_trade_id'],
							'type'         => 'sell'
						);
			$trade_log2 = array(
						    'date'         => time(),
							'date_ms'      => time() * 1000,
							'price'        => $trade_price,
							'amount'       => $trade_num,
							'tid'          => $trade_order['currency_trade_id'],
							'type'         => 'buy'
						);
			array_unshift($trade_log,$trade_log1);
			array_unshift($trade_log,$trade_log2);
			$trade_log_r = json_encode($trade_log);
			file_put_contents('./trade_log'.$currencyId,$trade_log_r);
		}else{*/
			$trade_log[] = array(
						    'date'         => time(),
							'date_ms'      => time() * 1000,
							'price'        => $trade_price,
							'amount'       => $trade_num,
							'tid'          => $trade_order['currency_trade_id'],
							'type'         => 'sell'
						);
			$trade_log[] = array(
						    'date'         => time(),
							'date_ms'      => time() * 1000,
							'price'        => $trade_price,
							'amount'       => $trade_num,
							'tid'          => $trade_order['currency_trade_id'],
							'type'         => 'buy'
						);
			
			$trade_log_r = json_encode($trade_log);
			file_put_contents('./Uploads/Trade/trade_log'.$currencyId.'_'.date("Ymd"),$trade_log_r);
		//}
		$money = $trade_num*$trade_price;

		//更新总成交额的信息
		//褚天恩2017.4.19修改
		if(S('Trade_allnum_'.$currencyId)){
			$money_ago = S('Trade_allnum_'.$currencyId);
			$money_now = $money+$money_ago;
			$money_now = number_format($money_now,3);
			S('Trade_allnum_'.$currencyId,$money_now);
			
		}else{
			
			$trade_total = M('Trade_'.$currency['currency_id'])->where("currency_id={$currency['currency_id']}")->sum('money');
			$currency_message['trade_total'] = number_format($trade_total,3);
			S('Trade_allnum_'.$currencyId,$currency_message['trade_total']);
		}

		
		/*******************************************************************************************/
	   $num =$num- $trade_num;
        if ($num>0){
            //递归
           $r[]= $this->trade($currencyId, $type, $num, $price);
        }
        $this->getCurrencyMessageById($currencyId);
        
        return $r;
        
    }
    /**
     * 返回一条挂单记录
     * @param int $currencyId 币种id
     * @param float $price 交易价格
     * @return array 挂单记录
     */
    private function getOneOrders($type,$currencyId,$price){
        switch ($type){
            case 'buy':$gl='egt';$order='price desc'; break;
            case 'sell':$gl='elt'; $order='price asc';break;
        }
        $where['currency_id']=$currencyId;
        $where['type']=$type;
        $where['price']=array($gl,$price);
        $where['status']=array('in',array(0,1));
        return M('Orders')->where($where)->order('add_time desc')->order($order)->find();
    }
    /**
     * 返回用户第一条未成交的挂单
     * @param int $memberId 用户id
     * @param int $currencyId 币种id
     * @return array 挂单记录
     */
    private function getFirstOrdersByMember($memberId,$type,$currencyId){
        $where['member_id']=$memberId;
        $where['currency_id']=$currencyId;
        $where['type']=$type;
        $where['status']=array('in',array(0,1));
        return M('Orders')->where($where)->order('add_time desc')->find();
    }
    /**
     *  返回指定价格排序的订单
     * @param char $type  buy sell
     * @param float $price   交易价格
     * @param char $order 排序方式
     */
    private function getOrdersByPrice($currencyId,$type,$price,$order){
        switch ($type){
            case 'buy': $gl='elt';break;
            case 'sell': $gl='egt';break;
        }
        $where['currency_id']=$currencyId;
        $where['price']=array($gl,$price);
        $where['status']=array('in',array(0,1));
        return  M('Orders')->where($where)->order("price  $order")->select();
    }


//     /**
//      * 增加交易记录
//      * @param unknown $member_id
//      * @param unknown $currency_id
//      * @param unknown $currency_trade_id
//      * @param unknown $price
//      * @param unknown $num
//      * @param unknown $type
//      * @return boolean
//      */
//     private function  addTrade($member_id,$currency_id,$currency_trade_id,$price,$num,$type,$fee){
//         $fee=$price*$num*$fee;
//         $this->dividend($price*$num,$member_id);
//         $data=array(
//             'member_id'=>$member_id,
//             'currency_id'=>$currency_id,
//             'currency_trade_id'=>$currency_trade_id,
//             'price'=>$price,
//             'num'=>$num,
//             'fee'=>$fee,
//             'money'=>$price*$num,
//             'type'=>$type,
//         );
//         if (D('Trade')->create($data)){
//             if (D('Trade')->add()){
// 				//对上级进行奖励
// 				$this->addReward($member_id,$currency_id,$fee);
//                 return true;
//             }else {
//                 return false;
//             }
//         }else {
//             return false;
//         }
//     }

	/**
	*给上级的交易手续费的百分比的奖励
	*2016.12.26   GS
	*yuan 2018更改
	*/
	private function addReward($member_id,$currency,$fee){
		if(empty($fee)){
			return true;
		}
		if(empty($currency['currency_fee_reward'])){
			return true;
		}
		$user_info = M('Member')->field("pid")->where("member_id={$member_id}")->find();
		$pid = $user_info['pid'];
		//判断是否有上级推荐人
		if(!empty($pid)){
			//$Currency = M('Currency')->where("currency_id={$currency_id}")->find();
			$currency_fee_reward = $currency['currency_fee_reward'];
			$reward_money = intval(($fee * $currency_fee_reward)*100) / 10000;
			//dump($fee);
			//dump($reward_money);die;
			//为上级进行奖励发放
			$re = M('Member')->where("`member_id`={$pid}")->setInc('rmb',$reward_money);
			if($re){
				//加入日志
				$uname = substr($user_info['email'],0,-2).'**';
				$r[]=$this->addFinance($pid, 24, $uname.'产生的交易奖励',$reward_money, 1, 0);
			}
		}
	}

	/**
	 * 隔代奖励
	 * yuan 20180301 更改
	 * 隔代交易手续佣金ST币更改为人民币
	 */
	public function grandAward($member_id,$fee_money){
		$res = M('Member')->where(array('member_id'=>$member_id))->find();
		if($res){
			$r = M('Member')->where(array('member_id'=>$res['pid']))->find();
			if($r){
				//获取配置表返还的币名称
				$config = $this->configaction();
				$coin_id = M('Currency')->where(array('currency_name'=>$config['daicoin']))->find();
				$where['member_id'] = $r['pid'];
				//$where['currency_id'] = $coin_id['currency_id'];
				$daifee = $config['daifee']/100;
				$money = $fee_money*$daifee;
				M('Member')->where($where)->setInc('rmb',$money);
				$r[]=$this->addFinance($r['pid'], 26, '隔代推广奖',$money, 1, 0);
			}
		}
	}
}
