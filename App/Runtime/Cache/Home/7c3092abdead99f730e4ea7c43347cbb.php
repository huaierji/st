<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html> 
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" >
<meta name="renderer" content="webkit">
<meta name="keywords" content=""/>
<meta name="description" content=""/>
<meta property="wb:webmaster" content="8af72a3a7309f0ee">
    <title><?php if(!empty($article)): echo ($article["title"]); ?>-<?php endif; echo ((isset($config["title"]) && ($config["title"] !== ""))?($config["title"]):"虚拟币交易网站"); ?></title>
	<link rel="Shortcut Icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/base.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/layout.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/subpage.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/user.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/coin.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/zcpc.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/iconfont/demo.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/iconfont/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/jb_style.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/hb_index.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/fhb_trade.css">
    <link rel="stylesheet" type="text/css" href="/Public/Home/css/fhb_miao.css">

    <script src="/Public/Home/js/hm.js"></script>
    <script type="text/javascript" src="/Public/Home/js/downList.js"></script>


    <script type="text/javascript" src="/Public/js/jquery-1.8.2.js"></script>
    <script type="text/javascript" src="/Public/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/js/bootstrap.min.js?v=3.4.0"></script>
    <script type="text/javascript" src="/Public/js/layer/layer.js"></script>
    <script src="/Public/js/jquery.validate.min.js"></script>
    <script src="/Public/js/messages_zh.min.js"></script>
    <script src="/Public/js/base.js"></script>
