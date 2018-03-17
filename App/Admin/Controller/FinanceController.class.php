<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
use Think\Page;
class FinanceController extends AdminController{
    //空操作
    public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }
    //财务日志
	public function index(){
        $addTime = I('addTime');
        $endTime = I('endTime');
        $addTime = str_replace('+',' ',$addTime );
        $endTime = str_replace('+',' ',$endTime );
        $addTime=empty($addTime)?0:strtotime($addTime);//开始时间
        $endTime=empty($endTime)?0:strtotime($endTime);//结束时间
        if(!empty($addTime) && empty($endTime)){
            $where[C("DB_PREFIX")."finance.add_time"] = array('egt',$addTime);
        }else if (empty($addTime) && !empty($endTime)){
            $where[C("DB_PREFIX")."finance.add_time"] = array('elt',$endTime);
        }else if (!empty($addTime) && !empty($endTime)){
            $where[C("DB_PREFIX")."finance.add_time"] = array('between',$addTime.','.$endTime);
        }else {
            $where = array();
        }


		$type_id=I('type_id');
		$name=I('name');
		$member_id=I('member_id');
		$table_p = $member_id%10;
		if(!empty($name)){
			$member=M('Member')->where("name like '%{$name}%'")->find();
			$id = $member['member_id'];
			if(!empty($member_id)){
				if($member_id != $id){
					$this->error('请确认要查询的用户名或ID');
				}
			}
			$table_p = $id%10;
			$where[C("DB_PREFIX")."member.member_id"]=$member['member_id'];
		}
		if(!empty($member_id)){
			$table_p = $member_id%10;
			$where[C("DB_PREFIX")."member.member_id"]=$member_id;
		}
		$post = array('type_id'=>$type_id,'name'=>$name,'member_id'=>$member_id);
// 		if(!empty($type_id)){
// 			$where['type']=$type_id;
// 		}
// 		if(!empty($member_id)){
// 			$where[C("DB_PREFIX")."member.member_id"]=$member_id;
// 	   }else{
// 		   $where[C("DB_PREFIX")."member.member_id"]=0;
// 	   }
// 		if(!empty($name)){
// 			$uid=M('Member')->where("name like '%{$name}%'")->find();
// 			$where[C("DB_PREFIX")."member.member_id"]=$uid['member_id'];
// 			$table_p = $uid['member_id']%10;
// 		}
        
		//筛选
        $type=M('Finance_type')->Field('id,name,status')->select();
        $this->assign('type',$type);
		//显示日志
        $count = M('Finance_'.$table_p)
        ->field(C("DB_PREFIX")."finance_".$table_p.".*,".C("DB_PREFIX")."member.name as username,".C("DB_PREFIX")."currency.currency_name,".C("DB_PREFIX")."finance_type.name as typename")
        ->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id=".C("DB_PREFIX")."finance_".$table_p.".member_id")
        ->join("left join ".C("DB_PREFIX")."finance_type on ".C("DB_PREFIX")."finance_type.id=".C("DB_PREFIX")."finance_".$table_p.".type")
        ->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id=".C("DB_PREFIX")."finance_".$table_p.".currency_id")
        ->where ( $where )->count (); // 查询满足要求的总记录数
        $Page = new Page ( $count, 25 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        //给分页传参数
        setPageParameter($Page, array('type_id'=>$type_id,'name'=>$name,'member_id'=>$member_id,'addTime'=>I('addTime'),'endTime'=>I('endTime')));
        
        $show = $Page->show (); // 分页显示输出
        		
		$list=M('Finance_'.$table_p)
		->field(C("DB_PREFIX")."finance_".$table_p.".*,".C("DB_PREFIX")."member.name as username,".C("DB_PREFIX")."currency.currency_name,".C("DB_PREFIX")."finance_type.name as typename")
		->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id=".C("DB_PREFIX")."finance_".$table_p.".member_id")
		->join("left join ".C("DB_PREFIX")."finance_type on ".C("DB_PREFIX")."finance_type.id=".C("DB_PREFIX")."finance_".$table_p.".type")
		->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id=".C("DB_PREFIX")."finance_".$table_p.".currency_id")
		->limit($Page->firstRow.','.$Page->listRows)
		->where($where)
		->order('add_time desc')
		->select();
// 		dump(M('Finance_'.$table_p)->_sql());
// 		die();
		//echo M('Finance')->_sql();
		foreach ($list as $k=>$v){
			if($v['currency_id']==0){
				$list[$k]['currency_name']='人民币';
			}
		}
		$moneyNum = M('Finance_'.$table_p)
            ->field(C("DB_PREFIX")."finance_".$table_p.".*,".C("DB_PREFIX")."member.name as username,".C("DB_PREFIX")."currency.currency_name,".C("DB_PREFIX")."finance_type.name as typename")
            ->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id=".C("DB_PREFIX")."finance_".$table_p.".member_id")
            ->join("left join ".C("DB_PREFIX")."finance_type on ".C("DB_PREFIX")."finance_type.id=".C("DB_PREFIX")."finance_".$table_p.".type")
            ->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id=".C("DB_PREFIX")."finance_".$table_p.".currency_id")
            ->where($where)
            ->sum('money');
			
		
		//$this->assign('xiaji',$xiaji);
		$this->assign('post',$post);
        $this->assign('moneyNum',$moneyNum);
		$this->assign('empty','暂未查询到数据');
		$this->assign('list',$list);
		$this->assign ( 'page', $show ); // 赋值分页输出
        $this->display();
     }
    //财务明细
    public function count(){
    	//$pay=$this->getFinenceByType(array('6'),1);
		$where['status'] = 1;
		$where['currency_id'] = 0;
		$where['type'] = array('in','1,2');
        $pay = M('Pay')->where( $where )->sum('money');
    	$this->assign('pay',$pay);
    	//$pay_admin=$this->getFinenceByType(array('3','13'),0);
		$pay_admin = M('Pay')->where( array('status'=>1,'currency_id'=>0,'type'=>3) )->sum('money');
    	$this->assign('pay_admin',$pay_admin);
    	//$draw=$this->getFinenceByType(array('23'),1);
		$draw = M('withdraw')->where(array('status'=>2))->sum('money');
    	$this->assign('draw',$draw);
    	//统计人民币总额
    	$rmb_count=M('Member')->sum('rmb');
		$this->assign('rmb_count',$rmb_count);
    	$forzen_rmb_count=M('Member')->sum('forzen_rmb');
		$this->assign('forzen_rmb_count',$forzen_rmb_count);
    	$rmb=$rmb_count+$forzen_rmb_count;
    	$this->assign('rmb',$rmb);
    	//分币种统计
    	$currency=M('Currency')->field('currency_id,currency_name')->select();
    	foreach ($currency as $k=>$v){
    		$currency_user[$k]['num']=M('Currency_user')->where('currency_id='.$v['currency_id'])->sum('num');
    		$currency_user[$k]['forzen_num']=M('Currency_user')->where('currency_id='.$v['currency_id'])->sum('forzen_num');
    		$currency_user[$k]['name']=$v['currency_name'];
    	}
    	$this->assign('currency_user',$currency_user);
    	$this->display();
    }
    private function getFinenceByType($type,$currency=1){
		if($currency==0){
			$where['currency_id']=0;
		}
    	$where['type']=array('in',$type);
    	$rs=M('Finance')->where($where)->sum('money');
    	return $rs;
    }
    /**
     * 导出excel文件
     */
    public function derivedExcel(){
    	//时间筛选
		
		$member_id = I('member_id');
		$table_p = $member_id%10;
		//dump($member_id);die;
		set_time_limit (0);
		ini_set('memory_limit', '1600M');
    	$add_time=I('get.add_time');
    	$end_time=I('get.end_time');
    	$add_time=empty($add_time)?0:strtotime($add_time);
    	$end_time=empty($end_time)?0:strtotime($end_time);
    	$where[C("DB_PREFIX").'finance_'.$table_p.'.add_time'] = array('between',array($add_time,$end_time));
		$where[C("DB_PREFIX").'finance_'.$table_p.'.member_id'] = $member_id;
    	//$where[C("DB_PREFIX").'finance.add_time'] = array('gt',$add_time);
		// dump($where);die;
		$count = M('Finance_'.$table_p)->where($where)->count();
		// dump($count);die;
		for($i=0;$i<=$count/10000;$i++){
			$list= M('Finance_'.$table_p)
						->field(C("DB_PREFIX")."finance_".$table_p.".finance_id,"
						.C("DB_PREFIX")."member.name as uesrname,"
						.C("DB_PREFIX")."finance_type.name as typename,"
						.C("DB_PREFIX")."finance_".$table_p.".content,"
						.C("DB_PREFIX")."finance_".$table_p.".money,"
						.C("DB_PREFIX")."currency.currency_name,"
						.C("DB_PREFIX")."finance_".$table_p.".money_type,"
						.C("DB_PREFIX")."finance_".$table_p.".add_time")
						->join("left join ".C("DB_PREFIX")."member on ".C("DB_PREFIX")."member.member_id=".C("DB_PREFIX")."finance_".$table_p.".member_id")
						->join("left join ".C("DB_PREFIX")."finance_type on ".C("DB_PREFIX")."finance_type.id=".C("DB_PREFIX")."finance_".$table_p.".type")
						->join("left join ".C("DB_PREFIX")."currency on ".C("DB_PREFIX")."currency.currency_id=".C("DB_PREFIX")."finance_".$table_p.".currency_id")
						
						->order('add_time desc')
						->where($where)
						//->where(C("DB_PREFIX").'finance.add_time>'.$add_time)
						->limit($i*10000,($i+1)*10000)
						->select();
						// dump($list);die;
			$dataa[] = $list;
		}
		
    	foreach($dataa as $k=>$v){
			foreach($v as $kk=>$vv){
				$lists[] = $vv;
			}
		}
		unset($dataa);
    	//     	echo M("Pay")->getLastSql();die;
    	//echo M('Finance')->_sql();die;
		
    	foreach ($lists as $k=>$v){
    		if($lists[$k]['money_type']==1){
    			$lists[$k]['money_type']='支出';
    		}else{
    			$lists[$k]['money_type']='收入';
    		}
    		if($lists[$k]['currency_id']==0)$list[$k]['currency_name']='人民币';
    		$lists[$k]['add_time']=date('Y-m-d H:i:s',$list[$k]['add_time']);
    	}
		
    	$title = array(
    			'日志编号',
    			'所属',
    			'财务类型',
    			'内容',
    			'金额',
    			'币种',
    			'收入/支出',
    			'时间',
    	);
    	$filename= $this->config['name']."财务日志-".date('Y-m-d',time());
    	$r = exportexcel($lists,$title,$filename);
    }
    
    
    
    
    public function aa(){
//     	$count = M('Finance')->count();
//     	dump($count);
//     	for($i=0;$i<=$count/10000;$i++){
//     		dump($i);
//     		echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~";
//     		$list[] = M('Finance')->limit($i*10000,($i+1)*10000)->select();
//     		dump($list);
//     	}
    	$User = M('Finance'); // 实例化User对象
    	$count      = $User->where()->count();// 查询满足要求的总记录数
    	$Page       = new \Think\Page($count,1000);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$show       = $Page->show();// 分页显示输出
    	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$list = $User->where()->order('finance_id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
//     	$this->assign('list',$list);// 赋值数据集
//     	$this->assign('page',$show);// 赋值分页输出
    	foreach ($list as $k=>$v){
    		//把数据分别转入分表中
    		$res[] = $this->bb($list[$k]);
    	}
    	dump($res);
    	if(in_array_case(false, $res)){
    		echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~";
    	}
// 		dump($list);    	
    }
    public function bb($list){
    	$id = $list['member_id']%10;
    	$re = M('Finance_'.$id)->add($list);
    	return $re;
    	
    }
    
    
    
    
    
    
}