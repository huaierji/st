<?php

namespace Common\Logic;
class TradeLogic{
	protected $table="Trade";
	public function __construct($currency_id){
		$this->table.="_".$currency_id;
// 		dump($this->table);
	}
	
	/**
	 * 查询集合
	 * @param array $where 数组形式处理
	 * @return array 成功返回数组，失败返回false
	 */
	public function getTradeALLByUid($field,$limit,$where=""){
		
		$re=M($this->table)->field($field)->where($where)->order("trade_id desc")->limit($limit)->select();
		return $re;
	}
	
	/**
	 * 添加一条交易记录
	 * @param array data 数组格式
	 * @return boolean 成功返回id值，失败返回false
	 */
	public function addTradeOne($member_id,$currency_id,$currency_trade_id,$price,$num,$type,$fee){
		$fee=$price*$num*$fee;
		$data=array(
				'member_id'=>$member_id,
				'currency_id'=>$currency_id,
				'currency_trade_id'=>$currency_trade_id,
				'price'=>$price,
				'num'=>$num,
				'fee'=>$fee,
				'money'=>$price*$num,
				'type'=>$type,
				'status'=>0,
				'add_time'=>time(),
				'trade_no'=>'T'.time(),
				
		);
		$re=M($this->table)->add($data);
		return $re;
	}
	
// 	/**
// 	 * 查看所有数据
// 	 */
// 	public function getTradeAllView($where=array()){
// 		$tradeall=M("Tradeall");
// 		$count      = $tradeall->where($where)->count();// 查询满足要求的总记录数
// 		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
// 		$show       = $Page->show();// 分页显示输出
// 		foreach ($where as $k=> $v){
// 			if (isset($v)){
// 				$Page->parameter[$k]=$v;
// 			}
// 		}
// 		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
// 		$list = $tradeall->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
// 		$data['list']=$list;
// 		$data['page']=$show;
// 		return $data;
// 	}
	/*
	创建视图语句  创建成交记录
	CREATE VIEW yang_tradeall as 
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_0 ) union ALL
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_1 ) union ALL 
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_2 ) union ALL
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_3 ) union ALL
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_4 ) union ALL
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_5 ) union ALL
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_6 ) union ALL
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_7 ) union ALL
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_8 ) union ALL
	(SELECT trade_no,member_id,currency_id,currency_trade_id,price,num,money,fee,type,add_time,status FROM yang_trade_9 ) ;
	 */
}