<style>
.user_boxsize{ width:400px !important;}
.balance_list{ margin-left:70px; width:180px; float:left;}
.mywallet_btn_box{ width:380px;}
.top_colorhover:hover{ color:#fff !important;}
.menubox_position{ right:0% !important; width:140px !important;}
.head_user_option{    position: relative;}
<!--头部公告-->
.head_notice_header {
    height: 35px;
    line-height: 35px;
    clear: both;
    background: #fffaf4;
    color: #999;
    position: relative;
}
.head_notice_header .notice_info {
    text-align: center;
}
.head_notice_header a {
    color: #ff6000;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 950px;
}	
.head_notice_header .close {
    position: absolute;
    right: 11px;
    top: 0;
}
.head_notice_header a:hover {
    color: #ec3523;
}

.qh_sub{
	position: relative;
	padding-left: 60px;
}
.qh_sub a{
	color: black !important;
}
.qh_sub a:hover{
	color: #F6A500 !important;
}
.qh_sub img{
	position: absolute;
	top: 9px;
	left: 22px;
	width: 28%;
	height: 76%;
	
}
<?php if(($_SESSION['is_phone']) == "1"): ?>.qh_fixed{
	position: fixed !important;
	/*top: 0;
	left: 0;*/
	z-index: 100;
	width: 100%;
}
.qh_kong{
	width: 100%;
	height: 103px;
}<?php endif; ?>
<!--头部公告结束-->
</style>
</head>
<body style="height:100%;">

<!--top start-->
<div class="qh_fixed">
<div id="doc_top_bar">
    <div class="section">
        <div class="float_left  head_user_option">
				
				<a  class="option" >您好，欢迎您来到 STICO</a>
				<?php if(!empty($member)): ?><a data-widget="#user_slide" href="<?php echo U('ModifyMember/modify');?>" class="option " id="user_slide">
						你好，<?php echo (session('USER_KEY')); ?> 
					              
							<i class="icon_gray_arrows"></i>
					</a>
					<a data-widget="#message_slide" href="#" class="option top_msg top_msg_new top_colorhover" id="message_slide">(UID: <?php echo (session('USER_KEY_ID')); ?> )</a>
					<a data-widget="#message_slide" href="#" class="option top_msg top_msg_new top_colorhover" id="message_slide">
						会员等级:
							<?php if(($total > 49)): ?>钻石会员
								<?php elseif( ($total < 10) and ($total > 4) ): ?>铜牌会员
								<?php elseif( ($total < 20) and ($total > 9) ): ?>银牌会员
								<?php elseif( ($total < 50) and ($total > 19) ): ?>金牌会员
								<?php else: ?> 普通会员<?php endif; ?>
					</a>
					<a href="<?php echo U('Login/loginOut');?>" class="option">退出</a>
					<i class="seg"></i>
					<a  href="#" class="option last" id="menu_slide">我的账户</a>
					<i class="seg sub_show"></i>
				<div data-widget="#user_slide" class="slide_box user_slide_box user_boxsize" id="user_slide_box">
				    <div class="clear"><ul class="balance_list"><h4>可用余额</h4><li><a href="javascript:void(0)"><em style="margin-top: 5px;" class="deal_list_pic_cny"></em><strong>人民币：</strong><span><?php echo ($member["rmb"]); ?></span></a></li></ul><ul class="freeze_list"><h4>委托冻结</h4><li><a href="javascript:void(0)"><em style="margin-top: 5px;" class="deal_list_pic_cny"></em><strong>人民币：</strong><span><?php echo ($member["forzen_rmb"]); ?></span></a></li></ul></div>
				    <div class="mywallet_btn_box" style=" margin-left:20px;"><a  href="javascript:void(0)" onclick="confirm_fill();"  >充值</a><a href="<?php echo U('User/draw');?>">提现</a><a href="<?php echo U('User/index');?>">转入</a><a href="<?php echo U('User/index');?>">转出</a><a href="<?php echo U('Entrust/manage');?>">委托管理</a><a href="<?php echo U('Trade/myDeal');?>">成交查询</a></div>
				</div>
				
				<div class="slide_box menu_slide_box menubox_position" id="menu_slide_box">
					<div class="bd">
						<dl class="menu_1">
							<dd><a href="<?php echo U('User/index');?>">我的资产</a></dd>
							<dd><a href="<?php echo U('Entrust/manage');?>">我的交易</a></dd>
							<!--<dd><a href="<?php echo U('User/zhongchou');?>">我的众筹</a></dd>-->
							<dd><a   href="javascript:void(0)" onclick="confirm_fill();"  >人民币充值</a></dd>
							<dd><a href="<?php echo U('User/draw');?>" >人民币提现</a></dd>
							<dd><a href="<?php echo U('User/index');?>">充积分提积分</a></dd>
				            <dd><a href="<?php echo U('User/updatePassword');?>">修改密码</a></dd>
							<dd><a href="<?php echo U('User/sysMassage');?>">系统消息<span class="messagenum" id="messagenum_common"><?php echo ($count); ?></a></dd>
						</dl>
					</div>
				</div>
				<script>
					$(document).ready(function(){
						$("#menu_slide").mouseenter(
							function(){
								$("#menu_slide_box").show();
							}
						).mouseout(
							function(){
								$("#menu_slide_box").hover(
									function(){
										$("#menu_slide_box").show();
									},
									function(){
									$("#menu_slide_box").hide();
									}
								)
								$("#menu_slide_box").hide();
							}
						)
						
						$("#user_slide").mouseenter(
							function(){
								$("#user_slide_box").show();
							}
						).mouseout(
							function(){
								$("#user_slide_box").hover(
									function(){
										$("#user_slide_box").show();
									},
									function(){
									$("#user_slide_box").hide();
									}
								)
								$("#user_slide_box").hide();
							}
						)
					})
					
					</script>
					<div data-widget="#head_lang" class="slide_box lang_slide_box" id="head_lang_box">
						<a href="#" data-lang="en" class=""><i class="icon_lang icon_lang_en"></i>&nbsp;&nbsp;English</a>
					</div><?php endif; ?>
				<?php if(empty($member)): ?><a class="option icon_position" href="<?php echo U('Login/index');?>">[登录]</a>
					<!-- <i class="seg"></i> -->
				<a rel="nofollow" href="<?php echo U('Reg/reg');?>">
					<span style="color:#FF8938">[免费注册]</span>
				</a><?php endif; ?>
				
        </div>
     <div class="float_right head_user_option" style="margin-right:40px">
     			<a class="option icon_position" href="http://wpa.qq.com/msgrd?v=3&uin=524760321&site=qq&menu=yes" target="__blank">在线咨询</a>
     			<i class="seg"></i>
     			<a class="option icon_position" href="<?php echo U('Art/details',array('team_id'=>325));?>">关于我们</a>
     			<i class="seg"></i>
     			<a class="option icon_position" href="<?php echo U('Help/index');?>">新手引导</a>
     			<i class="seg"></i>
				<a class="option icon_position" href="<?php echo U('Help/index');?>">帮助中心</a>
				<b>交易时间：9:00-21:00</b>
     </div>
    </div>
</div>
<hr style="margin:0" />
<div id="doc_head" >
    <div class="head_masthead">
        <div class="section relative clear" style="min-width:1200px">
            <h1 class="head_logo">
                <img style=" height:68px;" src="<?php echo ($config["logo"]); ?>" />
            </h1>
			<?php if(!empty($member)): ?><!-- 登录后 显示资产-->
            <div class="head_balance float_right" style="margin-right:40px">
                <div data-widget="#head_balance" class="bar" id="head_balance" data-currency="" style="position:relative;z-index:2;right:-20%"  data-allow_cny="" data-allow_usd="">
                                           总资产
                <i class="symbol font_14">¥</i> 
                <span data-flaunted="1" data-flaunt="0.0000" class="convert_net_btc font_14" style="font-weight: 400;" id='total_common_money1'>--<?php echo ($member['rmb']+$member['forzen_rmb']+$sum); ?>--</span>
                       <!-- <i data-visible="visible" id="flaunt" class="icon_eye"></i> -->
                    <div class="handle">
                        <i class="icon_white_arrows"></i>
                    </div>
                </div>
                <div data-widget="#head_balance" class="info" style="right:-20%"  id="head_balance_box">
                    <dl>
                                                <dt class="total">
                            <span class="label">总资产</span>
                            <span class="font_orange"><i class="symbol">¥</i><b style="font-weight: 400;" data-flaunted="1" data-flaunt="0.0000" class="convert_total_btc" id='total_common_money'>--<?php echo ($member['rmb']+$member['forzen_rmb']+$sum); ?>--</b> </span>
                        </dt>
                        <!-- <dt class="total_2">
                            <span class="label">净资产</span>
                            <span class="font_orange"><i class="symbol">฿</i> <b style="font-weight: 400;" data-flaunted="1" data-flaunt="0.0000" class="convert_net_btc">0.0000</b> </span>
                        </dt> -->
                        
                        <dt>人民币现货账户</dt>
                        <dd>
                            <span class="label">可用</span>
                            <span class="c_1"><i class="symbol">¥</i> <b style="font-weight: 400;" data-flaunted="1" data-flaunt="0.00" class="cny_cny_available"><?php echo ((isset($member["rmb"]) && ($member["rmb"] !== ""))?($member["rmb"]):0.00); ?></b> </span>
                        </dd>
                        <dd>
                            <span class="label">冻结</span>
                            <span class="c_1"><i class="symbol">¥</i> <b style="font-weight: 400;" data-flaunted="1" data-flaunt="0.00" class="cny_cny_frozen"><?php echo ((isset($member["forzen_rmb"]) && ($member["forzen_rmb"] !== ""))?($member["forzen_rmb"]):0.00); ?></b> </span>
                        </dd>
                        <dd class="ratio cny_loan_rate_yes" style="display: none;">
                            资产/杠杆 <i class="font_orange">=</i> <b style="font-weight: 400;" data-flaunted="1" data-flaunt="0.00" class="font_orange cny_risk_rate">0.00</b><b class="font_orange">%</b>  &nbsp;
                            <span class="cny_loan_burst cny_loan_burst_price_no" style="">(爆仓值 110%)</span>
                            <span class="cny_loan_burst cny_loan_burst_price_btc" style="display: none;">(预估爆仓价比特币 <b class="font_orange">¥</b><b class="price font_orange">0</b>)</span>
                            <span class="cny_loan_burst cny_loan_burst_price_ltc" style="display: none;">(预估爆仓价莱特币 <b class="font_orange">¥</b><b class="price font_orange">0</b>)</span>
                        </dd>
                    </dl>
                    <div class="shortcut">
                                                <a href="javascript:void(0)" onclick="confirm_fill();" class="btn_orange">充值</a>
                        
                                                <a href="<?php echo U('User/draw');?>" class="btn_white">提现</a>
                                                
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- 登录前 头部显示 登录-->
             <div class="head_login">
                <a href="<?php echo U('Reg/reg');?>"<?php if(($style) == "2"): ?>class="login_btn"<?php endif; ?>>注册</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="<?php echo U('Login/index');?>"<?php if(($style) == "1"): ?>class="login_btn"<?php endif; ?>>登录</a>
                &nbsp;&nbsp;&nbsp;
            </div><?php endif; ?>
            <div class="head_nav" id="head_nav">
                 <ul>
            		<li>
            			<a href="<?php echo U('Index/index');?>">首页</a>
            		</li>
            		<li class="multi">
            			<a href="<?php echo U('Orders/currency_trade');?>">交易中心</a>
               			<div class="sub">
							<?php if(is_array($currency_header)): $i = 0; $__LIST__ = $currency_header;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="qh_sub">
                            		<a href="<?php echo U('Orders/index',array('currency'=>$vo['currency_mark']));?>">
                            			<img src="<?php echo ($vo["currency_logo"]); ?>"><?php echo ($vo["currency_name"]); ?>
                            		</a>
                            	</div>
                            	<!-- <div class="qh_sub">
                            		<a href="<?php echo U('Entrust/manage');?>">
                            			<img src="/Public/Home/images/qh_btb.png">委托管理
                            		</a>
                            	</div>
                            	<div class="qh_sub">
                            		<a href="<?php echo U('Entrust/manage');?>">
                            			<img src="/Public/Home/images/qh_btb.png">委托管理
                            		</a>
                            	</div>
                            	<div class="qh_sub">
                            		<a href="<?php echo U('Entrust/manage');?>">
                            			<img src="/Public/Home/images/qh_btb.png">委托管理
                            		</a>
                            	</div> --><?php endforeach; endif; else: echo "" ;endif; ?> 
                		</div>
            		</li>
         			<li>
         				<a href="<?php echo U('Zhongchou/index');?>">项目简介</a>
         			</li>
            		<li>
            			<a href="<?php echo U('Safe/index');?>">财务中心</a>
            		</li>
            		<!-- <li>
            			<a href="<?php echo U('Help/index',array('id'=>60));?>">安全中心</a>
            		</li> -->
            		<li>
            			<a href="<?php echo U('Art/index',array('ramdon_id'=>'1'));?>">公告</a>
            		</li>
            		<li>
            			<a href="<?php echo U('Vote/vote');?>">活动中心</a>
            		</li>
            		<!-- <li>
            			<a href="<?php echo U('Market/index');?>">行情中心</a>
            		</li> -->
            		<!--<li>
            			<a href="<?php echo U('Dow/index');?>">下载中心</a>
            		</li> -->
        		</ul>
                 
           	</div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
//异步加载个人资产以及系统未读消息条数信息  GS
$(function(){
	var session_id = <?php echo (session('USER_KEY_ID')); ?>+0?<?php echo (session('USER_KEY_ID')); ?>:0;
	if(session_id != 0)
	$.ajax({
		url:"<?php echo U('Index/personinfo');?>",
		type:'POST',
		dataType:'Json',
		success:function (data){
			//console.log(data);

			if(data.newMessageCount == 'false'){
				data.newMessageCount = '';
			}
			$('#total_common_money').html(data.allmoneys);
			$('#total_common_money1').html(data.allmoneys);
			if(data.newMessageCount == 0){
					$('#messagenum_common').remove();
					$('#messagenum2').remove();
				}else{
					$('#messagenum_common').html(data.newMessageCount);
					$('#messagenum2').html(data.newMessageCount);
				}
		}
	});
})

</script>


<div class="qh_kong"></div>
<script>
$(document).ready(function(){
	$("#head_balance").mouseenter(
		function(){
			$("#head_balance_box").show();
		}
	).mouseout(
		function(){
			$("#head_balance_box").hover(
				function(){
					$("#head_balance_box").show();
				},
				function(){
				$("#head_balance_box").hide();
				}
			)
			$("#head_balance_box").hide();
		}
	)
	
	$("#head_balance").mouseenter(
		function(){
			$("#head_balance_box").show();
		}
	).mouseout(
		function(){
			$("#head_balance_box").hover(
				function(){
					$("#head_balance_box").show();
				},
				function(){
				$("#head_balance_box").hide();
				}
			)
			$("#head_balance_box").hide();
		}
	)
})

</script>
<div class="pclxfsbox"> 
		<ul> 
			<!-- <li>
				<i class="pcicon1 iscion6" ></i>
				<div class="pcicon1box">
					<div class="iscionbox">
						<p>在线咨询</p>
						<p><?php echo ((isset($config['worktime']) && ($config['worktime'] !== ""))?($config['worktime']):"暂无"); ?></p>
					</div>
					<i></i>
				</div>
			</li> -->
			<li> 
				<i class="pcicon1 iscion1"></i>
				<div class="pcicon1box">
					<div class="iscionbox">
						<p><img src="<?php echo ($config['weixin']); ?>" alt="微信公众号" width="90"></p>
						<p><?php echo ($config["name"]); echo ((isset($config["micromsgname"]) && ($config["micromsgname"] !== ""))?($config["micromsgname"]):"微信公众号"); ?></p>
					</div>
					<i></i>
				</div>
			</li>
		

            <li>
				<i class="pcicon1 iscion3">
					<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($config["qq1"]); ?>&site=qq&menu=yes"></a>
				</i>
				<div class="pcicon1box">
					<div class="iscionbox">
						<p><?php echo ((isset($config["qq1"]) && ($config["qq1"] !== ""))?($config["qq1"]):"暂无"); ?></p>
						<p><?php echo ($config["name"]); echo ((isset($config["qqkufuname"]) && ($config["qqkufuname"] !== ""))?($config["qqkufuname"]):"QQ客服"); ?></p>
					</div>
					<i></i>
				</div>
			</li>

			<li>
				<i class="pcicon1 iscion4"></i>
				<div class="pcicon1box">
					<div class="iscionbox">
						<p>返回顶部</p>
					</div>
					<i></i>
				</div>
			</li>
		</ul>
	</div>
<script type="text/javascript">
	function confirm_fill(){
			window.location.href="<?php echo U('User/pay');?>";
	}
			
</script>
<script type="text/javascript"> 
		$(function(){
			$(".pcicon1").on("mouseover",function(){
				$(this).addClass("lbnora").next(".pcicon1box").css({"width":"148px"});
			}).on("mouseout",function(){
				$(this).removeClass("lbnora").next(".pcicon1box").css("width","0px");
			});
			$(".iscion4").on("click",function(){
				$("html, body").animate({
					scrollTop: 0
				})
			});

			var objWin;
			$("#opensq").on("click",function(){
				var top = window.screen.height/2 - 250;
				var left = window.screen.width/2 - 390;
				var target = "http://p.qiao.baidu.com//im/index?siteid=8050707&ucid=18622305"; 
				var cans = 'width=780,height=550,left='+left+',top='+top+',toolbar=no, status=no, menubar=no, resizable=yes, scrollbars=yes' ;

				if((navigator.userAgent.indexOf('MSIE') >= 0)&&(navigator.userAgent.indexOf('Opera') < 0)){
						//objWin = window.open ('','baidubridge',cans) ; 
						if (objWin === undefined || objWin === null || objWin.closed) { 
							objWin = window.open (target,'baidubridge',cans) ; 
						}else { 
							objWin.focus();
						}
				}else{
					var win = window.open('','baidubridge',cans );
					if (win.location.href == "about:blank") {
					    //窗口不存在
					    win = window.open(target,'baidubridge',cans);
					} else {
					    win.focus();
					}
				}
				return false;

			})
		})
		
	</script>
<!--top end-->

    <link rel="stylesheet" type="text/css" href="/Public/Home/css/kline.css">
    <script type="text/javascript" src="/Public/js/jquery-2.1.1.min.js"></script>
   	<script src="/Public/Home/js/kline.js"></script>
	<script src="/Public/Home/js/highstock.js"></script>
	
<script>
$(function() {
	$.get("/Home/Orders/getOrdersKline",{"currency":$("#currency_id").val(),"time":"kline_1h"},function(orders){
        kline($("#currency_mark").html()+"2"+$("#currency_trade_mark").html(),orders.kline_1h);

	});
	$("#chart-control > button").click(function(){
		$(this).addClass("btn-success").siblings().removeClass("btn-success");
			 var time = $(this).attr('data-time');
			 $.get("/Home/Orders/getOrdersKline",{"currency":$("#currency_id").val(),"time":time},function(orders){
			 kline($("#currency_mark").html()+"2"+$("#currency_trade_mark").html(),orders[time]);
		});
	});
});
</script>

<style>
	.my_coin ul li{ font-size:15px; width:180px; height:24px; overflow:hidden;}
  .now_price.right li{
    margin-right: 40px;
  }
</style>
<div id="" style="background:#fbfaf8; padding-top:0px;">
  <div class="total_top" style="margin-bottom: 20px;">
    <div class="price" style="width:1200px">
      <img style=" float:left; width:50px; height:50px; padding-right:10px;" src="<?php echo ($currency["currency_logo"]); ?>" />
      <div class="left coin_coin"><?php echo ((isset($currency["currency_name"]) && ($currency["currency_name"] !== ""))?($currency["currency_name"]):"虚拟币"); ?>对<?php echo ((isset($currency_trade["currency_name"]) && ($currency_trade["currency_name"] !== ""))?($currency_trade["currency_name"]):"人民币"); ?> <br>
        <span id="currency_mark"><?php echo ((isset($currency["currency_mark"]) && ($currency["currency_mark"] !== ""))?($currency["currency_mark"]):"XNB"); ?></span> /<span id="currency_trade_mark"> <?php echo ((isset($currency_trade["currency_mark"]) && ($currency_trade["currency_mark"] !== ""))?($currency_trade["currency_mark"]):"CNY"); ?></span></div>
      <ul class="right now_price">
      	<li>实时汇率<br>
            <span class="money" id="new_price"><?php echo ((isset($currency_message["new_price"]) && ($currency_message["new_price"] !== ""))?($currency_message["new_price"]):"0.000"); ?>/<?php echo ((isset($currency_trade["currency_name"]) && ($currency_trade["currency_name"] !== ""))?($currency_trade["currency_name"]):"人民币"); ?></span></li>
        <li>最新价<br>
            <span class="money" id="new_price"><?php echo ((isset($currency_message["new_price"]) && ($currency_message["new_price"] !== ""))?($currency_message["new_price"]):"0.000"); ?></span></li>
        <li>买一价<br>
            <span id="24h_sell"><?php echo (number_format($currency_message["buy_one_price"],$currency['currency_digit_num'])); ?></span></li>
        <li>卖一价<br>
            <span id="24h_buy"><?php echo (number_format($currency_message["sell_one_price"],$currency['currency_digit_num'])); ?></span></li>
        <li>最高价<br>
            <span id="24h_max"><?php echo ((isset($currency_message["max_price"]) && ($currency_message["max_price"] !== ""))?($currency_message["max_price"]):"0.000"); ?></span></li>
        <li>最低价<br>
            <span id="24h_min"><?php echo ((isset($currency_message["min_price"]) && ($currency_message["min_price"] !== ""))?($currency_message["min_price"]):"0.000"); ?></span></span></span></li>      
        <li>24H成交量<br>
            <span id="24h_count"><?php echo ((isset($currency_message["24H_done_num"]) && ($currency_message["24H_done_num"] !== ""))?($currency_message["24H_done_num"]):"0.000"); ?></span></li>
  		  <li>总成交额<br>
            <span id="24h_count"><?php echo ((isset($currency_message["trade_total"]) && ($currency_message["trade_total"] !== ""))?($currency_message["trade_total"]):"0.000"); ?></span></li>
      </ul>
      <div class="clear"></div>
    </div>
  </div>
  <div class="main_box"> 
    <!--普通-->
    <!--  <div class="" class="qh_k" id="k-cus-box" style="height:500px;width:65%;float:left">
		<script type="text/javascript" src="//static2.sosobtc.com/sb.js"></script>
				<script type="text/javascript">
					new SOSOBTC.widget({
						"symbol": "<?php echo ($_GET['currency']); ?>:100BI",
						"default_theme": "dark",
						"disable_theme_change": true,
						"container": "kline_container"
					})
				</script>
				
    </div>
   
    -->
    <div class="" class="qh_k" id="k-cus-box" style="height:500px;width:65%;float:left">
		<div id="graphbox" style="width:100%;height:455px;margin:0px; float:left;">
        <div id="container" style="height: 410px; min-width: 460px"></div>
        <div id="chart-control" data="ybc" class="btn-group centered" style="width: 98%;height: 30px;line-height: 30px; marign:0 auto;text-align:center">
          <button data-time="kline_5m" class="btn">5分钟线</button>
          <button data-time="kline_15m" class="btn">15分钟线</button>
          <button data-time="kline_30m" class="btn">30分钟线</button>
          <button data-time="kline_1h" class="btn btn-success">1小时线</button>
          <button data-time="kline_8h" class="btn">8小时线</button>
          <button data-time="kline_1d" class="btn ">日线</button>
        </div>
      </div>
    </div>

    

    <div class="qh_table" style="float:left;margin-left:5%;margin-top:20px;height:980px">
      <div style="margin-top:0px;">
        <h2 style="color:#FF8938;font-size:16px;font-weight:600">市场挂单</h2>
      </div>
      <table cellspacing="0" cellpadding="0" border="0" class="latest_list weituo" align="center" style="border-bottom:1px solid #d2d2d2;font-size:15px">
      <thead>
          <tr style="height:26px;">
            <th style="height:26px; text-align:center; width:55px;background:#f6f6f6" class="left_sell">卖 / 买</th>
            <th style="height:26px; width:70px;background:#f6f6f6;text-align:center;padding-left:4px">价格</th>
            <th style="height:26px;background:#f6f6f6;text-align:center;padding-left:4px">委托量</th>
            <th style="height:26px;background:#f6f6f6;text-align:center;padding-left:4px">总计</th>
          </tr>
        </thead>
        <tbody id="coinsalelist">
          <?php if(is_array($sell_record)): $k = 0; $__LIST__ = $sell_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="list_con2"  onclick="getsell(this)"  onmouseover="chufa1(this)" onmouseout="likai1(this)">
              <td class="sell left_sell" style="width:55px;">卖(<?php echo count($sell_record)-$k+1 ?>)</td>
              <td style="width:70px;"  class="sell left_sell">¥<?php echo (number_format($vo["price"],$currency['currency_digit_num'])); ?></td>
              <!-- <td class="sell left_sell" ><?php if(($currency["currency_name"]) == "比特币"): echo ($currency["currency_unit"]); echo (number_format($vo['num']-$vo['trade_num'],3)); ?> <?php else: ?> <?php echo ($currency["currency_unit"]); echo (number_format($vo['num']-$vo['trade_num'],4)); endif; ?></td> -->
              <td class="buy left_sell" ><?php if(($currency["currency_name"]) == "比特币"): echo ($currency["currency_unit"]); echo ($vo['not_trade_num']); ?> <?php else: ?> <?php echo ($currency["currency_unit"]); echo ($vo['not_trade_num']); endif; ?></td>
              <td class="sell left_sell" style="width:80px;">¥<?php echo (number_format($vo['price'] * ($vo['num']-$vo['trade_num']),2)); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
      </table>
      <table cellspacing="0" cellpadding="0" border="0" class="latest_list weituo" align="center" style="font-size:15px">
        
        <tbody id="coinbuylist">
          <?php if(is_array($buy_record)): $k = 0; $__LIST__ = $buy_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="list_con2" onclick="buynum(this)" onmouseover="chufa(this)" onmouseout="likai(this)">
              <td class="buy left_sell" style="width:55px;">买(<?php echo ($k); ?>)</td>
              <td class="buy left_sell" style="width:70px;">¥<?php echo (number_format($vo["price"],$currency['currency_digit_num'])); ?></td>
              <!-- <td class="buy left_sell" ><?php if(($currency["currency_name"]) == "比特币"): echo ($currency["currency_unit"]); echo (number_format($vo['num']-$vo['trade_num'],3)); ?> <?php else: ?> <?php echo ($currency["currency_unit"]); echo (number_format($vo['num']-$vo['trade_num'],4)); endif; ?></td> -->
              <td class="buy left_sell" ><?php if(($currency["currency_name"]) == "比特币"): echo ($currency["currency_unit"]); echo ($vo['not_trade_num']); ?> <?php else: ?> <?php echo ($currency["currency_unit"]); echo ($vo['not_trade_num']); endif; ?></td>
              <td class="buy left_sell" style="width:80px;">¥<?php echo (number_format($vo['price'] * ($vo['num']-$vo['trade_num']),2)); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    
      </table>
    </div>
                 <!--交易币种id-->
				 
	<input type="hidden" id="currency_id" value="<?php echo ($currency['currency_id']); ?>">
    <div style=" width:100%;">
    <div style=" width:65% !important; float:left !important;margin-top:-520px">
      <div class="my_coin"> 
        <!--登录后-->
        <?php if(!empty($session)): ?><ul>

            <li>我的<?php echo ((isset($currency["currency_name"]) && ($currency["currency_name"] !== ""))?($currency["currency_name"]):"虚拟币"); ?>资产：</li>
            <li>可用：<span class="sell" id="from_over"><?php echo ((isset($user_currency_money['currency']['num']) && ($user_currency_money['currency']['num'] !== ""))?($user_currency_money['currency']['num']):0.00); ?></span></li>
            <li>冻结：<span class="buy" id="from_lock"><?php echo ((isset($user_currency_money['currency']['forzen_num']) && ($user_currency_money['currency']['forzen_num'] !== ""))?($user_currency_money['currency']['forzen_num']):0.00); ?></span></li>
            <li>总量：<span style="color:#333;" id="from_total"><?php echo ($user_currency_money['currency']['num']+$user_currency_money['currency']['forzen_num']); ?></span></li>
            <div class="clear"></div>
          </ul>
          <ul>
            <li>我的<?php echo ((isset($currency_trade["currency_name"]) && ($currency_trade["currency_name"] !== ""))?($currency_trade["currency_name"]):"人民币"); ?>资产：</li>
            <li>可用：<span class="sell" id="to_over"><?php echo ((isset($user_currency_money['currency_trade']['num']) && ($user_currency_money['currency_trade']['num'] !== ""))?($user_currency_money['currency_trade']['num']):0.00); ?></span></li>
            <li>冻结：<span class="buy" id="to_lock"><?php echo ((isset($user_currency_money['currency_trade']['forzen_num']) && ($user_currency_money['currency_trade']['forzen_num'] !== ""))?($user_currency_money['currency_trade']['forzen_num']):0.00); ?></span></li>
            <li>总量：<span style="color:#333;" id="to_total"><?php echo ($user_currency_money['currency_trade']['num']+$user_currency_money['currency_trade']['forzen_num']); ?></span></li>
            <div class="clear"></div>
          </ul>
		   <?php if(($config["jiaoyitishi"]) == "1"): if(($_GET['currency']) == "G"): ?><div align='center' style='color:red;'><?php echo ($currency["currency_name"]); ?>今日的涨停价格：<?php echo ($up); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 跌停价格：<?php echo ($down); ?></div><?php endif; endif; ?>
          <?php else: ?>
          <p>我的资产：<a href="<?php echo U('Login/index');?>">登录</a> | <a href="<?php echo U('Reg/reg');?>">注册</a></p><?php endif; ?>
      </div>
    </div>
    <div style=" width:38% !important; float:right !important;">
      <p class="more_coin" style="padding-left:32px;margin-bottom:20px"><a href="http://<?php echo ((isset($currency["aa"]) && ($currency["aa"] !== ""))?($currency["aa"]):''); ?> " target="_blank">查看货币详情</a></p>

        <h2 style=" margin-left:19%;color:#FF8938;font-size:16px;font-weight:600">&nbsp&nbsp最新成交<a href="<?php echo U('Trade/myDeal');?>" class="right my_coin_trade">我的成交</a></h2>
        <div class="buysell" style="float:right;margin-top:0px;padding:0px">
          <ul class="record_title" style="background:#f6f6f6;font-size:15px">
            <li style="width:70px;padding-left:10px">成交时间</li>
            <li style="width:50px;padding-left:20px">类型</li>
            <li style="width:70px;">成交价格</li>
            <li style="width:70px;padding-left:10px">成交量</li>
            <li style="width:50px;padding-left:10px">总计</li>
          </ul>
          <div class="record" style="float:left;background:#f6f6f6">
            <table class="latest_list record_list" align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:12px;background:#f6f6f6">
              <tbody id="coinorderlist" style=" background:#f6f6f6">
                <?php if(is_array($trade)): $i = 0; $__LIST__ = $trade;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                  <td class="list_con1"><?php echo (date(' H:i:s',$vo["add_time"])); ?></td>
                  <td class="list_con1 <?php echo ($vo["type"]); ?>"><?php if(($vo["type"]) == "sell"): ?>卖<?php else: ?>买<?php endif; ?></td>
                  <td class="list_con1 <?php echo ($vo["type"]); ?>"><?php echo (number_format($vo["price"],$currency['currency_digit_num'])); ?></td>
                  <td class="list_con1"><?php echo (number_format($vo["num"],2)); ?></td>
                  <td class="list_con1"><?php echo (number_format($vo['num']*$vo['price'],2)); ?></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
              </tbody>
            </table>
          </div>
        </div>



       
      <!-- <div style="margin-top:0px;">
        <h2>委托信息</h2>
      </div>
      <table cellspacing="0" cellpadding="0" border="0" class="latest_list weituo" align="center" style="border:1px solid #d2d2d2;">
        <tbody id="coinsalelist">
          <?php if(is_array($sell_record)): $k = 0; $__LIST__ = $sell_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="list_con2">
              <td class="sell left_sell" style="width:125px">卖(<?php echo count($sell_record)-$k+1 ?>)</td>
              <td style="width:70px;"><?php echo ((isset($vo["price"]) && ($vo["price"] !== ""))?($vo["price"]):0.00); ?></td>
              <td><?php echo ($vo['num']-$vo['trade_num']); ?></td>
              <td style="width:80px;"><span style="width:<?php echo ($vo["bili"]); ?>%" class="sellSpan"></span></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
      </table>
      <table cellspacing="0" cellpadding="0" border="0" class="latest_list weituo" align="center" style="border:1px solid #d2d2d2;">
        <thead>
          <tr style="height:26px;">
            <th style="height:26px; text-align:center; width:125px;" class="left_sell">卖 / 买</th>
            <th style="height:26px; width:70px;">价格</th>
            <th style="height:26px;">委托量</th>
            <th style="height:26px;">&nbsp;</th>
          </tr>
        </thead>
        <tbody id="coinbuylist">
          <?php if(is_array($buy_record)): $k = 0; $__LIST__ = $buy_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="list_con2">
              <td class="buy left_sell" style="width:125px;">买(<?php echo ($k); ?>)</td>
              <td style="width:70px;"><?php echo ((isset($vo["price"]) && ($vo["price"] !== ""))?($vo["price"]):0.00); ?></td>
              <td><?php echo ($vo['num']-$vo['trade_num']); ?></td>
              <td style="width:80px;"><span style="width:<?php echo ($vo["bili"]); ?>%" class="buySpan"></span></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody> -->
    
    
    
       
      <!-- <div class="qh_table">
      <div style="margin-top:0px;">
        <h2>委托信息</h2>
      </div>
      <table cellspacing="0" cellpadding="0" border="0" class="latest_list weituo" align="center" style="border:1px solid #d2d2d2;">
        <tbody id="coinsalelist">
          <?php if(is_array($sell_record)): $k = 0; $__LIST__ = $sell_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="list_con2">
              <td class="sell left_sell" style="width:55px;">卖(<?php echo count($sell_record)-$k+1 ?>)</td>
              <td style="width:70px;" onclick="getsell(this)"><?php echo ((isset($vo["price"]) && ($vo["price"] !== ""))?($vo["price"]):0.00); ?></td>
              <td onclick="sellnum(this)"><?php echo ($vo['num']-$vo['trade_num']); ?></td>
              <td style="width:80px;"><span style="width:<?php echo ($vo["bili"]); ?>%" class="sellSpan"></span></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
      </table>
      <table cellspacing="0" cellpadding="0" border="0" class="latest_list weituo" align="center" style="border:1px solid #d2d2d2;">
        <thead>
          <tr style="height:26px;">
            <th style="height:26px; text-align:center; width:55px;" class="left_sell">卖 / 买</th>
            <th style="height:26px; width:70px;">价格</th>
            <th style="height:26px;">委托量</th>
            <th style="height:26px;">&nbsp;</th>
          </tr>
        </thead>
        <tbody id="coinbuylist">
          <?php if(is_array($buy_record)): $k = 0; $__LIST__ = $buy_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="list_con2">
              <td class="buy left_sell" style="width:55px;" onclick="getbuy(this);">买(<?php echo ($k); ?>)</td>
              <td style="width:70px;" onclick="buynum(this);"><?php echo ((isset($vo["price"]) && ($vo["price"] !== ""))?($vo["price"]):0.00); ?></td>
              <td><?php echo ($vo['num']-$vo['trade_num']); ?></td>
              <td style="width:80px;"><span style="width:<?php echo ($vo["bili"]); ?>%" class="buySpan"></span></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    
      </table>
      </div> -->
    </div>
    <div style=" width:62% !important; float:left !important;margin-top:-400px">
      <div class="curve pay">
        <div class="buysell sellform">
          <div class="buyformarea left">
            <h2 class="buy">买入<?php echo ((isset($currency["currency_name"]) && ($currency["currency_name"] !== ""))?($currency["currency_name"]):"虚拟币"); ?> </h2>
            <ul class="buyform">
              <!-- <li>
                <label>最佳买价：</label>
                <h3 id="coinbuy_nice" class="buy left"><?php echo ((isset($sell_record[count($sell_record)-1]['price']) && ($sell_record[count($sell_record)-1]['price'] !== ""))?($sell_record[count($sell_record)-1]['price']):"0.000"); ?></h3>
              </li> -->
              <li>
                <label for="price" class="buys">买入价格：</label>
                <input value="<?php echo (number_format($sell_record[count($sell_record)-1]['price'],$currency['currency_digit_num'])); ?>" style="color:#999" name="buyprice" id="coinpricein" onkeyup="vNum(this,<?php echo ($currency["currency_digit_num"]); ?>);zuidakemai();" onclick="if(value==defaultValue){value='';}" onblur="if(value==''){value='0.000';}" type="text">
              </li>
              <li>
                <label for="num" class="buys">最大可买：</label>
                <b id="coinbuy_max"  title="点击将数值写入数量输入框"></b><span class="maxcoin" id = "coinbuy_aa"><?php echo (sprintf('%.4f',$user_currency_money['currency_trade']['num']/$sell_record[count($sell_record)-1]['price'])); ?></span> </li>
              <li>
                <label for="buynum" class="buys">买入数量：</label>
                <input style="display:none;">
                <!-- for disable autocomplete on chrome -->
                <input name="buynum" id="numberin" onkeyup="vNum(this,<?php echo ($currency["currency_digit_num"]); ?>);" autocomplete="off" type="text" value="">
              </li>
              <li>
                <label for="buyword" class="buys">交易密码：</label>
                <input style="display:none;">
                <!-- for disable autocomplete on chrome -->
                <input class="buyinput"  id="pwdtradein" value="<?php echo (session('tradepwd')); ?>"  autocomplete="off" type="password" name="buypwd">
              </li>
              <li>
                <label for="num" class="buys">交易额 ：</label>
                <input id="coinin_sumprice" type="text" onkeyup='total_in(this,<?php echo ($currency["currency_digit_num"]); ?>);' name="total">
                <!-- <b id="coinin_sumprice">0.00</b> <?php echo ((isset($currency_trade["currency_mark"]) && ($currency_trade["currency_mark"] !== ""))?($currency_trade["currency_mark"]):"CNY"); ?> --></li>
              <li>
                <label for="num" class="buys">手续费 ：</label>
                <b id="feebuy">0.00</b><span>（<?php echo ((isset($currency['currency_buy_fee']) && ($currency['currency_buy_fee'] !== ""))?($currency['currency_buy_fee']):"0.00"); ?>% <?php echo ((isset($currency["currency_mark"]) && ($currency["currency_mark"] !== ""))?($currency["currency_mark"]):"--"); ?>）</span></li>
              
            </ul>
            <p class="sellform">
              <input id="trustbtnin" onclick="buy()" class="submit" value="买入" type="button">
            </p>
            <p class="sellform" style="margin:15px 0;"><span id="trustmsgin" class="tishi">实体积分交易具有极高的风险，投资需谨慎！</span></p>
          </div>
          
          
          <div class="buyformarea right">
            <h2 class="sell">卖出<?php echo ((isset($currency["currency_name"]) && ($currency["currency_name"] !== ""))?($currency["currency_name"]):"虚拟币"); ?> </h2>
            <ul class="buyform sellform">
              <!-- <li>
                <label>最佳卖价：</label>
                <h3 id="coinsale_nice" class="sell left"><?php echo ((isset($buy_record[0]['price']) && ($buy_record[0]['price'] !== ""))?($buy_record[0]['price']):"0.000"); ?></h3>
              </li>-->
              <li> 
                <label for="price" class="buys">卖出价格：</label>
                <input value="<?php echo (number_format($buy_record[0]['price'],$currency['currency_digit_num'])); ?>" style="color:#999" class="buyinput" id="coinpriceout" onkeyup="vNum2(this,<?php echo ($currency["currency_digit_num"]); ?>);" onclick="if(value==defaultValue){value='';}" onblur="if(value==''){value='0.000';}" type="text">
               
              </li>
              <li>
                <label for="num" class="buys">最大可卖：</label>
                <b id="coinsale_max" onclick="$('#numberout').val(this.innerHTML)" title="点击将数值写入数量输入框"><?php echo ((isset($user_currency_money['currency']['num']) && ($user_currency_money['currency']['num'] !== ""))?($user_currency_money['currency']['num']):0.00); ?></b><span></span> </li>
              <li>
                <label for="buynum" class="buys">卖出数量：</label>
                <input style="display:none;">
                <!-- for disable autocomplete on chrome -->
                <input class="buyinput" id="numberout" onkeyup="vNum2(this,<?php echo ($currency["currency_digit_num"]); ?>);" autocomplete="off" type="text">
              </li>
              <li>
                <label for="buyword" class="buys">交易密码：</label>
                <input style="display:none;">
                <!-- for disable autocomplete on chrome -->
                <input class="buyinput" id="pwdtradeout" value="<?php echo (session('tradepwd')); ?>" autocomplete="off" type="password">
              </li>
              <li>
                <label for="num" class="buys">交易额：</label>
                <input type="" name="" id="coinout_sumprice" onkeyup='total_out(this,<?php echo ($currency["currency_digit_num"]); ?>)'>
                <!-- <b id="coinout_sumprice">0.00</b><?php echo ((isset($currency_trade["currency_mark"]) && ($currency_trade["currency_mark"] !== ""))?($currency_trade["currency_mark"]):"CNY"); ?></li> -->
              <li>
                <label for="num" class="buys">手续费：</label>
                <b id="feesell">0.00</b><span>（<?php echo ((isset($currency["currency_sell_fee"]) && ($currency["currency_sell_fee"] !== ""))?($currency["currency_sell_fee"]):"0.00"); ?>% <?php echo ((isset($currency_trade["currency_mark"]) && ($currency_trade["currency_mark"] !== ""))?($currency_trade["currency_mark"]):"--"); ?>）</span></li>
              
            </ul>
            <p class="sellform2">
              <input class="submit" id="trustbtnout" value="卖出" onclick="sell();" type="button">
            </p>
            <p class="sellform" style="margin:15px 0;"><span id="trustmsgout" class="tishi">实体积分交易具有极高的风险，投资需谨慎！</span></p>
          </div>
          
          
          
          <?php if(!empty($session)): ?><div style="float:left">
      <h2 style="margin-top:100px;color:#FF8938;font-size:16px;font-weight:600">我的委托 <a href="<?php echo U('Entrust/manage');?>" class="right my_coin_trade">全部委托</a> </h2>
      <div style="border-bottom:2px solid #d2d2d2; color:#333;">
        <ul class="my_title" style="background:#f6f6f6">
          <li style=" width:115px; text-align:left; padding-left:10px;">时间</li>
          <li style=" width:120px; text-align:left; padding-left:10px;">数量</li>
          <li style=" width:130px; text-align:left; padding-left:10px;">价格</li>
          <li style=" width:120px; text-align:left; padding-left:10px;">类型</li>
          <li style=" width:130px; text-align:left; padding-left:10px;">交易额</li>
          <li style=" width:100px; text-align:left; padding-left:15px;">操作</li>
        </ul>
        <div class="my_record" style="float:left">
          <table class="latest_list weituo" style=" width:760px;" align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody id="mycointrustlist">
            <?php if(is_array($user_orders)): foreach($user_orders as $key=>$v): ?><tr class="list_con2">
              <td style="width:95px;"><?php echo (date('Y-m-d H:i:s',$v['add_time'])); ?></td>
              <td style="width:85px;" ><?php echo ($v['not_trade_num']); ?></td>
              <td style="width:75px;"><?php echo (number_format($v["price"],$currency['currency_digit_num'])); ?></td>
              <td class="<?php echo ($v['type']); ?> left_sell " style="width:100px;"><?php echo (fomatOrdersType($v['type'])); ?></td>
              <td style="width:100px;text-align:center"><?php echo (number_format($v['price']*($v['num']-$v['trade_num']),2)); ?></td>
              <td style="width:100px;text-align:center"><a href="javascript:void(0)"  onclick="cexiao(<?php echo ($v["orders_id"]); ?>)">撤销</a></td>
            </tr><?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    
      <?php else: ?>
      <!-- <div style="border:1px solid #e2e2e2; margin:15px 0; padding:15px; color:#333; background:#fff; line-height:20px; font-size:14px; text-align:left;">
              请先登录
        
        </div> --><?php endif; ?>
      <div class="clear"></div>

      <?php if(!empty($session)): ?><div style="float:left">
      <h2 style="margin-top:100px;color:#FF8938;font-size:16px;font-weight:600">我的成交记录<!-- <a href="<?php echo U('Entrust/manage');?>" class="right my_coin_trade">全部委托</a> --></h2>
      <div style="border-bottom:2px solid #d2d2d2; color:#333;">
        <ul class="my_title" style="background:#f6f6f6">
          <li style=" width:140px; text-align:left; padding-left:10px;">时间</li>
          <li style=" width:150px; text-align:left; padding-left:10px;">数量</li>
          <li style=" width:170px; text-align:left; padding-left:10px;">价格</li>
          <li style=" width:150px; text-align:left; padding-left:10px;">类型</li>
          <li style=" width:120px; text-align:left; padding-left:10px;">交易额</li>
          <!-- <li style=" width:100px; text-align:left; padding-left:15px;">操作</li> -->
        </ul>
        <div class="my_record" style="float:left;max-height:280px">
          <table class="latest_list weituo" style=" width:760px;" align="center" border="0" cellpadding="0" cellspacing="0">
            <tbody id="mycointrustlist">
            <?php if(is_array($chengjiao)): foreach($chengjiao as $key=>$v): ?><tr class="list_con2">
              <td style="width:95px;"><?php echo (date('Y-m-d H:i:s',$v['add_time'])); ?></td>
              <td style="width:85px;" ><?php echo (number_format($v['num']-$v['trade_num'],2)); ?></td>
              <td style="width:75px;"><?php echo (number_format($v["price"],$currency['currency_digit_num'])); ?></td>
              <td class="<?php echo ($v['type']); ?> left_sell " style="width:100px;"><?php echo (fomatOrdersType($v['type'])); ?></td>
              <td style="width:100px;text-align:center"><?php echo (number_format($v['price']*($v['num']-$v['trade_num']),2)); ?></td>
              <!-- <td style="width:100px;text-align:center"><a href="javascript:void(0)"  onclick="cexiao(<?php echo ($v["orders_id"]); ?>)">撤销</a></td> -->
            </tr><?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    
      <?php else: ?>
      <div style="border:1px solid #e2e2e2; margin:15px 0; padding:15px; color:#333; background:#fff; line-height:20px; font-size:14px; text-align:left;">
                请先登录
        </div>
        </div><?php endif; ?>
          <div class="clear"></div>
        </div>
      </div>
    </div>             
    </div>
  </div>

  
  <div class="clear"></div>
</div>
<format id="price_float" data="3"></format>
</div>
<!--footer start--> 
<style> 
	.rightwidth{ width:340px;}
</style>

<div class="coin_footer">
	<div class="coin_footerbar" style="background:#333333; height:240px">
		<div class="coin_footer_nav clearfix;">
			<div class="coin_nav coin_copy left">
				<p><a href="<?php echo U('Index/index');?>"><img style=" height:120px;" src="<?php echo ($config["index_logo_footer"]); ?>"></a></p>
			</div>

			<div class="coin_nav left" style="margin-right:70px">
				<h2 style="color:white;">快速链接</h2>
				<ul>
					<?php if(is_array($team)): $i = 0; $__LIST__ = $team;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Art/details',array('team_id'=>$vo['article_id']));?>" target="_blank" class="left" style="color:white"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
	                <li><a href="<?php echo ($config["qianbao"]); ?>" target="_blank" class="left" style="color:white">钱包下载</a></li>
				</ul>
			</div>

			<div class="coin_nav left" style="margin-right:70px">
				<h2 style="color:white">网站地图</h2>
				<ul>
	            <?php if(is_array($help)): $i = 0; $__LIST__ = $help;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Help/index',array('id'=>$vo['id']));?>" target="_blank" class="left" style="color:white"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<div class="coin_nav coin_nav02 left">
				<h2 class="clearfix" style="height:19px;margin-bottom:11px"><span class="left" style="color:white;">联系我们</span><!-- <a href="http://weibo.com/<?php echo ($config["weibo"]); ?>" target="_blank" class="coin_sina left" style="display:block;width:25px;height:25px"></a> --><!--<a href="#" id="coin_weixin" class="coin_wei left"></a>--></h2>
				<ul>
					
					<li style="color:white;">客 服 QQ：<?php echo ((isset($config["qq1"]) && ($config["qq1"] !== ""))?($config["qq1"]):"暂无"); ?></li>
					<li><a href="mailto:<?php echo ($config['email']); ?>" style="color:white">客服邮箱：<?php echo ((isset($config["email"]) && ($config["email"] !== ""))?($config["email"]):"暂无"); ?></a></li>
					<li><a href="mailto:<?php echo ($config['business_email']); ?>" style="color:white">业务合作：<?php echo ((isset($config["business_email"]) && ($config["business_email"] !== ""))?($config["business_email"]):"暂无"); ?></a></li>
					<li style="color:white;">客服电话：<?php echo ((isset($config['suggest_email']) && ($config['suggest_email'] !== ""))?($config['suggest_email']):"暂无"); ?></li>
					<li style="color:white;">公司地址：<?php echo ((isset($config['address']) && ($config['address'] !== ""))?($config['address']):"暂无"); ?></li>				
				</ul>
			</div>
			<!-- <div class="coin_nav coin_nav02 left rightwidth" style="position:relative;">
              <div style="float:left; padding-top:25px; padding-left:10px;" >
              <img style=" width:100px;" src="<?php echo ($config['weixin']); ?>"/></div>
              <div style=" float:left; padding-left:10px;color:white" > 
				<p class="coin_phoneqq" style="color:white;padding-top:30px;font-size:14px">
				实体积分网官网总群：<?php echo ((isset($config["qqqun1"]) && ($config["qqqun1"] !== ""))?($config["qqqun1"]):"暂无"); ?><br>
				实体积分网官网一群：<?php echo ((isset($config["qqqun2"]) && ($config["qqqun2"] !== ""))?($config["qqqun2"]):"暂无"); ?><br>
				实体积分网官网二群：<?php echo ((isset($config["qqqun3"]) && ($config["qqqun3"] !== ""))?($config["qqqun3"]):"暂无"); ?><br>
				实体积分网官网三群：<?php echo ((isset($config["qqqun4"]) && ($config["qqqun4"] !== ""))?($config["qqqun4"]):"暂无"); ?><br>
               </div>
			</div> -->
		</div>
	</div>

	<div class="footer_aq" style="background:#474747;width:100%;margin:0px;padding-bottom:20px;">
		<p style="color:white"><?php echo ((isset($config["copyright"]) && ($config["copyright"] !== ""))?($config["copyright"]):"暂无"); ?></p>
		<p style="color:white"><?php echo ((isset($config["record"]) && ($config["record"] !== ""))?($config["record"]):"暂无"); ?></p>
		<!-- <ul class="footerSafety clearfix">
	        <li class="safety02"><a href="http://net.china.com.cn/" target="_blank"></a></li>
	        <li class="safety03"><a href="http://webscan.360.cn/index/checkwebsite/?url=<?php echo ($config['localhost']); ?>" target="_blank"></a></li>
	        <li class="safety04"><a href="http://www.cyberpolice.cn/wfjb/" target="_blank"></a></li>
	    </ul> -->
	</div>

	<div id="weixin" style="position:absolute; bottom:88px; left:50%; margin-left:170px; display:block;"><!--<img src="<?php echo ($config["logo"]); ?>">--></div>

	<script>
		$('#coin_weixin').mouseover(function(){
			$('#weixin').show();
		}).mouseout(function(){
			$('#weixin').hide();
		});
	</script>

</div>
</body>
</html>
 
<!--footer end--> 
<input type="hidden" value="<?php echo ((isset($currency["currency_name"]) && ($currency["currency_name"] !== ""))?($currency["currency_name"]):'虚拟币'); ?>"  id = "cname"/>

<script>
function numberfomate(num,decimal){
	var f = ''+num;
	var str = f.split('.');
	if(str.length == 1){
		str[1] = '';
	}
	//console.log(str);
	if(str[1].length >= decimal ){
		var flo = str[1].substr(0,decimal);
	}else{
		var c = decimal - str[1].length;
		//console.log(c);
		if( 1 == c )  var flo = str[1]+'0';
		if( 2 == c )  var flo = str[1]+'00';
		if( 3 == c )  var flo = str[1]+'000';
		if( 4 == c )  var flo = str[1]+'0000';
		//console.log(flo);
	}
	
	var n = str[0]+'.'+flo;
	//return parseFloat(n);
	return n;
}

//console.log(numberfomate(1.0,2));

function sell(){
	var cname=$("#cname").val();
	if($("#coinpriceout").val()==""){
		$("#trustmsgout").text("卖出价格不能为空").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
		return false;
	}
	if($("#numberout").val()==""){
		$("#trustmsgout").text("卖出数量不能为空").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
		return false;
	}
	if($("#pwdtradeout").val()==""){
		$("#trustmsgout").text("交易密码不能为空").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
		return false;
	}
	if(($("#coinpriceout").val())*($("#numberout").val())<1){
		$("#trustmsgout").text("交易额不能低于1元").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
		return false;
	}

	layer.confirm('确定卖出?', {
		  offset: '380px',
		  btn: ['确定','取消'] //按钮
		  
		}, function(index){
			layer.close(index);
			$("body").append("<div id='loading' style='z-index:19891014; background-color:#000; opacity:0.3; filter:alpha(opacity=30);top: 0;left: 0;width: 100%;height: 100%;position: fixed;_position: absolute;text-align:center;'><img src='/public/home/images/loading.gif' style='margin-top:25%;' /></div>");
			$.ajax({
				type:"post",
				url:"<?php echo U('Trade/sell');?>",
				data:{sellprice:$("#coinpriceout").val(),sellnum:$("#numberout").val(),sellpwd:$("#pwdtradeout").val(),'currency_id':$("#currency_id").val()},
				async:true,
				success: function(d){
					$("#loading").remove();
					if(d.status!=1){
					$("#trustmsgout").text(d.info).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);	
					//加一个提示
					layer.msg(d.info,{offset:'380px'});
					}else{
					layer.msg(d.info,{offset:'380px'});
					//刷新页面
					setTimeout(window.location.reload(),2000);
					}
				}
			});
}, function(){
  layer.msg('已取消',{
	  offset: '380px',
  });
});
	
	<?php if(($_SESSION['is_phone']) == "1"): else: ?>$('.layui-layer-dialog').css({position:'absolute',left:'45%',top:'55%'})<?php endif; ?>
	

}
function buy(){
	if($("#coinpricein").val()==""){
		$("#trustmsgin").text("买入价格不能为空").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
		return false;
	}
	if($("#numberin").val()==""){
		$("#trustmsgin").text("买入数量不能为空").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
		return false;
	}
	if($("#pwdtradein").val()==""){
		$("#trustmsgin").text("交易密码不能为空").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
		return false;
	}
	if(($("#coinpricein").val())*($("#numberin").val())<1){
		$("#trustmsgin").text("交易额不能低于1元").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
		return false;
	}
	layer.confirm('确定买入?', {
		offset:'380px',
	  btn: ['确定','取消'] //按钮
	}, function(index){
		layer.close(index);
		$("body").append("<div id='loading' style='z-index:19891014; background-color:#000; opacity:0.3; filter:alpha(opacity=30);top: 0;left: 0;width: 100%;height: 100%;position: fixed;_position: absolute;text-align:center;'><img src='/public/home/images/loading.gif' style='margin-top:25%;' /></div>");
		$.ajax({
			type:"post",
			url:"<?php echo U('Trade/buy');?>",
			data:{buyprice:$("#coinpricein").val(),buynum:$("#numberin").val(),buypwd:$("#pwdtradein").val(),currency_id:$("#currency_id").val()},
			async:true,
			success: function(d){
				$("#loading").remove();
				if(d.status!=1){
				$("#trustmsgin").text(d.info).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);	
				//加一个提示
				layer.msg(d.info,{offset:'380px'});
				}else{
				layer.msg(d.info,{offset:'380px'});
				//刷新页面
				setTimeout(window.location.reload(),2000);
				}
			}
		});
	}, function(){
  layer.msg('已取消',{offset:'380px'});
});
		<?php if(($_SESSION['is_phone']) == "1"): else: ?> $('.layui-layer-dialog').css({position:'absolute',left:'3%',top:'55%'});<?php endif; ?>
}

