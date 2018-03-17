<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class IndexController extends CommonController {
 	public function _initialize(){
 		parent::_initialize();
 	}
	//空操作
	public function _empty(){
		header("HTTP/1.0 404 Not Found");
		$this->display('Public:404');
	
	}
	
	
	public function index(){
		//取得选择币种信息
		//$currency=$this->currency();
		//$currency_select = $_SESSION['currency_id_select'] ? $_SESSION['currency_id_select'] : $currency[0]['currency_id'];
	
		//首页消息
		$souyexiaoxi = M('Config')->where(C("DB_PREFIX")."config.key='souyexiaoxi'")->find();
		// dump($souyexiaoxi['value']);
		// die;
		// cookie('souyexiaoxi',$souyexiaoxi['value']);
		// cookie('souyexiaoxi',null);
		
		$qian=array(" ","　","\t","\n","\r");  
		str_replace($qian, '', $str);   
		$souyexiaoxi1 = str_replace($qian, '', $souyexiaoxi['value']);   
		$this->assign('souyexiaoxi',$souyexiaoxi1);
		//咨询
		$art_model = M('Article');
		//官方公告
		if(S('gfgg_index')){
			$gfgg = S('gfgg_index');
		}else{
			$gfgg = $art_model->where("position_id = 1")->limit(8)->order('add_time desc')->select();
			S('gfgg_index',$gfgg);
		}
		$this->assign('gfgg',$gfgg);
		//市场动态
		if(S('scdt_index')){
			$scdt = S('scdt_index');
		}else{
			$scdt = $art_model->where("position_id = 2")->limit(8)->order('add_time desc')->select();
			S('scdt_index',$scdt);
		}
		
		$this->assign('scdt',$scdt);
		//媒体报道
		if(S('mtbd_index')){
			$mtbd = S('mtbd_index');
		}else{
			$mtbd = $art_model->where("position_id = 3")->limit(8)->order('add_time desc')->select();
			S('mtbd_index',$scdt);
		}
		$this->assign('mtbd',$mtbd);

		//幻灯
		if(S('flash')){
			$flash = S('flash');
		}else{
			$flash=M('Flash')->order('sort')->limit(6)->select();
			S('flash',$flash,10000);
		}
		
		$this->assign('flash',$flash);
		
        //截断友情链接url头，统一写法
		if(S('link')){
			$link_info = S('link');
		}else{
			$link = M('Link');
					$link_info =$link
					->where("status =1")
					->order('add_time desc')
					->limit(12)->select();
			 foreach($link_info as $k => $v){
				$url="";
				$url = trim($v['url'],'https://');
				$link_info[$k]['url'] = trim($url,'http://');
			}
			S('link',$link_info,1000);
		}
       
      
        $this->assign('link_info',$link_info);
    	
//         $aaa = S('currency');
//         dump($aaa);
//         die();
// 		$this->assign('currency',$currency);

		$this->assign("cu_id",$currency_select);
		$this->assign('empty','暂无数据');
		
		/********************************修改后的首页数据*********************************/

        $currency = $this->currency;//时间降序排列，越接近当前时间越高
        foreach ($currency as $k=>$v){
           $currency[$k] = $this->getCurrencyMessageById($v['currency_id']);
 
        }
        $this->assign('currency',$currency);
		$this->assign("zzzz", round($zhangfu,2));
        $this->display();
     }
      
	  public function select_currency(){
		  $_SESSION['currency_id_select'] = $_GET['currency'];
		  $this->redirect('Index/index');
	  }
	  
	  //币种信息轮询最新
	 public function currency_info(){
		$currency = $this->currency;//时间降序排列，越接近当前时间越高
        foreach ($currency as $k=>$v){
           $currency[$k] = $this->getCurrencyMessageById($v['currency_id']);
        }
		$this->ajaxReturn($currency);
		
	 }
	  
     public function ajax_trade_price(){
     	$id = I("id","");
     	if(empty($id)){
     		return false;
     	}
     	//求出这个币种的最新价格
     	$data['info']['new_price']=M("Trade_".$id)->where(array("currency_id"=>$id))->order("add_time desc")->getField('price');//最新价格
     	$currency_digit_num = M('Currency')->Field('currency_digit_num')->where(array("currency_id"=>$id))->find();
     	//最低价
     	$data['info']['di_price']=M("Trade_".$id)->where(array("currency_id"=>$id))->order("price asc")->getField('price');//最低价格
     	//最高价
     	$data['info']['gao_price']=M("Trade_".$id)->where(array("currency_id"=>$id))->order("price desc")->getField('price');//最高价
     	//24H成交量
		$data['info']['di_price'] = number_format($data['info']['di_price'],$currency_digit_num['currency_digit_num']);
		$data['info']['gao_price'] = number_format($data['info']['gao_price'],$currency_digit_num['currency_digit_num']);
     	$where['currency_id']=$id;
     	$where['add_time']=array("between",array(time()-24*3600,time()));
        $num24=M("Trade_".$id)->where($where)->sum('num');
        $data['info']['num_24']=empty($num24)?0:$num24;
     	//涨幅
     	$data['status']=1;
     	$this->ajaxReturn($data);     	
     }
	 
	 /**
	 *排行榜
	 */
	 public  function  chart(){
		 
		 $switch = $this->config['list_switch'];
		 $start = $this->config['reward_start_time'];
		 $end = $this->config['reward_end_time'];
		 if($switch == 0){
			 $this->error('活动未开启');
		 }
		 $where['reg_time'] = array('between',array($start,$end));
		 $where['pid'] = array("NEQ","");
		 $where['status'] = 5;
		 $field="pid,count(pid) as xx";
		 $list=M("Member")->field($field)->where($where)->group("pid")->order("xx desc")->select();
		 $re_15 = array();
		 $re_30 = array();
		 foreach($list as $k => $v){
			 $email = M('Member')->Field('email')->where("`member_id`={$v['pid']}")->find();
			 if(empty($email)){
				 continue;
			 }
			 if(count($re_15) < 15){
				 $re_15[] = array('email'=>$email['email'],'count'=>$v['xx']);
			 }else if(count($re_30) < 15){
				 $re_30[] = array('email'=>$email['email'],'count'=>$v['xx']);
			 }else{
				 break;
			 }
		 }
		 //dump($list);die;
		 $this->assign('re_15',$re_15);
		 $this->assign('re_30',$re_30);
		 $this->display();
	 }
	 
	 /**
	 *用于异步加载头部数据  包含信息  未读消息条数  个人总资产情况
	 *
	 */
	 public function personinfo(){
		   //执行抓取条数  **头部异步加载
			$newMessageCount = $this->pullMessageCount($_SESSION['USER_KEY_ID']); 
			//个人资产
			$where['member_id']=$_SESSION['USER_KEY_ID'];
			$currency_user=M('Currency_user')
			->field(''.C('DB_PREFIX').'currency_user.*,('.C('DB_PREFIX').'currency_user.num+'.C('DB_PREFIX').'currency_user.forzen_num) as count,'.C('DB_PREFIX').'currency.currency_name,'.C('DB_PREFIX').'currency.currency_mark')
			->join("left join ".C('DB_PREFIX')."currency on ".C('DB_PREFIX')."currency.currency_id=".C('DB_PREFIX')."currency_user.currency_id")
			->where($where)->order('sort')->select();
			$allmoneys = null;
			foreach ($currency_user as $k=>$v){
				$Currency_message=$this->getCurrencyMessageById($v['currency_id']);
				$allmoney=$currency_user[$k]['count']*$Currency_message['new_price'];
				$allmoneys+=$allmoney;
			}
			$member_rmb=$this->member;
			$allmoneys=$allmoneys+$member_rmb['count'];
			$allmoneys = intval($allmoneys*10000)/10000;
			
			$data['newMessageCount'] = $newMessageCount;
			$data['allmoneys'] = $allmoneys;
		 
			$this->ajaxReturn($data);
		 
	 }
	 public function whitepaper(){
	 	$this->display();
	 }
	 
	 public function testt(){
	 	$member_id = 158;
	 	dump($member_id);
	 	//查到我的上线ID
	 	$res = M('Member')->where(array('member_id'=>$member_id))->find();
	 	dump($res['pid']);
	 	if($res){
	 		//查到上线上线的ID
	 		$r = M('Member')->where(array('member_id'=>$res['pid']))->find();
	 		dump($r['pid']);
	 	}
	 }
}