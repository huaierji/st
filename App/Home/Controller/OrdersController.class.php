<?php
namespace Home\Controller;
use Common\Controller\CommonController;
use Common\Logic\TradeLogic;
class OrdersController extends CommonController{
    public function _initialize(){
        parent::_initialize();
    }
    /**
     * 测试
     */
    public function test(){
    	$currency = M("Currency")->where(['is_line'=>1])->field('currency_id,currency_mark')->select();
    	//K线查询时间段
    	$kline[] = ['name'=>'kline_5m','time'=>'5'];
    	$kline[] = ['name'=>'kline_15m','time'=>'15'];
    	$kline[] = ['name'=>'kline_30m','time'=>'30'];
    	$kline[] = ['name'=>'kline_1h','time'=>'60'];
    	$kline[] = ['name'=>'kline_1d','time'=>24*60];
  		//循环存文件
    	foreach ($currency as $k=>$v){
    		//查询名称
    		$symbol = '100bi'.strtolower($v['currency_mark']).'cny';
    		//查询数据存入时间
    		foreach ($kline as $kl => $vl){
    			//查询时间
    			$step = 60 * $vl['time'];
    			//获取数据
    			$output = $this ->curl($symbol,$step);
    			$result = json_decode($output);
    			foreach ($result as $kk=>$vv){
    				if($kk > 60){
    					continue ;
    				}
    				$data[$kk][0] = $vv[0] * 1000;	//时间
    				$data[$kk][1] = $vv[5];		//成交量
    				$data[$kk][2] = $vv[1];		//开盘价
    				$data[$kk][3] = $vv[2];		//最高价
    				$data[$kk][4] = $vv[3];		//最低价
    				$data[$kk][5] = $vv[4];		//收盘价
    			}
    			//转成Json
    			$log = json_encode($data);
    			//存文件
    			header("Content-type: text/html; charset=utf-8");
    			//文件名
    			$file  = 'kline/kline_'.$v['currency_id'].'_'.$vl['name'].'.log';	
    			//存文件
    			file_put_contents($file,$log);
    		}
    	}
    }
    
    /**
     * 获取数据
     */
    private function curl($symbol,$step){
    	//查询接口
    	$smsapi = "https://plugin.sosobtc.com/widgetembed/data/period";
    	//拼接参数
    	$sendurl = $smsapi."?symbol={$symbol}&step={$step}";
    	//初始化url
    	$ch = curl_init();
    	// 设置URL和相应的选项
    	curl_setopt($ch,CURLOPT_URL,$sendurl);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	//返回值
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    	// 抓取URL并把它传递给浏览器
    	$output = curl_exec($ch);
    	// 关闭cURL资源，并且释放系统资源
    	curl_close($ch);
    	return $output;
    }
    
