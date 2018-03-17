<?php

namespace Common\Logic;
class FinanceLogic{
	protected $table="Finance";
	public function __construct($uid){
		$this->table.="_".$uid%10;
		dump($this->table);
	}
	
	/**
	 * 查询集合
	 * @param array $where 数组形式处理
	 * @return array 成功返回数组，失败返回false
	 */
	public function getFinanceALLByUid($where=array()){
		$re=M($this->table)->where($where)->select();
		return $re;
	}
	
	/**
	 * 添加一条交易记录
	 * @param array data 数组格式
	 * @return boolean 成功返回id值，失败返回false
	 */
	public function addFinanceOne($data){
		$re=M($this->table)->add($data);
		return $re;
	}
	
	/**
	 * 查看所有数据
	 */
	public function getFinanceAllView($where=array()){
		$tradeall=M("Financeall");
		$count      = $tradeall->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		foreach ($where as $k=> $v){
			if (isset($v)){
				$Page->parameter[$k]=$v;
			}
		}
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $tradeall->where($where)->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$data['list']=$list;
		$data['page']=$show;
		return $data;
	}
	/*
	创建视图语句  创建成交记录
	CREATE VIEW yang_financeall as 
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_0 ) union ALL
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_1 ) union ALL 
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_2 ) union ALL
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_3 ) union ALL
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_4 ) union ALL
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_5 ) union ALL
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_6 ) union ALL
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_7 ) union ALL
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_8 ) union ALL
	(SELECT member_id,type,content,money_type,money,add_time,currency_id,ip FROM yang_finance_9 ) ;
	 */
}