function badFloat(num, size){
    if(isNaN(num)) return true;
    num += '';
    if(-1 == num.indexOf('.')) return false;
    var f_arr = num.split('.');
    if(f_arr[1].length > size){
        return true;
    }
    return false;
}

//格式化小数
//@f float 传入小数: 123; 1.1234; 1.000001;
//@size int 保留位数
//@add bool 进位: 0舍 1进
function formatfloat(f, size, add){
    f = parseFloat(f);
    if(size == 2) conf = [100,0.01];
    if(size == 3) conf = [1000,0.001];
    if(size == 4) conf = [10000,0.0001];
    if(size == 5) conf = [100000,0.00001];
    if(size == 6) conf = [1000000,0.000001];
    if(size == 7) conf = [10000000,0.0000001];
	if(size == 8) conf = [100000000,0.00000001];
    var ff = Math.floor(f * conf[0]) / conf[0];
    if(add && f > ff) ff += conf[1];
    return isNaN(ff)? 0: ff;
}
function vNum(o, len){
	if($("#coinpricein").val()!="" || $("#coinpricein").val()!="此出价为1个币的价格" && $("#numberin").val()!=""){

    if(badFloat(o.value, len))
		  o.value=formatfloat(o.value,len,0);

      var zuidakemai = $(".maxcoin").text();
       zuidakemai=formatfloat(zuidakemai,len,0);
      if($("#numberin").val() > zuidakemai){
        $("#numberin").val(zuidakemai);
      }
		  var fee2 = <?php echo ($currency["currency_buy_fee"]); ?> / 100;
		  var ci = $("#coinpricein").val()*$("#numberin").val();
		  var ct2 = ci ;//+ ci * fee2;

//修改手续费为数量*费率，原计算方式为单价*数量*费率。cuiwei 2017.12.01
		//$("#feebuy").text(Math.round(ci*fee2*10000)/10000);
		$("#feebuy").text(Math.round($("#numberin").val()*fee2*10000)/10000);
		$("#coinin_sumprice").val(Math.round(ct2*10000)/10000);
	}
}