    /**
     * 获取文件K线
     */
    private function getCurl($currency_id,$char){
    	header("Content-type: text/html; charset=utf-8");
    	$file  = 'kline/kline_'.$currency_id.'_'.$char.'.log';	
    	return json_decode(file_get_contents($file));
    }
    //     		//添加数据
    //     		foreach ($result as $kk => $vv){
    //     			$insert['trade_no'] = 'T'.$vv[0];		//订单号
    //     			$insert['member_id'] = 198;				//用户id
    //     			$insert['currency_id'] = $v['currency_id'];	//用户id
    //     			$insert['currency_trade_id'] = 0;		//用户id
    //     			$insert['price'] = $vv[1];				//购买价格
    //     			$insert['num'] = $vv[5];					//数量
    //     			$insert['money'] = $vv[5] * $vv[1];		//订单金额
    //     			$insert['fee'] = 0.0024;				//手续费
    //     			$insert['type'] = 'buy';				//订单状态
    //     			$insert['add_time'] = $vv[0];			//时间
    //     			$insert['status'] = 0;					//状态
    //     			//添加数据
    //     			$re = M('Trade_'.$v['currency_id'])->add($insert);
    //     		}
    
    
    
//     $member_id = 12991;
     
//     $list = M('Trade_34')->where(array('member_id'=>$member_id))->select();
//     foreach($list as $k=>$v){
//     	$list[$k]['add_time'] = date("Y-m-d H:i:s",$list[$k]['add_time']);
//     	if($list[$k]['type'] == 'buy'){
//     		$aa = '买入';
//     	}
//     	if($list[$k]['type'] == 'sell'){
//     		$aa = '卖出';
//     	}
//     	$content = '何秋霞'.$list[$k]['add_time'].$aa.$list[$k]['currency_id'].'金额：'.$list[$k]['price'].'数量：'.$list[$k]['num'];
//     	dump($content);
//     }
     
//     dump($list);
//     die();
    //交易页面显示
    public function index(){
    
        if(empty($_GET['currency'])){
            $this->display('Public:b_stop');
            return;
        }        
        $currency_id=I('get.currency');
        $currency=M('Currency')->where("currency_mark='$currency_id' and is_line=1")->find();
        $currency['aa'] = ltrim($currency['detail_url']," https:// ");
        if(empty($currency)){
            $this->display('Public:b_stop');
            return;
        }
        
        $currency['currency_digit_num']=$currency['currency_digit_num']?$currency['currency_digit_num']:4;//设置限制位数
        //显示委托记录
        $buy_record=$this->getOrdersByType($currency['currency_id'],'buy', 11, 'desc');
        foreach($buy_record as $k=>$v){
        	$buy_record[$k]['not_trade_num'] = sprintf("%.4f",substr(sprintf("%.3f", $buy_record[$k]['num']-$buy_record[$k]['trade_num']), 0, -2));
        }
//         dump($buy_record);
        $sell_record=$this->getOrdersByType($currency['currency_id'],'sell', 11, 'asc');
        foreach($sell_record as $k=>$v){
        	$sell_record[$k]['not_trade_num'] = sprintf("%.4f",substr(sprintf("%.3f", $sell_record[$k]['num']-$sell_record[$k]['trade_num']), 0, -2));
        }
        $this->assign('buy_record',$buy_record);
        $this->assign('sell_record',$sell_record);
        //格式化手续费
        $currency['currency_sell_fee']=floatval($currency['currency_sell_fee']);
        $currency['currency_buy_fee']=floatval($currency['currency_buy_fee']);
        //币种信息
        $currency_message=$this->getCurrencyMessageById($currency['currency_id']);
        
        //判断是否是主币
        if($currency['trade_currency_id']!=0){
        	//获取主币信息
        	$zhu = M('Currency')->where(['trade_currency_id'=>0])->find();
        	$zhu_message = $this->getCurrencyMessageById($zhu['currency_id']);
        	$bili = $zhu_message['new_price']*$currency_message['new_price'];
        	$this->assign('bili',$bili);
        }
		//dump($zhu);
		//dump($currency_message);
       // dump($bili);
//         die();
		//调用买一卖一方法去写
		$min_order =$this->getOneOrdersByPrice($currency['currency_id'], 'sell');
		$max_order =$this->getOneOrdersByPrice($currency['currency_id'], 'buy');
		
		$currency_message['buy_one_price'] = $max_order;
		$currency_message['sell_one_price'] = $min_order;
        $currency_trade=$this->getCurrencynameById($currency['trade_currency_id']);
		//总成交额  从文件里读取
			$trade_total = M('Trade_'.$currency['currency_id'])->where("currency_id={$currency['currency_id']}")->sum('money');
			$currency_message['trade_total'] = number_format($trade_total,3);
// 		dump($currency_message);
// 		die();
		//涨跌停查询
		$price_time = strtotime(date('Y-m-d',time()));
		$count_price['add_time'] = array('lt',$price_time);
		$count_price['currency_id'] = $currency['currency_id'];
		$price_last = M('Trade_'.$currency['currency_id'])->Field('price')->where($count_price)->order('add_time desc')->find();
		$currencys=$this->getCurrencyByCurrencyId($currency['currency_id']);
		
		
		$down = ( ( 1 - $currencys['price_down']/100) * $price_last['price']  );
		$up = ( ( 1 + $currencys['price_up']/100) * $price_last['price']  );
		
		$this->assign('up',$up);
		$this->assign('down',$down);
		
 		//dump($currency_message);die();
        $this->assign('currency_message',$currency_message);
        $this->assign('currency_trade',$currency_trade);
        $TLogin=new TradeLogic($currency['currency_id']);
        $tlfield="type,price,num,add_time";
        //个人账户资产
        if (!empty($_SESSION['USER_KEY_ID'])){
        	//个人资金优化 ，getuserallmoney
			$user_info_money = $this->getuserallmoney($currency['currency_id']);
            //dump($user_info_money);die();
			$user_currency_money['currency']['num'] = $user_info_money['num'];
			$user_currency_money['currency']['forzen_num'] = $user_info_money['forzen_num'];
		    $user_info_trade_money = $this->getuserallmoney($currency['trade_currency_id']);
            $user_currency_money['currency_trade']['num']=$user_info_trade_money['num'];
            $user_currency_money['currency_trade']['forzen_num']=$user_info_trade_money['forzen_num'];
            if($currency['trade_currency_id']==0){
                $user_currency_money['currency_trade']['num']=$this->member['rmb'];
                $user_currency_money['currency_trade']['forzen_num']=$this->member['forzen_rmb'];
            }
            $this->assign('user_currency_money',$user_currency_money);
            //个人挂单记录
            $user_orders = $this->getOrdersByUser(5,$currency['currency_id']);
            //dump($user_orders);
            foreach($user_orders as $k=>$v){
            	$user_orders[$k]['not_trade_num'] = sprintf("%.4f",substr(sprintf("%.3f", $user_orders[$k]['num']-$user_orders[$k]['trade_num']), 0, -2));
            }
            $this->assign('user_orders',$user_orders);
            //最大可买
            if (!empty($sell_record)){
            		  $buy_num=sprintf('%.4f',$user_currency_money['currency_trade']['num']/$sell_record[0]['price']);
            }else {
                $buy_num=0;
            }
			
            $this->assign('buy_num',$buy_num);
			//个人成交记录
			$mytwhere['member_id']=$_SESSION['USER_KEY_ID'];
            $chengjiao=  $TLogin->getTradeALLByUid($tlfield,10,$mytwhere);

			$this->assign('chengjiao',$chengjiao);
			
            //最大可卖
            $sell_num=sprintf('%.4f',$user_currency_money['currency_trade']['num']);
            //dump($user_currency_money);die();
            $this->assign('sell_num',$sell_num);
        }
		//优化半分比显示
        $this->assign('session',$_SESSION['USER_KEY_ID']);
        $this->assign('currency',$currency);
		
        //成交记录
      
        //这块是有问题的，最新成交记录里，还要带上部分成交的
	    //$only_buy['type'] = 'buy'; 
   	    //$trade=  $TLogin->getTradeALLByUid($tlfield,20,$only_buy);
   	   $trade=  $TLogin->getTradeALLByUid($tlfield,20);

        $this->assign('trade',$trade);
		
		
		
// 		dump($currency);
// 		die();
        //$this->display('index_new');
        $this->display();
    }
	
