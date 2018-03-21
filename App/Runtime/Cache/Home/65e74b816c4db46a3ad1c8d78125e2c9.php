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

<!--top end-->
<link rel="shortcut icon" href="http://www.stjfw.net/favicon.ico" type="image/x-icon">
<script src="_PUBLIC__/Home/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="/Public/Home/css/jb_font-awesome.css">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
.s-policy{ margin-top:30px; padding-right: 20px;}
.security-col a span{ color:#999; padding-right:5px;}
dd{
    font-size: 14px;
}
dt{
    font-size: 14px;
}

</style>
</head>

<body>
<div id="main">
    <div class="main_box">
        <div id="my_menu" class="sdmenu left">
		<div>
			<p><i class="iconfont iconcolor">&#xe61e;</i>&nbsp;我的资产</p>
						<a href="<?php echo U('User/index');?>" class="menu"><b class="ic-uc"></b><!--<i class="iconfont">&#xe6f6;</i>&nbsp;&nbsp;-->账户资产</a>
                        <a href="<?php echo U('Finance/index');?>" class="menu13"><b class="ic-uc"></b><!--<i class="iconfont">&#xe93e;</i>&nbsp;&nbsp;-->财务日志</a>
					</div>
                    <hr />
		<div style="position:relative;">
			<p><i class="iconfont iconcolor">&#xe643;</i>&nbsp;我的交易</p>
						<a href="<?php echo U('Entrust/manage');?>" class="menu2"><b class="ic-uc"></b><!--<i class="iconfont">&#xe616;</i>&nbsp;&nbsp;-->委托管理</a>
						<a href="<?php echo U('Trade/myDeal');?>" class="menu3"><b class="ic-uc"></b><!--<i class="iconfont">&#xe65b;</i>&nbsp;&nbsp;-->我的成交</a>
						<!-- <a href="<?php echo U('Entrust/history');?>" class="menu4"><b class="ic-uc"></b><!--<i class="iconfont">&#xe62d;</i>&nbsp;&nbsp;委托历史</a> -->
						
						<!--<a href="<?php echo U('User/zhongchou');?>" class="menu6"><b class="ic-uc"></b>我的众筹</a>-->
					</div>
                    <hr />
                    <div>
			<p><i class="iconfont iconcolor">&#xe60e;</i>&nbsp;安全中心</p>
            		
						<!--<a href="<?php echo U('Safe/index');?>" class="menu11"><b class="ic-uc"></b><i class="iconfont">&#xe649;</i>&nbsp;&nbsp;安全中心</a>-->
                        <a href="<?php echo U('User/updatePassword');?>" class="menu8"><b class="ic-uc"></b><!--<i class="iconfont">&#xe638;</i>&nbsp;&nbsp;-->修改密码</a>
                        <a href="<?php echo U('User/updateMassage');?>" class="menu7"><b class="ic-uc"></b><!--<i class="iconfont">&#xe649;</i>&nbsp;&nbsp;-->个人信息</a>
						<!--<a href="<?php echo U('Safe/mobilebind');?>" class="menu12"><b class="ic-uc"></b><i class="iconfont">&#xe609;</i>&nbsp;&nbsp;手机绑定</a>-->
					  </div>
                      <hr />
				  <div>
			<p><i class="iconfont iconcolor">&#xe611;</i>&nbsp;账户中心</p>
			            <!-- <?php if(($config["huanxun"]) == "1"): ?><a href="<?php echo U('FillByBank/index');?>" class="menu12"><b class="ic-uc"></b><!--<i class="iconfont">&#xe620;</i>&nbsp;&nbsp;人民币充值（在线）</a><?php endif; ?> -->
			            <a href="<?php echo U('User/pay');?>" class="menu12"><b class="ic-uc"></b><!--<i class="iconfont">&#xe620;</i>&nbsp;&nbsp;-->人民币充值</a>
			            <!-- <a href="<?php echo U('Fill/index');?>" class="menu15"><b class="ic-uc"></b><i class="iconfont">&#xe620;</i>&nbsp;&nbsp;人民币充值记录</a> -->
			            <a href="<?php echo U('User/draw');?>" class="menu14"><b class="ic-uc"></b><!--<i class="iconfont">&#xe6f0;</i>&nbsp;&nbsp;-->人民币提现</a>
            		    <a href="<?php echo U('Safe/index');?>" class="menu11"><b class="ic-uc"></b><!--<i class="iconfont">&#xe660;</i>&nbsp;&nbsp;-->用户中心</a>
						<a href="<?php echo U('User/invit');?>" class="menu9"><b class="ic-uc"></b><!--<i class="iconfont">&#xe602;</i>&nbsp;&nbsp;-->邀请好友</a>
						<a href="<?php echo U('User/sysMassage');?>" class="menu10"><b class="ic-uc"></b><!--<i class="iconfont">&#xe664;</i>&nbsp;&nbsp;-->系统消息<span class="messagenum" id='messagenum2'><?php echo ($count); ?></span></a>
					  </div>
					  <?php if(($config["list_switch"]) == "1"): ?><div>
						<p><i class="iconfont iconcolor">&#xe611;</i>&nbsp;精彩活动</p>
			            <a href="<?php echo U('Index/chart');?>" class="menu13"><b class="ic-uc"></b><!--<i class="iconfont">&#xe620;</i>&nbsp;&nbsp;-->推荐排行榜</a>
			            
					  </div><?php endif; ?>
                      
		</div>
		<script>
			$("#head_nav ul").children("li").eq(3).addClass("cur");
		</script>
        <div class="assets_content w753 right bg_w" id="safebox" style=" border-left-style:none !important;"><h1>用户中心</h1>
            <div class="safe_center clear">
              <div style="float:left; margin-left:30px;">
                <div class="sc_level">
                    <div class="sc_level_4">
                      <img style=" width:102px; height:102px;" <?php if($u_info['head']): ?>src="<?php echo ($u_info['head']); ?>"<?php else: ?>src="/Public/Home/images/ulogodefault.jpg"<?php endif; ?> >
                    </div>
                    <!--<div class="sc_level_info"></div>-->
                </div>
                <dl>
                    <dt>ID：<span><?php echo ($u_info['member_id']); ?></span></dt>
                    <dd>姓名：<?php echo ($u_info['name']); ?></dd>
                    <dd>用户名：<?php echo ($u_info['email']); ?></dd>
                    <dd>会员等级：   
                        <?php if(($total > 50) ): ?>钻石会员
                            <?php elseif( ($total < 10) and ($total > 4) ): ?>铜牌会员
                            <?php elseif( ($total < 20) and ($total > 9) ): ?>银牌会员
                            <?php elseif( ($total < 50) and ($total > 19) ): ?>金牌会员
                            <?php else: ?> 普通会员<?php endif; ?>
                    </dd>
					<dd>注册时间：<?php echo (date("Y-m-d H:i:s",$u_info['reg_time'])); ?></dd>
                </dl>
              </div>
              <div style="float:left; margin-left:170px;">
                <dl style=" padding-top:0px !important;"><span style="font-size:16px;">人民币信息</span>
                    <dt>可用：￥<?php echo (floatval($u_info['rmb'])); ?></span></dt>
                    <dd>冻结：￥<?php echo (floatval($u_info['forzen_rmb'])); ?></dd>
                    <dd>总资产：￥<?php echo (floatval($u_info['rmb']+$u_info['forzen_rmb'])); ?></dd>
                </dl>
              </div>
            </div>
            <ul class="sc_statu">
                 <li> 
                     <?php if($u_info['status'] < 2){ ?> 
                     <em class="sc_statu_type_1_1"></em> 
                     <dl> 
                         <dt>实名认证</dt> 
                         <dd class="nopass">未认证<a href="<?php echo U('ModifyMember/userAuthentication');?>">点击认证</a></dd> 
                     </dl> 
                     <?php }else if($u_info['status']==4){ ?> 
                     <em class="sc_statu_type_1_1"></em> 
                     <dl> 
                         <dt>实名认证</dt> 
                         <dd class="nopass">未通过审核，请重新上传资料<a href="<?php echo U('ModifyMember/userAuthentication');?>">重新认证</a></dd> 
                     </dl> 
                     <?php }else if($u_info['status']==5){ ?> 
                     <em class="sc_statu_type_1_2"></em> 
                     <dl> 
                         <dt>实名认证</dt> 
                         <dd class="alpass">已认证</dd> 
                     </dl> 
                     <?php }else if($u_info['status']==3){ ?> 
                     <em class="sc_statu_type_1_1"></em> 
                     <dl> 
                         <dt>实名认证</dt> 
                         <dd class="nopass">审核中</dd> 
                     </dl> 
                     <?php }else{ ?> 
                     <dl> 
                         <dt>实名认证</dt> 
                         <dd class="nopass">账号有误,请联系客服</dd> 
                     </dl> 
                     <?php } ?> 
                 </li> 
                <li>
                    <?php if($u_info['phone']): ?><em class="sc_statu_type_3"></em>
                        <dl>
                            <dt>绑定手机</dt>
                            <dd class="alpass">已认证 <a href="<?php echo U('User/updateMassage');?>">查看</a></dd>
                        </dl>
                        <?php else: ?>
                        <em class="sc_statu_type_3_1"></em>
                        <dl>
                            <dt>绑定手机</dt>
                            <dd class="nopass">未认证 <a href="<?php echo U('ModifyMember/modify');?>">点击绑定</a></dd>
                        </dl><?php endif; ?>

                </li>
                <!--<li>
                    <em class="sc_statu_type_2"></em>
                    <dl>
                        <dt>账户资产</dt>
                        <dd class="alpass">已认证<a href="<?php echo U('User/index');?>">点击进入</a></dd>
                    </dl>
                </li>
-->
            </ul>
            
            <!--copy_hb新修改的-->
            <div class="s-policy s-setting">
              <dl>
                 <dt>您已设置 <b>2</b> 个保护项，还有 <b>0</b>个保护项可设置</dt>                    
                    <!-- <dd> -->
                        <!-- <i class="icon-pilicy-mobile float_left"></i> -->
                        <!-- <div class="security-col"> -->
                            <!-- <div class="validate"><b>实名认证</b></div> -->
                            <!-- <div class="pass">受国家要求及为了您的资金安全需进行实名认证</div> -->
                            <!-- <?php if($u_info['status']==0){ ?> -->
		                        <!-- <a href="<?php echo U('ModifyMember/modify');?>"><span>未认证</span>认证</a> -->
		                    <!-- <?php }else if($u_info['status']==1){ ?> -->
		                        <!-- <a href="<?php echo U('User/updateMassage');?>"><span>已认证</span>查看</a> -->
		                    <!-- <?php }else if($u_info['status']==3){ ?> -->
		                    	<!-- <a href="<?php echo U('ModifyMember/userAuthentication');?>"><span>未认证</span>认证</a> -->
		                    <!-- <?php }else if($u_info['status']==4){ ?> -->
                                <!-- <a href="<?php echo U('User/updateMassage');?>"><span>审核中</span>查看</a> -->
                            <!-- <?php }else{ ?> -->
                                 <!-- <span>账号有误，联系客服</span> -->
                            <!-- <?php } ?> -->
                        <!-- </div> -->
                    <!-- </dd>                    -->
                    <dd>
                        <i class="icon-pilicy-login float_left"></i>
                        <div class="security-col">
                            <div class="validate"><b>登录密码</b></div>
                            <div class="pass">登录<?php echo ($config['name']); ?>账户时需要输入的密码</div>
                            <a target="_blank" href="<?php echo U('User/updatePassword',array('type'=>1));?>">修改登录密码</a>
                        </div>
                    </dd>
                    <dd class="no">
                        <i class="icon-pilicy-money float_left"></i>
                        <div class="security-col">
                            <div class="validate"><b>交易密码</b></div>
                            <div class="pass">在<?php echo ($config['name']); ?>进行交易时需要输入的密码</div>
                            <a target="_blank" href="<?php echo U('User/updatePassword',array('type'=>2));?>">修改交易密码</a>
                        </div>
                    </dd>
              </dl>
    </div>
            <!--copy_hb新修改的结束-->
            
            <!--原来的-->
            
                
                <!--<div class="sc_info_list" id="sc_info_list">
                <dl style="background-color: rgb(249, 249, 249);">
                    <dt>登录密码</dt>
                    <dd><p>登录<?php echo ($config['name']); ?>账户时需要输入的密码</p></dd>
                    <dd><div class="changepw"><a href="<?php echo U('User/updatePassword');?>">修改登录密码</a></div></dd>
                </dl>
                <dl style="background-color: rgb(255, 255, 255);">
                    <dt>交易密码</dt>
                    <dd><p>在<?php echo ($config['name']); ?>进行交易时需要输入的密码</p></dd>
                    <dd><div class="changepw"><a href="<?php echo U('User/updatePassword');?>">修改交易密码</a></div></dd>
                </dl>
                <dl style="background-color: rgb(249, 249, 249);">
                    <dt>实名认证</dt>
                    <dd><p>受国家要求及为了您的资金安全需进行实名认证</p></dd>
                    <dd>
                        <?php if($u_info['status'] == 0 ): ?><div class="changepw">
                                未认证 <a href="<?php echo U('ModifyMember/modify');?>">认证</a>
                            </div>
                            <?php else: ?>
                            <div class="changepw">
                                已认证 <a href="<?php echo U('User/updateMassage');?>">查看</a>
                            </div><?php endif; ?>
                    </dd>
                </dl>
                </div>-->
                
                <!--原来的结束-->
                
            
        </div>

        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<script>
$("#head_nav ul").children("li").eq(3).addClass("cur");
    $(".menu11").addClass("uc-current");

    function showTips(id,msg){
        var tips = layer.tips(msg, id, {
            tips: [1, '#fff8db'],
            area: ['400px', '35px']
        });
        $(id).on('mouseout', function(){
            layer.close(tips);
        });
    }
</script>
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