<?php
namespace Home\Controller;
use Think\Controller;
use Common\Logic\TradeLogic;
class NocommonController extends Controller{
    // public function _initialize(){
        // parent::_initialize();
    // }
	
	
	/*************这段是交易页面显示挂单列表功能************/
	   /**
      * 返回指定数量排序的挂单记录
      * @param char $type buy sell
      * @param int $num 数量
      * @param char $order 排序 desc asc
      */
      /*protected function getOrdersByType($currencyid,$type,$num,$order){
         $where['type']=array('eq',$type);
         $where['status']=array('in',array(0,1));
         $where['currency_id']=$currencyid;
         $list= M('Orders')->field("sum(num) as num,sum(trade_num) as trade_num,price,type,status")->where($where)->group('price')->order("price $order, add_time asc")->limit($num)->select();
         foreach ($list as $k=>$v){
             $list[$k]['bili']=100-($v['trade_num']/$v['num']*100);
         }
         if ($type=='sell'){
           $list=  array_reverse($list);
         }
         return $list;       
     }*/ 
        protected function getOrdersByType($currencyid,$type,$num,$order){
            $key=$type.'_'.time().'_'.$currencyid;
                if(S($key)!=null){
                    $list=S($key);
                }
                else{
                    $where['type']=array('eq',$type);
                    $where['status']=array('in',array(0,1));
                    $where['currency_id']=$currencyid;
                    $list= M('Orders')->field("sum(num) as num,sum(trade_num) as trade_num,price")->where($where)->group('price')->order("price $order")->limit($num)->select();            
                foreach ($list as $k=>$v){
                    $list[$k]['bili']=100-($v['trade_num']/$v['num']*100);
                }
                if ($type=='sell'){
                   $list=  array_reverse($list);
                }           
                S($key,$list,60);
                }
            return $list;
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
	
	/*************这段是交易页面显示挂单列表功能************/
	 
}
