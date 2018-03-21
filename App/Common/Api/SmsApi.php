<?php
use think\Session;
use Sms\Request\V20160927 as Sms;
/**
 * @description  	短信接口
 * @author      	LiYI <1041012846@qq.com>
 * @date 2016-11-1 	时间
 * @version 1.0.0	版本
 * @copyright
 */
class SmsApi {
	public	$user 		= 'shitilian';	//短信账户		默认liyi
	public	$password 	= '123456';	//账户密码		
	public	$type 		= 'huaxin';	//发送短信分类	默认华信
	public	$time 		=  60;		//发送短信间隔 	默认120秒
	public	$length 	=  4;		//验证码位数	默认4位
	public	$signature 	= 'ST';	//签名			默认千翼
	private	$ip;					//当前用户ip
	/**
	 * 构造函数
	 * @param string $type			发送短信分类
	 * @param string $user			短信账户
	 * @param string $password		账户密码
	 * @param string $signature		签名		
	 * @param string $time			发送短信间隔 默认120秒
	 * @param string $length		验证码位数	默认4位
	 */
	function __construct($type=null,$user=null,$password=null,$signature=null,$time=null,$length=null){
		//修改默认值
		$this ->type		= $type?$type:$this ->type;
		$this ->user		= $user?$user:$this ->user;
		$this ->password 	= $password?$password:$this ->password;
		$this ->signature 	= $signature?$signature:$this ->signature;
		$this ->time 		= $time?$time:$this ->time;
		$this ->length 		= $length?$length:$this ->length;
		$this ->ip 			= $_SERVER["REMOTE_ADDR"];
	}
	
	/**
	 * 执行主方法
	 * @param $phone 	电话号码
	 * @param $content	短信内容
	 */
	public function send ($phone,$content=''){
		/*
		$phone_regex = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';
		if(!preg_match($phone_regex,$phone))
			return array('status'=>-101,'info'	=>'请填写正确手机号');		//验证手机号
		*/
		if(!$this->checkApp($phone,'','time')){
			return array('status'=>-104,'info'	=>'短信已发送！请稍等');	//验证发送时间
		}
		if(!$this->checkApp($phone,'','ip')){
			return array('status'=>-104,'info'	=>'短信已发送！请稍等');	//验证发送时间
		}
		if(!$this->checkApp($phone,'','check')){
			return array('status'=>-104,'info'	=>'您今日获取短信验证码的次数过多,已禁止发送验证码 ');	//验证发送时间
		}	
		$code=substr(rand(100000,999999),0,$this->length);
		if(!$content){	
			$content = $this ->getSmsContent($code);
		}
		$content = "【".$this ->signature."】".$content;					//拼接签名
		switch(strtolower($this ->type)){									//发送短信
			case 'huaxin': $data = $this ->huaxin($phone,$content);break;	//华信
			case 'dayu': $data = $this ->dayu($phone,$content);break;		//大鱼
			case 'cpunc': $data = $this ->cpunc($phone,$content);break;		//融合通信
			case 'ymrt': $data = $this ->ymrt($phone,$content);break;		//亿美软通
			default:$data = $this ->huaxin($phone,$content);break;			//默认华信
		}
		if(!$data['status'])
			return array('status'=>-104,'info'	=>'系统繁忙请稍后再试');			//判断发送状态
		if(!empty($code)){
			$this ->writeSmsApp($phone,$code);								//app写入日志
			$this->checkIp();												//写入ip时间
		}else{
			//$this ->writeSmsApp($phone);									//app写入日志
		}
		$data=array('status'=>1,'info'	=>'信息发送成功');					//返回数据
		return $data;
	}
	/**
	 * 获取发送短信内容
	 * @param unknown $code  验证码 
	 * @return string 短信信息
	 */
	private function getSmsContent($code){
		$content="您申请的验证码:".$code;
		$content=$content.',请勿泄漏!';
		return $content;
	}
	
