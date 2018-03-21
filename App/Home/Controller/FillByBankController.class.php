<?php

namespace Home\Controller;

use Common\Controller\CommonController;

class FillByBankController extends HomeController {
	protected $pMerCode;
	protected $pMerCert;
	protected $pAccount;
	protected $url;
	public function _initialize() {
		parent::_initialize ();
		$this->User_status ();		
		$this->pMerCode = "191955";
		$this->pMerCert = "1eeHYSJhqD5AEror5cMmSfQCocr5qdbCTwCQarDbLcVsdZTLRVE9WCkx5280T8fJHcLlotMkm0f1OwcbgLfZnU1q8H0H0R8HZlUsstt3s8kBX8PzfhTTPctNQA3125Kp";
		$this->pAccount = "1919550010";		
		$this->url = $_SERVER ['HTTP_HOST'];
	}
	// 空操作
	Public function _empty() {
		header ( "HTTP/1.0 404 Not Found" ); // 使HTTP返回404状态码
		$this->display ( "Public:404" );
	}
	public function index() {
		$this->display ();
	}
	public function success(){
		$this->display();
	}
	// 生成订单
	public function bank() {
		//echo 123;die;
		if($this->config['huanxun'] != 1){
			$this->error ( '网银充值暂时关闭，请选择其他充值方式进行充值' );
		}				
		if ($_POST ["p3_Amt"] < $this->config ['huanxun_min_money']) {
			$this->error ( '充值金额不得小于' . $this->config ['huanxun_min_money'] );
		}
		// 写入订单
		$orderno = date ( 'YmdHis' ) . mt_rand ( 100000, 999999 );
		$data ['num'] = floatval($_POST ["p3_Amt"]);
		$data ['random'] = rand ( 0001, 9999 );
		$data ['uid'] = $_SESSION ['USER_KEY_ID'];
		$data ['email'] = $_SESSION ['USER_KEY'];
	    $data ['uname']=$this->auth['name'];
		$data ['ctime'] = time ();
		$data ['fee'] = $this->config['huanxun_fee'];
		$data ['actual'] = $data['num']*(1-$this->config['huanxun_fee']);
		$data ['tradeno'] = $orderno;
		$data ['status'] = 0;
		$data ['bankname'] = $_POST ["pd_FrpId"];
		$data ['type'] = 1;
		//dump($data);
		$r = M ( 'Fill' )->add ( $data );
		if(!$r){
			$this->error('失败');
		}
		//dump($r);die;
		$pMerCode = $this->pMerCode;
		$pMerCert = $this->pMerCert;
		$pAccount = $this->pAccount;		
		$pMerBillNo = $orderno;
		$amount = $_POST ["p3_Amt"];
		$pAmount = number_format ( $amount, 2, '.', '' );
		$pIsCredit = "1"; // '银行直连
		$pBankCode = $_POST ["pd_FrpId"]; // request("rtype")
		$pAttach = $this->auth ['member_id'];		
		$pVersion = "v1.0.0";
		$pMerName = $pMerCode;
		$pMsgId = "msg" . mt_rand ( 1000, 9999 );
		$pReqDate = date ( 'YmdHis' );
		// $pReqDate = $pReqDate;
		$pCurrencyType = "GB";
		$pGatewayType = "01";
		$pLang = "156";
		$pDate = date ( 'Ymd' );
		$pMerchanturl = "http://" . $this->url . "/index.php/Home/FillByBank/OrderReturn"; // 成功
		$pFailUrl = "http://" . $this->url . "/index.php/Home/FillByBank/OrderReturn"; // 失败
		
		$pOrderEncodeTyp = "5";
		$pRetEncodeType = "17";
		$pRetType = "1";
		$pServerUrl = "http://" . $this->url . "/index.php/Home/FillByBank/OrderReturn2"; // 异步
		$pBillEXP = 1;
		$pGoodsName = "ipsonlinepay";
		$pProductType = "1";		
		$reqParam = "【商户号】:" . $pMerCode . "【商户名】:" . $pMerName . " 【账户号】:" . $pAccount . " 【消息编号】:" . $pMsgId . " 【商户请求时间】:" . $pReqDate . " 【商户订单号】:" . $pMerBillNo;
		$reqParam = $reqParam . " 【订单金额】:" . $pAmount . " 【订单日期】:" . $pDate . " 【币种】:" . $pCurrencyType . " 【支付方式】:" . $pGatewayType . " 【语言】:" . $pLang . " 【支付结果成功返回的商户URL】:" . $pMerchanturl;
		$reqParam = $reqParam . " 【支付结果失败返回的商户URL】:" . $pFailUrl . " 【商户数据包】:" . $pAttach . " 【订单支付接口加密方式】:" . $pOrderEncodeTyp . " 【交易返回接口加密方式】:" . $pRetEncodeType;
		$reqParam = $reqParam . " 【返回方式】:" . $pRetType . " 【Server to Server返回页面】:" . $pServerUrl . " 【订单有效期】:" . $pBillEXP . " 【商品名称】:" . $pGoodsName . " 【直连选项】:" . $pIsCredit;
		$reqParam = $reqParam . " 【银行号】:" . $pBankCode . " 【产品类型】:" . $pProductType;
		
		if ($pIsCredit == "0") {
			$pBankCode = "";
			$pProductType = "";
		}		
		$strbodyxml = "<body><MerBillNo>" . $pMerBillNo . "</MerBillNo><Amount>" . $pAmount . "</Amount>";
		$strbodyxml = $strbodyxml . "<Date>" . $pDate . "</Date><CurrencyType>" . $pCurrencyType . "</CurrencyType>";
		$strbodyxml = $strbodyxml . "<GatewayType>" . $pGatewayType . "</GatewayType><Lang>" . $pLang . "</Lang>";
		$strbodyxml = $strbodyxml . "<Merchanturl>" . $pMerchanturl . "</Merchanturl><FailUrl>" . $pFailUrl . "</FailUrl>";
		$strbodyxml = $strbodyxml . "<Attach>" . $pAttach . "</Attach><OrderEncodeType>" . $pOrderEncodeTyp . "</OrderEncodeType>";
		$strbodyxml = $strbodyxml . "<RetEncodeType>" . $pRetEncodeType . "</RetEncodeType><RetType>" . $pRetType . "</RetType>";
		$strbodyxml = $strbodyxml . "<ServerUrl>" . $pServerUrl . "</ServerUrl><BillEXP>" . $pBillEXP . "</BillEXP>";
		$strbodyxml = $strbodyxml . "<GoodsName>" . $pGoodsName . "</GoodsName><IsCredit>" . $pIsCredit . "</IsCredit>";
		$strbodyxml = $strbodyxml . "<BankCode>" . $pBankCode . "</BankCode><ProductType>" . $pProductType . "</ProductType></body>";
		
		$pSignature = MD5 ( $strbodyxml . $pMerCode . $pMerCert ); // 数字签名
		
		$strheaderxml = "<head><Version>" . $pVersion . "</Version><MerCode>" . $pMerCode . "</MerCode>";
		$strheaderxml = $strheaderxml . "<MerName>" . $pMerName . "</MerName><Account>" . $pAccount . "</Account>";
		$strheaderxml = $strheaderxml . "<MsgId>" . $pMsgId . "</MsgId><ReqDate>" . $pReqDate . "</ReqDate>";
		$strheaderxml = $strheaderxml . "<Signature>" . $pSignature . "</Signature></head>";
		
		$strsubmitxml = "<Ips><GateWayReq>" . $strheaderxml . $strbodyxml . "</GateWayReq></Ips>";
		
		$form_url = "http://newpay.ips.com.cn/psfp-entry/gateway/payment.html";
		// $form_url="http://pay.huatiansc.com/ips31/ips31.php";
		
		$this->assign ( 'strsubmitxml', $strsubmitxml );
		$this->assign ( 'form_url', $form_url );
		
		$this->display ();
	}
	public function OrderReturn() {
		header ( "Content-type:text/html; charset=utf-8" );
		
		$pMerCode = $this->pMerCode;
		$pMerCert = $this->pMerCert;
		$pAccount = $this->pAccount;
		if (isset ( $_POST ["paymentResult"] )) {
			
			$paymentResult = $_POST ["paymentResult"]; // 获取信息
			
			$xml = simplexml_load_string ( $paymentResult, 'SimpleXMLElement', LIBXML_NOCDATA );
			//dump($xml);
			// 读取相关xml中信息
			$ReferenceIDs = $xml->xpath ( "GateWayRsp/head/ReferenceID" ); // 关联号
			                                                            // var_dump($ReferenceIDs);
			$ReferenceID = $ReferenceIDs [0]; // 关联号
			$RspCodes = $xml->xpath ( "GateWayRsp/head/RspCode" ); // 响应编码
			$RspCode = $RspCodes [0];
			$RspMsgs = $xml->xpath ( "GateWayRsp/head/RspMsg" ); // 响应说明
			$RspMsg = $RspMsgs [0];
			$ReqDates = $xml->xpath ( "GateWayRsp/head/ReqDate" ); // 接受时间
			$ReqDate = $ReqDates [0];
			$RspDates = $xml->xpath ( "GateWayRsp/head/RspDate" ); // 响应时间
			$RspDate = $RspDates [0];
			$Signatures = $xml->xpath ( "GateWayRsp/head/Signature" ); // 数字签名
			//dump($Signatures);
			$Signature = $Signatures [0];
			$MerBillNos = $xml->xpath ( "GateWayRsp/body/MerBillNo" ); // 商户订单号
			$MerBillNo = $MerBillNos [0];
			$CurrencyTypes = $xml->xpath ( "GateWayRsp/body/CurrencyType" ); // 币种
			$CurrencyType = $CurrencyTypes [0];
			$Amounts = $xml->xpath ( "GateWayRsp/body/Amount" ); // 订单金额
			$Amount = $Amounts [0];
			$Dates = $xml->xpath ( "GateWayRsp/body/Date" ); // 订单日期
			$Date = $Dates [0];
			$Statuss = $xml->xpath ( "GateWayRsp/body/Status" ); // 交易状态
			$Status = $Statuss [0];
			$Msgs = $xml->xpath ( "GateWayRsp/body/Msg" ); // 发卡行返回信息
			$Msg = $Msgs [0];
			$Attachs = $xml->xpath ( "GateWayRsp/body/Attach" ); // 数据包
			$Attach = $Attachs [0];
			$IpsBillNos = $xml->xpath ( "GateWayRsp/body/IpsBillNo" ); // IPS订单号
			$IpsBillNo = $IpsBillNos [0];
			$IpsTradeNos = $xml->xpath ( "GateWayRsp/body/IpsTradeNo" ); // IPS交易流水号
			$IpsTradeNo = $IpsTradeNos [0];
			$RetEncodeTypes = $xml->xpath ( "GateWayRsp/body/RetEncodeType" ); // 交易返回方式
			$RetEncodeType = $RetEncodeTypes [0];
			$BankBillNos = $xml->xpath ( "GateWayRsp/body/BankBillNo" ); // 银行订单号
			$BankBillNo = $BankBillNos [0];
			$ResultTypes = $xml->xpath ( "GateWayRsp/body/ResultType" ); // 支付返回方式
			$ResultType = $ResultTypes [0];
			$IpsBillTimes = $xml->xpath ( "GateWayRsp/body/IpsBillTime" ); // IPS处理时间
			$IpsBillTime = $IpsBillTimes [0];
			
			$resParam = "关联号:" . $ReferenceID . "响应编码:" . $RspCode . "响应说明:" . $RspMsg . "接受时间:" . $ReqDate . "响应时间:" . $RspDate . "数字签名:" . $Signature . "商户订单号:" . $MerBillNo . "币种:" . $CurrencyType . "订单金额:" . $Amount . "订单日期:" . $Date . "交易状态:" . $Status . "发卡行返回信息:" . $Msg . "数据包:" . $Attach . "IPS订单号:" . $IpsBillNo . "交易返回方式:" . $RetEncodeType . "银行订单号:" . $BankBillNo . "支付返回方式:" . $ResultType . "IPS处理时间:" . $IpsBillTime;
			
			// 验签明文
			// billno+【订单编号】+currencytype+【币种】+amount+【订单金额】+date+【订单日期】+succ+【成功标志】+ipsbillno+【IPS订单编号】+retencodetype +【交易返回签名方式】+【商户内部证书】
			
			$sbReq = "<body>" . "<MerBillNo>" . $MerBillNo . "</MerBillNo>" . "<CurrencyType>" . $CurrencyType . "</CurrencyType>" . "<Amount>" . $Amount . "</Amount>" . "<Date>" . $Date . "</Date>" . "<Status>" . $Status . "</Status>" . "<Msg><![CDATA[" . $Msg . "]]></Msg>" . "<Attach><![CDATA[" . $Attach . "]]></Attach>" . "<IpsBillNo>" . $IpsBillNo . "</IpsBillNo>" . "<IpsTradeNo>" . $IpsTradeNo . "</IpsTradeNo>" . "<RetEncodeType>" . $RetEncodeType . "</RetEncodeType>" . "<BankBillNo>" . $BankBillNo . "</BankBillNo>" . "<ResultType>" . $ResultType . "</ResultType>" . "<IpsBillTime>" . $IpsBillTime . "</IpsBillTime>" . "</body>";
			$sign = $sbReq . $pMerCode . $pMerCert;
			$md5sign = md5 ( $sign );
			//dump($sign);
			//dump($_POST);
			$logName = "11.txt";
			
			$james = fopen ( $logName, "a+" );
			
			fwrite ( $james, "\r\n" . date ( "Y-m-d H:i:s" ) . "|" . $Signature . "|[" . $md5sign . "]|[" . $MerBillNo . "]|[" . $Amount . "]|[" . $Status . "]" );
			
			fwrite ( $james, "\r\n----------------------------------------------------------------------------------------" );
			fclose ( $james );
			//dump($md5sign);
			//dump($Signature);die;
			// 判断签名
			if ($Signature == $md5sign) {				
				if ($RspCode == '000000') {					
					$extra_return_param = $Attach;
					$order_no = $MerBillNo;
					$order_amount = $Amount;					
					$link = mysql_connect (  C('DB_HOST'), C('DB_USER'), C('DB_PWD')) or die ( "数据库连接失败" );
					mysql_select_db ( C('DB_NAME'), $link );
					mysql_set_charset ( "utf8" );
					//dump($extra_return_param);die;
					$result = mysql_query ( "select count(*) from ".C('DB_PREFIX')."fill where uid='{$extra_return_param}'", $link );					
					$num = mysql_result ( $result, "0" );
					if (! $num) {
						echo "<tr align=center bgcolor=#FFFFFF><td colspan=16>暂无用户数据</td></tr>";
						exit ();
					} else {						
						$result2 = mysql_query ( "select * from ".C('DB_PREFIX')."fill where uid='{$extra_return_param}'", $link  );
						$row = mysql_fetch_assoc ( $result2 );						
						$assets = $row ['num'];
						$uid = $row ['uid'];
						$username = $row ['name'];
					}
					//dump($order_no);
					/*$result3 = mysql_query ( "SELECT * FROM ".C('DB_PREFIX')."fill WHERE `tradeno` = '{$order_no}'",$link);
					echo "SELECT * FROM ".C('DB_PREFIX')."fill WHERE `tradeno` = '{$order_no}'";
					dump($result3);					
					$row3 = mysql_fetch_assoc ( $result3 );*/
					$row3 = M("fill")->where("`tradeno` = '{$order_no}'")->find();					
					if (empty ( $row3 )) {
						echo "无此订单号" . $order_no . "订单";
						exit ();
					}
					$m_id = $row3 ['id'];
					$u_id = $row3 ['uid'];
					$p_money = $row3 ['actual'];
					
					// $sql2 = "update k_money,k_user set k_money.status=1,k_money.update_time=now(),k_user.money=k_user.money+k_money.m_value,k_money.about='ips chong zhi ok',k_money.sxf=0,k_money.balance=k_user.money+k_money.m_value where k_money.uid=k_user.uid and k_money.m_id=$m_id and k_money.`status`=2";
					
					$check_status = M('Fill')->Field('status')->where("id={$m_id}")->find();
					if($check_status['status'] != 1){
						$sql2 = "UPDATE ".C('DB_PREFIX')."fill SET `status` =1 WHERE id=$m_id";						
						if (mysql_query ( $sql2 )) {
							echo "";
						} else {
							echo "Error creating database: " . mysql_error ();
						}
						$sql3 = "UPDATE ".C('DB_PREFIX')."member SET rmb=rmb +$p_money WHERE member_id=$u_id";
						if (mysql_query ( $sql3 )) {
							echo "";
						} else {
							echo "Error creating database: " . mysql_error ();
						}
					}
					$url = U('Fill/index');
					echo "<Script language=javascript>alert('交易成功,返回订单列表.');window.location.href='{$url}'</script>";
					exit ();
				}
			} else {
				echo "订单签名错误";
			}
		} else {
			echo "非法交易";
		}
	}
	public function OrderReturn2() {
		header ( "Content-type:text/html; charset=utf-8" );
		
		$pMerCode = $this->pMerCode;
		$pMerCert = $this->pMerCert;
		$pAccount = $this->pAccount;
		if (isset ( $_POST ["paymentResult"] )) {
			
			$paymentResult = $_POST ["paymentResult"]; // 获取信息
			
			$xml = simplexml_load_string ( $paymentResult, 'SimpleXMLElement', LIBXML_NOCDATA );
			
			// 读取相关xml中信息
			$ReferenceIDs = $xml->xpath ( "GateWayRsp/head/ReferenceID" ); // 关联号
			                                                            // var_dump($ReferenceIDs);
			$ReferenceID = $ReferenceIDs [0]; // 关联号
			$RspCodes = $xml->xpath ( "GateWayRsp/head/RspCode" ); // 响应编码
			$RspCode = $RspCodes [0];
			$RspMsgs = $xml->xpath ( "GateWayRsp/head/RspMsg" ); // 响应说明
			$RspMsg = $RspMsgs [0];
			$ReqDates = $xml->xpath ( "GateWayRsp/head/ReqDate" ); // 接受时间
			$ReqDate = $ReqDates [0];
			$RspDates = $xml->xpath ( "GateWayRsp/head/RspDate" ); // 响应时间
			$RspDate = $RspDates [0];
			$Signatures = $xml->xpath ( "GateWayRsp/head/Signature" ); // 数字签名
			$Signature = $Signatures [0];
			$MerBillNos = $xml->xpath ( "GateWayRsp/body/MerBillNo" ); // 商户订单号
			$MerBillNo = $MerBillNos [0];
			$CurrencyTypes = $xml->xpath ( "GateWayRsp/body/CurrencyType" ); // 币种
			$CurrencyType = $CurrencyTypes [0];
			$Amounts = $xml->xpath ( "GateWayRsp/body/Amount" ); // 订单金额
			$Amount = $Amounts [0];
			$Dates = $xml->xpath ( "GateWayRsp/body/Date" ); // 订单日期
			$Date = $Dates [0];
			$Statuss = $xml->xpath ( "GateWayRsp/body/Status" ); // 交易状态
			$Status = $Statuss [0];
			$Msgs = $xml->xpath ( "GateWayRsp/body/Msg" ); // 发卡行返回信息
			$Msg = $Msgs [0];
			$Attachs = $xml->xpath ( "GateWayRsp/body/Attach" ); // 数据包
			$Attach = $Attachs [0];
			$IpsBillNos = $xml->xpath ( "GateWayRsp/body/IpsBillNo" ); // IPS订单号
			$IpsBillNo = $IpsBillNos [0];
			$IpsTradeNos = $xml->xpath ( "GateWayRsp/body/IpsTradeNo" ); // IPS交易流水号
			$IpsTradeNo = $IpsTradeNos [0];
			$RetEncodeTypes = $xml->xpath ( "GateWayRsp/body/RetEncodeType" ); // 交易返回方式
			$RetEncodeType = $RetEncodeTypes [0];
			$BankBillNos = $xml->xpath ( "GateWayRsp/body/BankBillNo" ); // 银行订单号
			$BankBillNo = $BankBillNos [0];
			$ResultTypes = $xml->xpath ( "GateWayRsp/body/ResultType" ); // 支付返回方式
			$ResultType = $ResultTypes [0];
			$IpsBillTimes = $xml->xpath ( "GateWayRsp/body/IpsBillTime" ); // IPS处理时间
			$IpsBillTime = $IpsBillTimes [0];
			
			$resParam = "关联号:" . $ReferenceID . "响应编码:" . $RspCode . "响应说明:" . $RspMsg . "接受时间:" . $ReqDate . "响应时间:" . $RspDate . "数字签名:" . $Signature . "商户订单号:" . $MerBillNo . "币种:" . $CurrencyType . "订单金额:" . $Amount . "订单日期:" . $Date . "交易状态:" . $Status . "发卡行返回信息:" . $Msg . "数据包:" . $Attach . "IPS订单号:" . $IpsBillNo . "交易返回方式:" . $RetEncodeType . "银行订单号:" . $BankBillNo . "支付返回方式:" . $ResultType . "IPS处理时间:" . $IpsBillTime;
			
			// 验签明文
			// billno+【订单编号】+currencytype+【币种】+amount+【订单金额】+date+【订单日期】+succ+【成功标志】+ipsbillno+【IPS订单编号】+retencodetype +【交易返回签名方式】+【商户内部证书】
			
			$sbReq = "<body>" . "<MerBillNo>" . $MerBillNo . "</MerBillNo>" . "<CurrencyType>" . $CurrencyType . "</CurrencyType>" . "<Amount>" . $Amount . "</Amount>" . "<Date>" . $Date . "</Date>" . "<Status>" . $Status . "</Status>" . "<Msg><![CDATA[" . $Msg . "]]></Msg>" . "<Attach><![CDATA[" . $Attach . "]]></Attach>" . "<IpsBillNo>" . $IpsBillNo . "</IpsBillNo>" . "<IpsTradeNo>" . $IpsTradeNo . "</IpsTradeNo>" . "<RetEncodeType>" . $RetEncodeType . "</RetEncodeType>" . "<BankBillNo>" . $BankBillNo . "</BankBillNo>" . "<ResultType>" . $ResultType . "</ResultType>" . "<IpsBillTime>" . $IpsBillTime . "</IpsBillTime>" . "</body>";
			$sign = $sbReq . $pMerCode . $pMerCert;
			
			$md5sign = md5 ( $sign );
			
			$logName = "22.txt";
			
			$james = fopen ( $logName, "a+" );
			
			fwrite ( $james, "\r\n" . date ( "Y-m-d H:i:s" ) . "|" . $Signature . "|[" . $md5sign . "]|[" . $MerBillNo . "]|[" . $Amount . "]|[" . $Status . "]" );
			
			fwrite ( $james, "\r\n----------------------------------------------------------------------------------------" );
			fclose ( $james );
			
			// 判断签名
			if ($Signature == $md5sign) {
				
				if ($RspCode == '000000') {
					
					$extra_return_param = $Attach;
					$order_no = $MerBillNo;
					$order_amount = $Amount;
					
					$link = mysql_connect (  C('DB_HOST'), C('DB_USER'), C('DB_PWD')) or die ( "数据库连接失败" );
					mysql_select_db ( C('DB_NAME'), $link );
					mysql_set_charset ( "utf8" );
					//dump($extra_return_param);die;
					$result = mysql_query ( "select count(*) from ".C('DB_PREFIX')."fill where uid='{$extra_return_param}'", $link );
					
					$num = mysql_result ( $result, "0" );
					if (! $num) {
						echo "<tr align=center bgcolor=#FFFFFF><td colspan=16>暂无用户数据</td></tr>";
						exit ();
					} else {
						
						$result2 = mysql_query ( "select * from ".C('DB_PREFIX')."fill where uid='{$extra_return_param}'", $link  );
						$row = mysql_fetch_assoc ( $result2 );
						
						$assets = $row ['num'];
						$uid = $row ['uid'];
						$username = $row ['name'];
					}
					//dump($order_no);
					/*$result3 = mysql_query ( "SELECT * FROM ".C('DB_PREFIX')."fill WHERE `tradeno` = '{$order_no}'",$link);
					echo "SELECT * FROM ".C('DB_PREFIX')."fill WHERE `tradeno` = '{$order_no}'";
					dump($result3);
					
					$row3 = mysql_fetch_assoc ( $result3 );*/
					$row3 = M("fill")->where("`tradeno` = '{$order_no}'")->find();
					
					if (empty ( $row3 )) {
						echo "无此订单号" . $order_no . "订单";
						exit ();
					}
					
					$m_id = $row3 ['id'];
					$u_id = $row3 ['uid'];
					$p_money = $row3 ['actual'];

					$check_status = M('Fill')->Field('status')->where("id={$m_id}")->find();
					if($check_status['status'] == 1){
						echo "充值成功";
						exit();
					}
					$m_id = $row3 ['id'];
					$u_id = $row3 ['uid'];
					$p_money = $row3 ['actual'];
					
					// $sql2 = "update k_money,k_user set k_money.status=1,k_money.update_time=now(),k_user.money=k_user.money+k_money.m_value,k_money.about='ips chong zhi ok',k_money.sxf=0,k_money.balance=k_user.money+k_money.m_value where k_money.uid=k_user.uid and k_money.m_id=$m_id and k_money.`status`=2";					
					$check_status = M('Fill')->Field('status')->where("id={$m_id}")->find();
					if($check_status['status'] != 1){
						$sql2 = "UPDATE ".C('DB_PREFIX')."fill SET `status` =1 WHERE id=$m_id";						
						if (mysql_query ( $sql2 )) {
							echo "";
						} else {
							echo "Error creating database: " . mysql_error ();
						}
						$sql3 = "UPDATE ".C('DB_PREFIX')."member SET rmb=rmb +$p_money WHERE member_id=$u_id";
						if (mysql_query ( $sql3 )) {
							echo "";
						} else {
							echo "Error creating database: " . mysql_error ();
						}
					}
					$url = U('Fill/index');
					echo "<Script language=javascript>alert('交易成功,返回订单列表.');window.location.href='{$url}'</script>";
					exit ();
				}
			} else {
				echo "订单签名错误";
			}
						
						
						
		} else {
			echo "非法交易";
		}
	}
	

}