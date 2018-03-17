<?php
/**
 */

namespace Home\Controller;

use Common\Controller\CommonController;

class MarketController extends CommonController {
	
	public function index2(){
		$this->assign("id",$_GET['currency_id']);
		$this->display();
	}
	
	public function server(){
		header('Access-Control-Allow-Origin: *');
		$type = $_POST['type'];
		// 	'zh-cn': {'line':'(分时)','0':'(1分钟)','1':'(5分钟)','2':'(15分钟)','9':'(30分钟)','10':'(1小时)','3':'(日线)','4':'(周线)'
		// 			,'7':'(3分钟)', '11':'(2小时)','12':'(4小时)','13':'(6小时)','14':'(12小时)','15':'(3天)'},
		$id=I('get.id',"");
		switch ($type){
			case 0:
				$type = '1min';$time=1;
				break;
			case 1:
				$type = '5min';$time=5;
				break;
			case 2:
				$type = '15min';$time=15;
				break;
			case 9:
				$type = '30min';$time=30;
				break;
			case 10:
				$type = '1hour';$time=60;
				break;
			case 3:
				$type = '1day';$time=60*24;
				break;
			case 4:
				$type = '1week';$time=60*24*7;
				break;
			case 7:
				$type = '3min';$time=3;
				break;
			case 11:
				$type = '2hour';$time=60*2;
				break;
			case 12:
				$type = '4hour';$time=60*4;
				break;
			case 13:
				$type = '6hour';$time=60*6;
				break;
			case 14:
				$type = '12hour';$time=60*12;
				break;
			default:
				$type = 0;$time=15;
				break ;
		}
		$data=$this->getOrdersKline($id, $time);
// 		$re =file_get_contents("https://www.okcoin.cn/api/v1/kline.do?symbol=btc_cny&type=".$type."&size=100");
// 		echo $re;
		$this->ajaxReturn($data);
		//echo json_encode($data);
// 		echo file_get_contents("https://www.okcoin.cn/api/v1/kline.do?symbol=btc_cny&type=".$type."&size=100");
	}
	
	
	
	public  function ssss(){
// 		M("Trade")->where("currency_id = 26")->delete();
		for ($i=0;$i<100;$i++){
			$data['currency_id']=25;
			$data['add_time']=time()-$i*30;
			$data['price']=rand(100.01,5000.99)/100;
			$data['num']=rand(1, 50);
			$data['member_id']=59;
			if($data['num']>25){
				
				$data['type']='buy';
			}else{
				$data['type']='sell';
				
			};
		
			M("Trade")->add($data);
		}
	}

	//获取k线
	private function getOrdersKline($id,$time){
		if(empty($time)){
			return;
		}
		if(empty($id)){
			return;
		}
		$currency_id=$id;
		//K线

		$data=$this->getKline1($time,$currency_id);
		return $data;
// 		$this->ajaxReturn($data);
	}
	
