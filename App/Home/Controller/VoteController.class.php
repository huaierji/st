<?php
namespace Home\Controller;
use Home\Controller\TradeFatherController;
use Common\Logic\TradeLogic;
use Think\Controller;

class VoteController extends Controller {   
    //投票
    public function vote() {		    	
        $this->display();
		$user=session('USER_KEY_ID');
		if(!isset($user)){
			echo "<script language=\"javascript\">alert('请登录后投票!');</script>";
		}	
			 
	}
}