	/**
	*个人资金优化  只有上面调用
	*/
	private function getuserallmoney($currency_id){
		if (empty($currency_id)){
            $data['forzen_num'] = $this->member['forzen_rmb'];
            $data['num'] = $this->member['rmb'];
         }else {
             $data= M('Currency_user')->Field('forzen_num,num')->where("member_id={$this->member['member_id']} and currency_id=$currency_id")->find();
         }
        return $data;
	}
	
	
    //交易大厅
    public function currency_trade(){
        $count = M('Currency')->where('is_line=1')->count();//根据分类查找数据数量
        $page = new \Think\Page($count,10);//实例化分页类，传入总记录数和每页显示数
        $show = $page->show();//分页显示输出性
        $currency = M('Currency')->where('is_line=1')->order('sort')->limit($page->firstRow.','.$page->listRows)->select();//时间降序排列，越接近当前时间越高
        foreach ($currency as $k=>$v){
            $list=$this->getCurrencyMessageById($v['currency_id']);
            $currency[$k]=array_merge($list,$currency[$k]);
			$trade_total = M('Trade_'.$v['currency_id'])->where("currency_id={$v['currency_id']}")->sum('money');
			$currency[$k]['currency_trade_all'] = $trade_total;
			
			$list=$this->getCurrencyMessageById($v['currency_id']);
			$currency[$k]=array_merge($list,$currency[$k]);
			$list['new_price']?$list['new_price']:0;
			$currency[$k]['currency_all_money'] = floatval($v['currency_all_num'])*$list['new_price'];
			$res=$this->getCurrencyMessageById($v['trade_currency_id']);
			$currency[$k]['biaoshi'] = $res['currency_mark'];
        }
        $this->assign('page',$show);
        $this->assign('currency',$currency);
        $this->display();
    }
    
    //获取挂单记录
    public function getOrders(){
        switch (I('post.type')){
          case 'buy':  $this->ajaxReturn($this->getOrdersByType(I('post.currency_id'),'buy', 11, 'desc'));
          break;
          case 'sell':$this->ajaxReturn($this->getOrdersByType(I('post.currency_id'),'sell', 11, 'asc'));
          break;
        }
    }
    //获取k线
    public function getOrdersKline(){
        if(empty($_GET['currency'])){
            return;
        }
        $currency_id=I('get.currency');
        //K线
        $char=!empty($_GET['time'])?I('get.time'):'kline_1h';
        switch ($char){
            case 'kline_5m':$time=5;break;
            case 'kline_15m':$time=15;break;
            case 'kline_30m':$time=30;break;
            case 'kline_1h':$time=60;break;
            case 'kline_8h':$time=480;break;
            case 'kline_1d':$time=24*60;break;
            default:$time=60;
        }
//        	$data[$char] = $this ->test($time,$currency_id,$char);
//         $data[$char] = $this ->getCurl($currency_id,$char);
       	// $data[$char]=$this->getKline($time,$currency_id);
        $data[$char]=$this->getKlineNew($time,$currency_id);
        $this->ajaxReturn($data);
    }
    