	//获取K线
	public function getKline1($base_time,$currency_id){
// 		$base_time=$base_time;//30分钟
			$ss = S('Kline_market'.$currency_id.$base_time);
			
			if(!empty($ss)){
				return $ss;
			}
			
			$time=time()-$base_time*100*60;//当前时间-30分钟*300*60
			for ($i=0;$i<100;$i++){
				$start= $time+$base_time*60*$i;//开始时间=当前时间-30分钟*300*60+30分钟*60*i
				$end=$start+$base_time*60;//结束时间=当前时间-30分钟*300*60+30分钟*60*i+30分钟*60
				//时间
				$item[$i][]=($start)*1000;
				$where['currency_id']=$currency_id;
				$where['type']='buy';
				$where['add_time']=array('between',array($start,$end));
				//开盘
				$where_price['currency_id']=$currency_id;
				$where_price['type']='buy';
				$where_price['add_time']=array('elt',$end);
				 
				$order_k=M('Trade')->field('price')->where($where_price)->order('add_time desc')->find();
				$item[$i][]=!empty($order_k['price'])?floatval($order_k['price']):0;
	
				//最高
				$max=M('Trade')->where($where)->max('price');
				$max=!empty($max)?floatval($max):$order_k['price'];
				$max=!empty($max)?$max:0;
				$item[$i][]=floatval($max);
				//最低
				$min=M('Trade')->where($where)->min('price');
				$min=!empty($min)?floatval($min):$order_k['price'];
				$item[$i][]=!empty($min)?floatval($min):0;
				//收盘
				$order_s=M('Trade')->field('price')->where($where)->order('add_time asc')->find();
				$order_s=!empty($order_s['price'])?floatval($order_s['price']):$order_k['price'];
				$item[$i][]=!empty($order_s)?floatval($order_s):0;
	
				//交易量
				$num=M('Trade')->where($where)->sum('num');
				// 	            dump(M('Trade')->_sql());
				$item[$i][]=!empty($num)?floatval($num):0;
			}
	
		// $item=json_encode($item,true);
		
		S('Kline_market'.$currency_id.$base_time,$item,$base_time);
		
		return $item;
	}
	
	
	//获取K线
	private function getKline($base_time,$currency_id){
		$time=time()-$base_time*60*100;
		for ($i=0;$i<100;$i++){
			$start= $time+$base_time*(60*$i);
			$end=$start+$base_time*60;
			//时间
			$item[$i][]=$start*1000;
			$where['currency_id']=$currency_id;
			$where['type']='buy';
			$where['add_time']=array('between',array($start,$end));
			 
			//交易量
			$num=M('Trade')->where($where)->sum('num');
			$item[$i][]=!empty($num)?floatval($num):1;
			//开盘
			$where_price['currency_id']=$currency_id;
			$where_price['type']='buy';
			$where_price['add_time']=array('elt',$end);
	
			$order_k=M('Trade')->field('price')->where($where_price)->order('add_time desc')->find();
			$item[$i][]=!empty($order_k['price'])?floatval($order_k['price']):0;
			//最高
			$max=M('Trade')->where($where)->max('price');
			$max=!empty($max)?floatval($max):$order_k['price'];
			$max=!empty($max)?$max:1;
			$item[$i][]=floatval($max);
			//最低
			$min=M('Trade')->where($where)->min('price');
			$min=!empty($min)?floatval($min):$order_k['price'];
			$item[$i][]=!empty($min)?floatval($min):1;
			//收盘
			$order_s=M('Trade')->field('price')->where($where)->order('add_time asc')->find();
			$order_s=!empty($order_s['price'])?floatval($order_s['price']):$order_k['price'];
			$item[$i][]=!empty($order_s)?floatval($order_s):1;
		}
// 		$item=json_encode($item,true);
		return $item;
	}
	
	
	public function server2(){
		$this->Trans=M("Trade");
		$coinid = intval($_GET['id']);
		$start_time = $this->Trans->where(array('currency_id'=>$coinid))->order('add_time asc')->getField('add_time');
	
		$type = intval($_POST['type']);
		$limit = intval($_POST['limit']);
	
		$type = chkNum($type) ? $type : 2;
	
		$time_para = array();
		$time_para[0] = 60;
		$time_para[1] = 5*60;
		$time_para[2] = 15*60;
		$time_para[3] = 24*60*60;
		$time_para[4] = 7*24*60*60;
		$time_para[7] = 3*60;
		$time_para[9] = 30*60;
		$time_para[10] = 60*60;
		$time_para[11] = 2*60*60;
		$time_para[12] = 4*60*60;
		$time_para[13] = 6*60*60;
		$time_para[14] = 12*60*60;
	
		$line = $time_para[$type];
		$step = 100;
		$startMonth = (time() - time() % $line) - $step * $line;
		if($startMonth < $start_time) $startMonth = $start_time;
	
		$data = array();
	
		$first = 0;
		for($i=1; $i<=$step; $i++){
	
			$start = $startMonth+($i-1)*$line;
			$end = $startMonth+$i*$line;
			unset($map);
	
			$map['ctime'] = array('between',array($start,$end));
			$map['currency_id'] = $coinid;
	
			$open = $this->Trans->where($map)->order('add_time asc')->getField('price');
			$close = $this->Trans->where($map)->order('add_time desc')->getField('price');
			$high = $this->Trans->where($map)->max('price');
			$low = $this->Trans->where($map)->min('price');
			$sum = $this->Trans->where($map)->sum('num');
	
			if(!chkNum($open)){
				$high = $low = $close = $open = $last_close;
			}else{
				$last_close = $close;
			}
	
			$time = $start+$i*$line;
				
			$data[] = array($time*1000,floatval($open),floatval($high),floatval($low),floatval($close),floatval($sum));
		}
		echo json_encode($data);
	}
	
	
	