function vNum2(o, len){
	var zuida = $("#coinsale_max").text();
	//alert(zuida);
	
		//$("#numberout").val()=formatfloat($("#numberout").val(), len, 0);
		zuida=formatfloat(zuida, len, 4);
		//alert(o.value);
	
	if($("#numberout").val() > zuida){
		$("#numberout").val(zuida);
		//alert(234);
	}
	//alert(o.value);
	if($("#coinpriceout").val()!="" || $("#coinpriceout").val()!="此出价为1个币的价格" && $("#numberout").val()!=""){
	if(badFloat(o.value, len))
		o.value=formatfloat(o.value, len, 0);
	var nt = $("#coinpriceout").val()*$("#numberout").val();
	var fee = <?php echo ($currency["currency_sell_fee"]); ?> /100;
	var ct = nt;// - nt * fee;
  // alert(nt);
  // alert(fee);

	
	$("#feesell").text(Math.round(nt*fee*10000)/10000);
	$("#coinout_sumprice").val(Math.round(ct*10000)/10000);
	}
	
}

function total_in(o,len){
	var max = $('#to_over').html();
	//alert(o.value);
	
		max = formatfloat(max, len, 4);
		o.value = formatfloat(o.value, len, 4);
		//alert(o.value);
		if(o.value > max) o.value = max;
		if($("#coinpricein").val()!="" || $("#coinpricein").val()!="此出价为1个币的价格" && $("#numberin").val()!=""){
		if(badFloat(o.value, len))
			o.value=formatfloat(o.value, len, 4);
			var nt = o.value/$("#coinpricein").val();
			var fee = <?php echo ($currency["currency_sell_fee"]); ?> /100;
			var ct = nt;// - nt * fee;
			
			$("#feebuy").text(Math.round(ct*fee*10000)/10000);
			$("#numberin").val(Math.round(ct*10000)/10000);
		}
		
	
}
function total_out(o,len){
	var max = $('#from_over').html();
	//alert(o.value);
	
		max = formatfloat(max, len, 0);
		o.value = formatfloat(o.value, len, 0);
		//alert(o.value);
		if(o.value > max) o.value = max;
		if($("#coinpriceout").val()!="" || $("#coinpriceout").val()!="此出价为1个币的价格" && $("#numberout").val()!=""){
		if(badFloat(o.value, len))
			o.value=formatfloat(o.value, len, 0);
			var nt = o.value/$("#coinpriceout").val();
			var fee = <?php echo ($currency["currency_sell_fee"]); ?> /100;
			var ct = nt;// - nt * fee;
			
			$("#feesell").text(Math.round(o.value*fee*10000)/10000);
			$("#numberout").val(Math.round(ct*10000)/10000);
		}
		
	
}