    /**
     * /**
     * 获取K线
     * @param unknown $base_time	获取时间 （分钟）
     * @param unknown $currency_id	币种id
     * @return Ambigous <number, unknown>
     */
    private function getKlineNew($base_time,$currency_id){
	    //K线添加10秒缓存
	    $kline = S('kline_'.$currency_id.'_'.$base_time);
	    if($kline){
	    	return $kline ;
	    }
		//查询表
    	$Trade = M('Trade_'.$currency_id);
		//获取查询最小值(当前时间 - 公共时间*60秒* 100条数据)
    	$time=time()-$base_time*60*60;
    	//循环处理K线
    	for ($i=0;$i< 60;$i++){
    		//查询开始时间
    		$start= $time+$base_time*60*$i;
    		//查询结束时间
    		$end=$start+$base_time*60;
    		//获取K线时间
    		$item[$i][]=$start*1000+8*3600*1000;
    		//查询条件
    		$where['currency_id']=$currency_id;
    		$where['type']='buy';
    		$where['add_time']=array('between',array($start,$end));
    		//交易量
    		$num= $Trade->where($where)->sum('num');
    		$num = $this ->del0($num);
    		$item[$i][]=!empty($num)?floatval($num):floatval(0);
    		//开盘
    		//获取当前时间段里第一条数据
    		$open = $Trade ->field('price')->where($where)->order('add_time asc')->find();
    		$open = $this ->del0($open['price']);
    		$item[$i][]=!empty($open)?floatval($open):floatval(0);
    		//最高
    		$max=$Trade->where($where)->max('price');
    		$max = $this ->del0($max);
    		$max=!empty($max)?floatval($max):$open;
    		$max=!empty($max)?$max:0;
    		$item[$i][]=$max;
    		//最低
    		$min=$Trade->where($where)->min('price');
    		$min = $this ->del0($min);
    		$min=!empty($min)?floatval($min):$open;
    		$item[$i][]=!empty($min)?$min:0;
    		//收盘
    		$order_s= $Trade->field('price')->where($where)->order('add_time desc')->find();
    		$order_s = $this ->del0($order_s['price']);
    		$order_s=!empty($order_s)?floatval($order_s):$open;
    		$item[$i][]=!empty($order_s)?$order_s:0;
    	}
    	//添加K线缓存
    	S('kline_'.$currency_id.'_'.$base_time,$item,10);
    	return $item;
    }
     //获取K线
    private function getKline($base_time,$currency_id){
            $time=1487066555-$base_time*60*60;
            for ($i=0;$i<60;$i++){
             $start= $time+$base_time*60*$i;
             $end=$start+$base_time*60;
            //时间
            $item[$i][]=$start*1000+8*3600*1000;
            $where['currency_id']=$currency_id;
            $where['type']='buy';
            $where['add_time']=array('between',array($start,$end));
     
            //交易量
          $num=M('Trade_'.$currency_id)->where($where)->sum('num');
          $item[$i][]=!empty($num)?floatval($num):0;
            //开盘
           // $where_price['currency_id']=$currency_id;
            $where_price['type']='buy';
            $where_price['add_time']=array('elt',$end);
          
            $order_k=M('Trade_'.$currency_id)->field('price')->where($where_price)->order('add_time desc')->find();
            $item[$i][]=!empty($order_k['price'])?floatval($order_k['price']):0;
            //最高
           $max=M('Trade_'.$currency_id)->where($where)->max('price');
           $max=!empty($max)?floatval($max):$order_k['price'];
           $max=!empty($max)?$max:0;
           $item[$i][]=$max;
            //最低
            $min=M('Trade_'.$currency_id)->where($where)->min('price');
            $min=!empty($min)?floatval($min):$order_k['price'];
            $item[$i][]=!empty($min)?$min:0;
            //收盘
            $order_s=M('Trade_'.$currency_id)->field('price')->where($where)->order('add_time asc')->find();
            $order_s=!empty($order_s['price'])?floatval($order_s['price']):$order_k['price'];
            $item[$i][]=!empty($order_s)?$order_s:0;
        }
       // $item=json_encode($item,true);
        return $item;
    }
}