    /**
     * 查看行情
     */
  public function index(){
  	
  		if(!empty($_GET['coin'])){
  			$whereMark['currency_mark'] = I('get.coin');
  		}
  		
  		$whereMark['is_line']=1;
  		$liCurrency = M('Currency')->field('currency_id,currency_name,currency_logo,currency_mark')->where($whereMark)->order('sort')->find();
  		
  		//判断 有没有 可以交易的币种
  	 	if(empty($liCurrency)){
  	 		$this -> error('交易币种正在紧张筹备中！敬请期待',U('Index/index'));
  	 	}
  	 	
  	 	$wheretrade['currency_id'] = $liCurrency['currency_id'] ;
  	 	//获取成交最大值
  	 	$liCurrency['maxPrice'] = M('Trade_'.$liCurrency['currency_id']) ->where($wheretrade)->max('price');
  	 	//获取成交最小值
  	 	$liCurrency['minPrice'] = M('Trade_'.$liCurrency['currency_id']) ->where($wheretrade)->min('price');
  	 	// 获取交易量
  	 	$liCurrency['countNum'] = M('Trade_'.$liCurrency['currency_id']) ->where($wheretrade)->sum('num');
  	 	//最新价格
  	 	$liCurrency['newPrice'] = $this -> getNewPriceByCurrencyid($liCurrency['currency_id']);
  	 	$buyOrder['type'] = "buy";
  	 	//获取买一价
  	 $liCurrency['buyPrice']=$this->getOneOrdersByPrice($liCurrency['currency_id'], 'buy');
  	 	//获取卖一价
  	 	 $liCurrency['sellPrice']=$this->getOneOrdersByPrice($liCurrency['currency_id'], 'sell');
  	 	//成交盘
  	 	$Deal = M('Trade_'.$liCurrency['currency_id'])->where($wheretrade)->order('add_time desc')->limit(30)->select();
  	 	
  	 
  	 	//买卖盘   买
  		 $sell=$this->getOrdersByType($liCurrency['currency_id'], 'buy', 20, 'desc');
  		 // 页面显示 成交量背景 比例
  	 	 foreach ($sell as $k=>$v){
			$sell[$k]['bili']=100-intval(($v['trade_num']/$v['num'])*100)."%";
		 }
  	 
  	 	//买卖盘   卖
  		 $buy =$this->getOrdersByType($liCurrency['currency_id'], 'sell', 20, 'asc');
  		 $buy=  array_reverse($buy);
  		 // 页面显示 成交量背景 比例
  	 	 foreach ($buy as $k=>$v){
  	 		$buy[$k]['bili']=100-intval(($v['trade_num']/$v['num'])*100)."%";
  	 	 }
  	 	
  	 	//查询其他交易币  去掉当前 币种
  	 	$where['currency_id'] =  array('NEQ',$liCurrency['currency_id']);
  	 	$where['is_line']=1;
  	 	$listCurrency = M('Currency')->field('currency_id,currency_name,currency_logo,currency_mark')->where($where)->select();
  	 	foreach($listCurrency as $k =>$v){
  	 		$listCurrency[$k]['newPrice'] = $this -> getNewPriceByCurrencyid($v['currency_id']);
  	 	}
  	 	
  	 	$this ->assign('count',max(count($sell),count($buy)));
  	 	$this ->assign('deal',$Deal);
  	 	$this ->assign('sell',$sell);
  	 	$this ->assign('buy',$buy);
  	 	$this ->assign('listCurrency',$listCurrency);
  	 	$this ->assign('liCurrency',$liCurrency);
  		$this->display();
  }
  
  /**
   *  获取最新交易价格
   * @param unknown $type    币种id
   * @return unknown|number  
   */
  public function getNewPriceByCurrencyid($currency_id){
  		$where['currency_id'] =$currency_id;
  		$list = M('Orders')->Field('price')->where($where)->field('price')->order('add_time desc')->find();
  		
  		if(!empty($list)){
  			return $list['price'];
  		}else{
  			return  0;
  		}
  }
  
  public function getMarket(){
      $where['status']=array('in',array(0,1));
      $list=M('Orders')->field('price')->where($where)->group('price')->select();
      $price_arr=array();
      $buy_arr=array();
      $sell_arr=array();
      foreach ($list as $k=>$v){
            $sell=M('Orders')->field('sum(num) as num')->where($where)->where("type='sell' and price='{$v['price']}'")->select();
            $buy=M('Orders')->field('sum(num) as num')->where($where)->where("type='buy' and price='{$v['price']}'")->select();
            $list[$k]['sell']=!empty($sell[0]['num'])?$sell[0]['num']:0;
            $list[$k]['buy']=!empty($buy[0]['num'])?$buy[0]['num']:0;
      }
      foreach ($list as $k=>$v){
          $price_arr[]=floatval($v['price']);
          $sell_arr[]=floatval($v['sell']);
          $buy_arr[]=floatval($v['buy']);
      }
      $data['price']=$price_arr;
      $data['sell']=$sell_arr;
      $data['buy']=$buy_arr;
     $this->ajaxReturn($data);
  }
  
  
  //获取买卖单分补
  private function getOrdersMarket($type,$currency_id){
       $where['currency_id']=$currency_id;
       $where['type']=$type;
      $max_pirce=M('Orders')->where($where)->max('price');
      $min_price=M('Orders')->where($where)->min('price');
      $units=intval(($max_pirce-$min_price)/10);
      for ($i=0;$i<10;$i++){
            $arr_price[]=$min_price+$units*$i;  
      }
      $start=$min_price;
      $end=$min_price+$units;
      
  }
  
  
}  