//5秒自动刷新挂单记录
setInterval(function(){
	$.post('/Home/Nocommon/getOrders',{"type":'sell',"currency_id":$("#currency_id").val()},function(data){
		$("#coinsalelist").empty();
		var length=parseInt(data.length)
		for(var i=0;i<data.length;i++){
			$("#coinsalelist").append(
				"<tr class='list_con2' onclick='getsell(this)' onmouseover='chufa1(this)' onmouseout='likai1(this)'><td class='sell left_sell' style='width:55px;'>卖("+(length-i)+")</td><td class='sell left_sell'  style='width:70px;cursor:pointer;'>¥"+numberfomate(data[i]['price'],<?php echo ($currency["currency_digit_num"]); ?>)+"</td><td class='sell left_sell' ><?php echo ($currency["currency_unit"]); ?>"+(numberfomate(((data[i]['num']-data[i]['trade_num'])),<?php if(($currency["currency_name"]) == "比特币"): ?>3<?php else: ?> 4<?php endif; ?>))+"</td><td class='sell left_sell'  style='width:80px;'>¥"+(numberfomate((data[i]['price']*(data[i]['num']-data[i]['trade_num'])),2))+"</td></tr>"
			);
		}
	});
		$.post('/Home/Nocommon/getOrders',{"type":'buy',"currency_id":$("#currency_id").val()},function(data){
		$("#coinbuylist").empty();
		var length=parseInt(data.length)
		for(var i=0;i<data.length;i++){
			$("#coinbuylist").append(
				"<tr class='list_con2' onclick='buynum(this)' onmouseover='chufa(this)' onmouseout='likai(this)'><td class='buy left_sell'  style='width:55px;'>买("+(parseInt(i)+1)+")</td><td  class='buy left_sell' style='width:70px;cursor:pointer;'>¥"+numberfomate(data[i]['price'],<?php echo ($currency["currency_digit_num"]); ?>)+"</td><td  class='buy left_sell' onclick='buynum(this);'><?php echo ($currency["currency_unit"]); ?>"+(numberfomate(((data[i]['num']-data[i]['trade_num'])),<?php if(($currency["currency_name"]) == "比特币"): ?>3<?php else: ?> 4<?php endif; ?>))+"</td><td  class='buy left_sell' style='width:80px;'>¥"+(numberfomate((data[i]['price']*(data[i]['num']-data[i]['trade_num'])),2))+"</td></tr>"
			);
		}
	})		
},10000);