	/** 华信短信   我们合作
	 * @param $phone 电话号码
	 * @return mixed 发信息
	 */
	private  function huaxin($phone,$content){
		$smsapi = "http://dx.ipyy.net/smsJson.aspx";
		$pass = md5($this ->password);
		$sendurl = $smsapi.'?action=send&userid=&account='.$this ->user.'&password='.$pass.'&mobile='.$phone.'&content='.$content.'&sendTime=&extno=';
		$sendurl=urldecode($sendurl);
		$result =file_get_contents($sendurl);
		//$result = '{"returnstatus":"Success","message":"操作成功","remainpoint":"21396","taskID":"1611013435567687","successCounts":"1"}';
		$result = json_decode($result);
		$data=array(
			'status'=>$result->successCounts,	//短信回馈接口码 0  失败  1成功
			'info'=>$result->message			//短信回馈文字描述
		);
		return $data;							//返回信息
	}
	
	
	/** 华信短信   我们合作
	 * @param $phone 电话号码
	 * @return mixed 发信息
	 */
	private  function ymrt($phone,$content){
		//发送
		$url="http://hprpt2.eucp.b2m.cn:8080/sdkproxy/sendsms.action?cdkey=$this->user&password=$this->password&phone=$phone&message=$content";
		$result = file_get_contents($url);
		if($result == 0){
			$data=array(
				'status'=>1,			//短信回馈接口码 0  失败  1成功
				'info'=>'发送成功'		//短信回馈文字描述
			);
		}else{
			$data=array(
				'status'=>$result,		//短信回馈接口码 0  失败  1成功
				'info'=>$result			//短信回馈文字描述
			);
		}
		return $data;					//返回信息
	}
	
	
	/**
	 * 阿里大鱼
	 * @param unknown $phone
	 * @param unknown $code
	 * @return multitype:number string
	 */
	private function dayu($phone,$code){
		include_once 'aliyun-php-sdk-core/Config.php';
		$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "LTAIVUdtOEnrsfNO", "nQrYbHE4eEqjLFnvtV408ZFRhA6kec");
		$client = new DefaultAcsClient($iClientProfile);
		$request = new Sms\SingleSendSmsRequest();
		$request->setSignName("乐麦圈");/*签名名称*/
		$request->setTemplateCode("SMS_59820055");/*模板code*/
		$request->setRecNum("$phone");/*目标手机号*/
		$request->setParamString("{\"code\":\"{$code}\",\"product\":\"乐麦圈\"}");/*模板变量，数字一定要转换为字符串*/
		try {
			$response = $client->getAcsResponse($request);
			//print_r($response);
		} 
		catch (ClientException  $e) {
			//print_r($e->getErrorCode());
			//print_r($e->getErrorMessage());
		}
		catch (ServerException  $e) {
			//print_r($e->getErrorCode());
			//print_r($e->getErrorMessage());
		}
		$data=array(
				'status'=>1,	//短信回馈接口码 0  失败  1成功
				'info'=>'发送成功'			//短信回馈文字描述
		);
		return $data;							//返回信息
	}
	

	/** 华信短信   我们合作
	 * @param $phone 电话号码
	 * @return mixed 发信息
	 */
	private  function cpunc($phone,$content){
		$url = "http://service.winic.org:8009/sys_port/gateway/index.asp?"; //提交的url地址\
		$data = "id=%s&pwd=%s&to=%s&content=%s&time=%s";
		$id = urlencode(iconv("UTF-8","GB2312",$this->user));
		$pwd = $this ->password;
		$to = $phone;
		$content = iconv("UTF-8","GB2312",$content);
		$time = "";
		$rdata = sprintf($data, $id, $pwd, $to, $content, $time);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$rdata);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$result = curl_exec($ch);
		curl_close($ch);
		$result= substr( $result, 0, 3 );  //获取信息发送后的状态
		if($result == 000){
			$data=array(
					'status'=>1,				//短信回馈接口码 0  失败  1成功
					'info'=>'短信发送成功'			//短信回馈文字描述
			);
		}else{
			$data=array(
					'status'=>-1,				//短信回馈接口码 0  失败  1成功
					'info'=>'短信发送失败'			//短信回馈文字描述
			);
		}
		return $data;							//返回信息
	}
	
	
	/**
	 * 手机验证检测
	 * @param string $type 		验证规则 默认验证码	code 验证码 time 验证时间 ip 验证ip
	 * @param string $phone		手机号/ip
	 * @param string $code		验证码
	 * @return boolean
	 */
	Public function checkSms($phone,$code,$type){
		switch ($type){
			case 'code' ://验证码验证
				$data = true;
				$scode = Session::get('code_quyum_'.$phone);
				if($code != $scode)$data = false;break;
			case 'time' ://短信时间验证
				$data = true;
				$time = Session::get('time_quyum_'.$phone)+$this->time;
				if(time()< $time)$data = false;break;
			case 'ip' ://验证ip发送时间
				$data = true;
				$time = Session::get('time_quyum_'.$phone)+$this->time;
				if(time()< $time)$data = false;break;
			default:	//默认短信验证
				$data = true;
				$scode = Session::get('code_quyum_'.$phone);
				if($code != $scode)$data = false;break;
		}
		return $data;
	}
	/**
	 * 删除短信验证码
	 * @param string $phone		手机号
	 * @param string $type 		删除条件
	 * @return boolean
	 */
	Public function deleteSendSms($phone,$type){
		switch ($type){
			case 'code' ://验证码验证
				Session::delete('code_quyum_'.$phone);
				Session::delete('time_quyum_'.$phone);
				Session::delete('time_quyum_'.$this->ip);break;
			default:	//默认短信验证
				Session::delete('code_quyum_'.$phone);
				Session::delete('time_quyum_'.$phone);
				Session::delete('time_quyum_'.$this->ip);break;
		}
		return true;
	}
	
	/**
	 * 删除短信验证
	 * @param unknown $phone
	 */
	public function deleteSendSmsApp($phone){
		header("Content-type: text/html; charset=utf-8");
		$file  = 'sms/'.$phone.'_smsApp.log';			//日志名
		//检测文件是否存在
		if(!file_exists($file)){
			return true;
		}
		unlink($file);
	}
	/**
	 * 获取ip 并且转换城市
	 * @return array 
	 */
	private  function GetIpLookup(){
		$ip_json=@file_get_contents($api="http://ip.taobao.com/service/getIpInfo.php?ip=".$this ->ip);
		return json_decode($ip_json);
	}
	/**
	 * App存短短信
	 */
	private function writeSmsApp($phone,$code=''){
		header("Content-type: text/html; charset=utf-8");
		$file  = 'sms/'.$phone.'_smsApp.log';			//日志名
		//日志内容
		$data['time_quyum_'.$phone] = time();
		$data['time_quyum_'.$this ->ip] = time();
		$data['code_quyum_'.$phone] = $code;
		$log = json_encode($data);
		file_put_contents($file,$log);
	}
	
	
	// 存验证ip
	public function checkIp(){
		header("Content-type: text/html; charset=utf-8");
		$today = strtotime(date("Y-m-d"),time());
		$file  = 'check/'.$today."_".$this ->ip.'_smsApp.log';
		//$data['time_'.$this ->ip] = time();
		$num = "1";
		//$log = json_encode($data);
		$flies = $this->getipCheck($this->ip);
		
		if($flies){
			$num = (int)$flies+1;
			file_put_contents($file, $num);
		}else{
			file_put_contents($file, $num);
		}
	}
	
	/**
	 * 获取文件内容
	 */
	private function getSms($phone){
		header("Content-type: text/html; charset=utf-8");
		$file  = 'sms/'.$phone.'_smsApp.log';			//日志名
		return file_get_contents($file);
	}
	/**
	 * 获取文件内容
	 */
	private function getipCheck($ip){
		header("Content-type: text/html; charset=utf-8");
		$today = strtotime(date("Y-m-d"),time());
		$file  = 'check/'.$today."_".$ip.'_smsApp.log';			//日志名
		return file_get_contents($file);
	}
	/**
	 * 手机验证检测
	 * @param string $type 		验证规则 默认验证码	code 验证码 time 验证时间 ip 验证ip
	 * @param string $phone		手机号/ip
	 * @param string $code		验证码
	 * @return boolean
	 */
	Public function checkApp($phone,$code,$type){
		//检测文件是否存在
		if(!file_exists('sms/'.$phone.'_smsApp.log')){
			return true;
		}
		$today = strtotime(date("Y-m-d"),time());
		if(!file_exists('check/'.$today."_".$this ->ip.'_smsApp.log')){
			return true;
		}
		
		//存在打开
		$sms = json_decode($this ->getSms($phone));
		$check = json_decode($this ->getipCheck($this ->ip));
		
		switch ($type){
			case 'code' ://验证码验证
				$data = true;
				$name = 'code_quyum_'.$phone;
				$scode = $sms ->$name;
				if($code != $scode)$data = false;break;
			case 'time' ://短信时间验证
				$data = true;
				$name = 'time_quyum_'.$phone;
				$time = $sms ->$name+$this->time;
				if(time()< $time)$data = false;break;
			case 'ip' ://验证ip发送时间
				$data = true;
				$name = 'time_quyum_'.$phone;
				$time = $sms ->$name+$this->time;
				if(time()< $time)$data = false;break;
			case 'check':
				$data = true;
				$num  = $this->getipCheck($this->ip);
				if((int)$num>10){
					$data = false;
				}
				break;
			default:	//默认短信验证
				$data = true;
				$name = 'code_quyum_'.$phone;
				$scode = $sms ->$name;
				if($code != $scode)$data = false;break;
		}
		return $data;
	}
	/**
	 * 手机验证检测
	 * @param unknown $phone	手机号
	 * @param unknown $code		验证码
	 * @return boolean
	 */
	Public function checkAppCode($phone,$code){
		//检测文件是否存在
		if(!file_exists('sms/'.$phone.'_smsApp.log')){
			return false;
		}
		//存在打开
		$sms = json_decode($this ->getSms($phone));
		$data = true;
		$name = 'code_quyum_' . $phone;
		$scode = $sms->$name;
		if ($code != $scode)
			$data = false;
		return $data;
	}
}