</script>
<script>



function zuidakemai(){
	if($("#coinpricein").val()!="" && $("#coinpricein").val()!=0){
		var m = <?php echo ((isset($user_currency_money["currency_trade"]["num"]) && ($user_currency_money["currency_trade"]["num"] !== ""))?($user_currency_money["currency_trade"]["num"]):0); ?>;
		var fee=<?php echo ($currency["currency_buy_fee"]); ?>/100;
		m=m*(1-fee);
		var n = parseFloat($("#coinpricein").val());
		
		$(".maxcoin").text(Math.round(m/n*10000)/10000);
	}else{
		$(".maxcoin").text(0);
	}
}
</script>
<script>
	function cexiao(_this){
		layer.confirm('确定撤销委托？', {
	  btn: ['确定','取消'], //按钮
	  title: '撤销委托'
	}, function(){
	  $.post('<?php echo U('Entrust/cancel');?>',{status:-1,order_id:_this},function(data){
				   if(data['status'] == 1){
					   layer.msg(data['info']);
					   setTimeout(window.location.reload(),1000);
				   }else{
					   layer.msg(data['info']);
				   }
			})
	}, function(){
	  layer.msg('已取消');
	});

	}
	
	
	function getsell(_this){
		var reg = /[a-zA-Z|¥|,]/g;
		var moneyStrmai= _this.children[1].innerText;
		var numberSremai= _this.children[2].innerText;
		var moneymai = moneyStrmai.replace(reg,"");
		var numbermai = numberSremai.replace(reg,"");
		coinbuymai = $("#coinbuy_aa").text();
		$("#coinpricein").val(moneymai);
		if(parseFloat(numbermai)>parseFloat(coinbuymai)){
			var num = moneymai*coinbuymai;
			$("#coinin_sumprice").val(num.toFixed(4));
			$("#numberin").val(coinbuymai);
			return false;
		}
		var num = moneymai*numbermai;
		$("#coinin_sumprice").val(num.toFixed(4));
		$("#numberin").val(numbermai);
		
		
	}
	
	function buynum(_this){
		var reg = /[a-zA-Z|¥|,]/g;
		var moneyStr= _this.children[1].innerText;
		var numberSre= _this.children[2].innerText;
		var money = moneyStr.replace(reg,"");
		var number = numberSre.replace(reg,"");
		var coinsale_max = $("#coinsale_max").text()
		$("#coinpriceout").val(money);
		if(parseFloat(number)>parseFloat(coinsale_max)){
			var num = money*coinsale_max;
			$("#coinout_sumprice").val(num.toFixed(4));
			$("#numberout").val(coinsale_max);
			return false;
		}
		var num = money*number;
		$("#coinout_sumprice").val(num.toFixed(4));
		$("#numberout").val(number);
	}

	function chufa(_this){
	 $(_this).css('background-color','#CCC');
	}
	function likai(_this){
	$(_this).css('background-color','#f6f6f6');
	}
	function chufa1(_this){
	 $(_this).css('background-color','#CCC');
	}
	function likai1(_this){
	$(_this).css('background-color','#f6f6f6');
	}

</script>