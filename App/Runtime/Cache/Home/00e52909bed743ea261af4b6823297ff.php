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
<!--header end-->
<style>
.pull-left{ float:left;}
.pull-right{ float:right;}
.link a{ color:#000;}
.link{ margin:0px auto; width:100%;}
.pt_img{ width:158px; height:61px;}
.col-xs-2 {
    width: 16.66666667%;
	float: left;
}
p{
	margin-bottom: 0px !important;
}
h1{
	margin-top: 0px !important;
}
#qh_carousel{
	position: absolute;
	top: 74px;
}
/*#carousel-example-generic{
	width:100% !important;
	
}*/
.carousel-indicators{

}
.carousel-inner{
	height: 100% !important;
}
.carousel-indicators{
	left: 25% !important;
}
.carousel-indicators li{
	width: 14px !important;
	height: 14px !important;
	border-radius: 7px !important;
	margin-right: 6px !important;
	
}
/*.carousel-indicators .active{
	background: white !important;
}*/
#chart_canvasGroup{
	width: 800px !important
}
#qh_carousel img{height:360px !important;}
.active img{height:360px !important;}
/*.qh_sub img{
	margin-top:-6px;
}*/
.qh_sub{
	width: 200px;
}
<?php if(($_SESSION['is_phone']) != "1"): ?>.section.clear_fix.p_t_30{
	min-width: 1200px !important;
}
.market_right{
	width:1050px !important;
}
.sub_flash{
	width: 72% !important;
}
.qh_wxts{
	padding-left: 13% !important;
}<?php endif; ?>




        	/*.market_sub{
        		border: 1px solid #ddd;
        	}
        	.qh_btc_title{
        		width: 100%;
        		height: 55px;
        	}
        	.qh_btc_title h1{
        		height: 55px;
        		color: #ff8839;
        		font-size: 18px;
        		margin-bottom: 0px;
        		line-height: 55px;
        		width: 200px;
        		text-align: center;
        		<!-- border-top:1px solid #ff8839; -->

        	}
        	.qh_btc_table{
        		width: 100%;
        	}
        	.qh_btc_table tr{
        		border-bottom: 1px solid #faf5f2;
        	}
        	.qh_btc_table tr:hover{
        		background: #F9F5F2;
        	}
        	.qh_btc_table thead tr:first-child{
        		background: #e6e6e6;
        		color: #7d7875;
        	}
			.qh_btc_table thead tr:hover{
				cursor:pointer;
			}
        	.qh_btc_table th{
        		text-align: center;
        		padding: 0px;
        		height: 50px;
        		line-height: 50px;
        		position: relative;


        	}
        	.qh_btc_table th:first-child{

        	}
        	.qh_btc_table td{
        		padding: 0px;
        		height: 50px;
        		line-height: 50px;
        		text-align: center;
        	}
        	.qh_btc_table td img{
        		width: 24px;
        		height: 24px;
        	}
        	.qh_btc_table tr td:first-child{
        		color: #7d7875;
        		font-weight: bold;
        	}
        	.cagret-down{
        		position: absolute;
        		top: 26px;
        	}
        	.cagret-up{
        		position: absolute;
        		top: 14px;
        	}
			.qh_qujiaoyi{
				color:#e55600;
				line-height: 28px;
				border:1px solid #e55600;
				border-radius:3px;
				width:80px;
				height30px;
				cursor:ponter;
				margin:5px;
			}
			.qh_qujiaoyi:hover{
				background-color:#e55600;
				color:white;
			}*/


.container {
	  min-width: 1200px;
	  background: url(/Uploads/Public/Uploads/pic/bg1.png) no-repeat top left;
  }

.container .row1_1200 {
	width: 1200px;
	margin: 0 auto;
	height: 660px;
}

.container .row1_1200 .title {
	text-align: center;
	position: relative;
}

.container .row1_1200 .title .line1 {
	font-size: 40px;
	color: #fff;
	font-weight: 600;
	display: inline-block;
	padding: 80px 0 50px 0;
}

.container .row1_1200 .title .line2 {
	position: absolute;
	height: 4px;
	background-color: #f58623;
	width: 70px;
	top: 175px;
	left: 566px;
}

.container .row1_1200 .ctn {
	color: #fff;
	margin-top: 50px;
	padding: 0 16px;
}

.container .row1_1200 .ctn p {
	color: #fff;
	line-height: 30px;
	font-size: 16px;
}

.container .row1_1200 .ctn .row2 {
	margin-top: 50px;
}

.container .row2_1200 {
	width: 1200px;
	margin: 0 auto;
	padding-top: 50px;
}

.container .row2_1200 .row1 {
	color: #000;
	padding: 0 16px;
	line-height: 30px;
	font-size: 16px;
}

.container .row2_1200 .row2 {
	padding-top: 50px;
	overflow: hidden;
}

.container .row2_1200 .row2 div {
	width: 33%;
	float: left;
	text-align: center;
}

.container .row2_1200 .row2 div span {
	display: block;
	font-weight: 700;
	font-size: 16px;
}

.container .row2_1200 .row3 {
	padding: 30px 16px 50px;
	line-height: 30px;
	font-size: 16px;
}

.container .row2_1200 .row5 {
	min-width: 1200px;
}

.container .row1_max {
	min-width: 1200px;
}

.container .row1_max img {
	width: 100%;
	display: block;
}

.container .row3_1200 {
	width: 1200px;
	margin: 0 auto;
}

.container .row3_1200 .title {
	text-align: center;
	color: #000;
	font-weight: 700;
	font-size: 40px;
	padding: 60px 0 50px
}

.container .row3_1200 .ctn {
	overflow: hidden;
	margin-bottom: 40px;
}

.container .row3_1200 .ctn .left {
	float: left;
	text-align: center;
	margin-left: 120px;
}

.container .row3_1200 .ctn .left img {
	width: 370px;
	height: 480px;
}

.container .row3_1200 .ctn .right {
	float: right;
	text-align: center;
	margin-right: 120px;
}

.container .row3_1200 .ctn .right img {
	width: 370px;
	height: 480px;
}

.container .row3_1200 .ctn dt {
	line-height: 60px;
	margin: 0;
	font-size: 16px;
}

.container .row3_1200 .ctn dd {
	line-height: 30px;
	margin: 0;
	font-size: 16px;
}

.container .row2_max {
	background: #f5f5f5;
	overflow: hidden;
}

.container .row2_max .w_1200 {
	width: 1200px;
	background: #f5f5f5;
	margin: 0 auto;
	padding-top: 90px;
}

.container .row2_max .w_1200 div {
	float: left;
}

.container .row2_max .w_1200 .title {
	clear: both;
	text-align: center;
	padding: 30px 0 60px;
}

.container .row2_max .w_1200 .pic1 {
	margin-left: 60px;
}

.container .row2_max .w_1200 .pic2 {
	margin: 0 40px;
}

.container .row2_max .w_1200 .pic1 img {
	width: 500px;
	height: 364px;
}

.container .row2_max .w_1200 .pic2 img, .container .row2_max .w_1200 .pic3 img {
	width: 255px;
	height: 366px;
}

.container .row3_max {
	min-width: 1200px;
	background: url(/Uploads/Public/Uploads/pic/bg4.png) no-repeat left top;
	background-size: 100% 100%
}

.container .row3_max .w_1200 {
	width: 1200px;
	height: 400px;
	margin: 0 auto;
	color: #fff;
}

.container .row3_max .w_1200 .title {
	padding: 60px 0 50px;
	font-weight: 600;
	text-align: center;
	font-size: 30px;
	color: #fff;
}

.container .row3_max .w_1200 .ctn {
	line-height: 30px;
	padding: 0 16px;
	font-size: 16px;
}

</style>
<script type="text/javascript" src="/Public/Home/js/focus.js"></script>
<script type="text/javascript" src="/Public/Home/js/Fnc.js"></script>
<!-- <script type="text/javascript" src="/Public/Home/js/zc.js"></script> -->
<script type="text/javascript" src="/Public/Home/js/1.js"></script>
<script type="text/javascript" src="/Public/Home/js/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="/Public/Home/css/zc.css">
<link rel="stylesheet" type="text/css" href="/Public/Home/css/bootstrap.css">
<!--k线图有关-->
<link rel="stylesheet" type="text/css"
	href="/Public/Home/css/kline.css">
<script src="/Public/Home/js/jquery-1.js"></script>
<script src="/Public/Home/js/kline.js"></script>
<script src="/Public/Home/js/highstock.js"></script>
<script src="/Public/Home/js/coinindex.js"></script>
<script src="/Public/Home/js/sort.js"></script>

<style>
	.active{width: 100%;}
</style>
<div class="blank_400">
	<div class="ab_box">
		<div class="index_banner_box" id="index_banner_box">
			<div id="qh_carousel" style="width: 100%">
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="height:360px !important">
					<!-- Indicators -->
					<ol class="carousel-indicators">
					<?php if(is_array($flash)): $kk = 0; $__LIST__ = $flash;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($kk % 2 );++$kk;?><li data-target="#carousel-example-generic" data-slide-to="<?php echo ($kk -1); ?>"  <?php if(($kk) == "1"): ?>class='active'<?php endif; ?>  ></li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ol>
					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<?php if(is_array($flash)): $k = 0; $__LIST__ = $flash;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="item <?php if(($k) == "1"): ?>active<?php endif; ?> ">
							<a href="<?php echo ($vo["jump_url"]); ?>"><img src="<?php echo ($vo["pic"]); ?>" class="qh_img" alt="<?php echo ($vo["title"]); ?>" style="width: 100%"></a>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
						
					</div>
					<!-- Controls -->
				</div>
			</div>
			<div class="index_t">
				<div class="section floor_top">
					<!-- 轮播的页码  开始 -->
					<ul id="page_list"></ul>
					<div class="index_login_box" style="height: 320px;margin-top: -40px">
						<div class="login_box">
							<div class="opacity_bg"></div>
							<!--登录前 -->
							<?php if(empty($member)): ?><div class="tab tab01">
								<h4 class="title" style="line-height: 40px">登录</h4>
								<p>
									<input name="email_or_phone" id="email"

									class="input_login mail_complete" data-type="*"
									data-msg-null="请输入用户名或手机" value="" placeholder="请输入用户名或手机"
									autocomplete="off" type="text">
								</p>
								<div class="relative">
									<div class="mail_complete_list absolute"></div>
								</div>
								<p>
									<input name="password" class="input_login" id="password" 
									data-type="*" data-msg-null="请输入密码" placeholder="请输入密码"
									type="password">
								</p>
								<div class="relative">
									<div class="mail_complete_list absolute"></div>
								</div>
								<p>
									<input name="captcha" class="input_login" id="captcha"
										   data-type="*" data-msg-null="输入验证码" placeholder="输入验证码"
										   type="text" style="width: 38%">
									<img class="yanzm" id="captchaimg" src="<?php echo U('Login/showVerify');?>"
										 onclick="$('#captchaimg').attr('src', '<?php echo U('Login/showVerify');?>?t='+Math.random())"
										 style="width: 41%;margin-top: -9px">

								</p>
								
								
								<p class="help">
									<a href="<?php echo U('Login/findPwd');?>"
									target="_blank">忘记密码？</a>
								</p>
								<p>
									<button class="btn btn_orange sign_btn loading" type="button"
									onclick="login()">登录</button>
								</p>
								<input name="step" value="" type="hidden">
							</div>
							<?php else: ?>
							<!--登录后 -->
							<div class="tab tab01">
								<h3 class="title">
									<a href="<?php echo U('User/index');?>"
									class="font_20"> <?php echo (session('USER_KEY')); ?> </a>
								</h3>
								<p>
									<a href="<?php echo U('User/index');?>" class="btn 
									btn_orange btn_url">账户中心</a>
								</p>

								<p class="m_t_10">
									<a href="<?php echo U('Entrust/manage');?>" class="btn btn_orange btn_url">委托管理</a>
								</p>


								<p class="button_box">
									<a  href="javascript:void(0)" onclick="confirm_fill();"  class="btn_white">充值</a>
									<a href="<?php echo U('User/draw');?>" class="btn_white">提现</a>
								</p>
							</div><?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--<div class="coin_footer">
	&lt;!&ndash; <div class="baipi" style="width: 1180px;margin: 30px auto 0 auto;padding: 10px 0 30px 20px;">
		
	</div> &ndash;&gt;
	<img src="/Uploads/Public/U ploads/baipi.png" alt="" style="width:100%">
</div>-->
<div class="coin_footer">
	<div class="container">
		<div class="row1_1200">
			<p class="title">
				<span class="line1">白皮书</span>
				<span class="line2"></span>
			</p>
			<div class="ctn">
				<p class="row1">
					背景：如今互联网时代到来，由于比特币的崛起，区块链瞬速火爆，各种不同的虚拟数字货币应运而生，很多人是因为比特币才知道区块链的存在，而类似比特币这种虚拟数字货币却只是区块链中的一个应用产物，很多虚拟数字货币真正起到了多少实际的本身价值呢？然而并没有，只是很多人把虚拟数字货币当做当初的股市、楼市来炒，博傻理论，看最后谁来接盘而已，由于大量的资金涌入虚拟数字货币反而给很多实体项目陷入了因资金链困难或断裂的艰难处境，而得不到良好的发展，社会的进步与发展永远离不开实业努力创造发展所做的贡献，近几年来，以多种所有制形式为特征的中小企业迅猛发展，为拉动国民经济增长发挥着越来越重要的作用。全国政协厉以宁委员指出，中国个体私营经济中99%是中小企业，其中77%面临资金短缺问题。随着中小企业数量的增多，其对资金的需求也越来越大。但由于种种原因，中小企业融资难已日益成为制约其发展的突出问题，我相信很多创业者对此也深有体会，而很多手头上有一部分资金投资的人又不知道该如何投资，找不到项目，对项目也缺乏判断，并且投资进去资金也不是随时需要用时随时可以撤出来，而且小额的资金对于企业也起不到根本性的作用。</p>
				<p class="row2">
					为此也响应中央倡导市场闲散资金更多服务于实体经济精神我们创建此平台，结合区块链技术打造全球首个区块链实体项目积分投资获益新模式交易平台，充分利用散户资金完美对接实体项目，所发行的实体项目积分最终也全部将由实体项目所产生的利润来进行回收销毁。
				</p>
			</div>
		</div>
		<div class="row2_1200">
			<p class="row1">
				制度：项目方有好的项目需要对接平台可将资料发送到公司企业邮箱sz@stjfw.net，平台将派调查监管小组对经平台筛选出来实体项目进行考察，并将公示项目考察过程及结果然后进行评级并附上平台意见及项目不足之处，所有会员进行投票表决，当赞成该项目上线的超过百分之五十，我们将在项目方根据国家法律法规完成所需的监管备案后与项目方签订合同并上线该积分，项目方按合同签订条款每月拿出盈利的固定百分比交由平台回购销毁部门，并公示详细的每月项目规划，项目进度、工作内容、下时间段计划、财务报表等，平台调查监管小组会每月监管查阅项目方财务并公示每月所收到项目方的金额，平台拿项目方合同所签订的盈利百分比金额全部用于回购该项目方所发行的积分并立即销毁，直至项目方所发行的积分全部回购销毁，平台才与项目方合同终止并下架该项目。（为了防止后期所持积分人员知道流通积分已经不多坐地涨价，平台规定当回收达到第百分之八十个积分时，剩余百分之二十的积分将全按第百分之八十个积分的价钱回购，不再加价回购，项目方也只需要缴纳完其余百分之二十的积分价即合同终止），我们平台也将筹集一定资金到风险应急部门，万一该实体项目遇到自然灾害、政策变动、商业模式更新等不可抗力因素导致该项目方最终确定已再无回购销毁所发行的积分能力现象时，我平台为保障投资者利益将启动平台自行回购销毁机制，每月从风险应急部门拿出一定比例资金对该积分启动人道主义回购销毁。 
				<br/>
				发行量：ST积分总发行量1亿，永不增发，团队持有百分之八十（8千万），前期推广百分之十（1千万），前期发售百分之十（1千万）。 
				<br/>
			</p>
			<div class="row2">
				<div>
					<img src="/Uploads/Public/Uploads/pic/pic1.png" alt="">
					<span>团队持有(8千万)</span>
				</div>
				<div>
					<img src="/Uploads/Public/Uploads/pic/pic2.png" alt="">
					<span>前期推广(1千万)</span>
				</div>
				<div>
					<img src="/Uploads/Public/Uploads/pic/pic2.png" alt="">
					<span>前期发售(1千万)</span>
				</div>
			</div>

			<div class="row3">
				<strong>
					平台上线所有实体项目积分交易都用ST积分兑换，并且所有实体项目发行的积分都是在ST积分基础上发行的，所有实体项目都是在已经通过区块链技术手段成功研发出来了积分才上线本平台直接自由交易并可直接提到该项目的积分钱包（本平台不接受任何形式的前期ico众筹）。  
					<br/>
					平台所持积分：在ST积分单个价格在10元人民币以下时平台每月将盈利的百分之八十拿出来对ST积分进行回收，当单个ST积分价格高于十元人民币时平台将所持积分总量百分十的积分缓慢放还于市场，当单个ST积分价格高于二十元人民币时平台将所持积分总量百分二十的币缓慢放还于市场，以此类推，随着平台所持积分的数量的减少，放还的比例高了，但数量并没增加，反而到后期只会越来越少的积分放还市场。  
					<br/>
					<!--为响应国家政策，本平台的ST积分会上线各大交易平台交易，暂时均不在本平台与法定货币进行交易兑换，本平台暂不设法定货币充提接口，与法定货币完全脱钩。
                    <br/>-->
					<br/>

					<!--	计划：<br/>
                        1、2017年10月中旬平台在国内上线运营；<br/>
                        2、2017年11月中旬平台所有功能正式上线运营；<br/>
                        3、2017年12月ST积分上线各大交易平台交易；<br/>
                        4、2018年上半年在美国、日本、韩国、俄罗斯成立运营中心筛选优质实体项目并推广；<br/>
                        5、2018年下半年在全球范围内成立运营中心筛选优质实体项目并推广。<br/>-->
					<!--
                                    平台以后所有实体ICO项目都用ST币ICO，并且所有实体ICO项目发行的币都是在ST币基础上发行的，所有实体项目ICO 的币在ICO结束后都将上线本平台自由交易。
                                    <br><br>
                                    所有经平台筛选出来上线的实体ICO项目平台都将公示考察过程及结果然后进行评级并附上平台意见及项目不足之处，项目方必须有详细的项目规划，每月将项目进度、工作内容、下时间段计划、财务报表等准时公之于众，并在上线前与平台签订合同拿出每月盈利的固定百分比交由平台回购销毁部门，平台会每月监管查阅项目方财务并公示每月所收到项目方的金额，平台拿项目方合同所签订的盈利百分比金额全部用于回购该项目方ICO所发行的币并立即销毁，直至ICO所发行的币全部回购销毁，平台才与项目方合同终止并下架该项目。（为了防止后期持币人员知道流通币已经不多坐地涨价，平台规定当回收达到第百分之八十个币时，剩余百分之二十的币全按第百分之八十个币的价钱回购。不再加价回购，项目方也只需要缴纳完其余百分之二十的币价即合同终止），当然谋事在人成事在天，实体ICO也是存在一定风险的，请大家投资之前仔细分析权衡风险谨慎投资，我们平台也将筹集一定资金到风险应急部门，万一该实体项目出现破产现象再无能力回购销毁所发行出去的币时，我平台将启动人道回购销毁机制，每月从风险应急部门拿出一定比例资金对该币进行人道主义回购销毁。
                                    <br><br>
                                    在ST币单个价格在10元人民币以下时平台每月将利润的百分之八十拿出来对ST币进行回收，当价单个ST币格高于二十元人民币时平台将所持币总量百分十的币缓慢放还于市场，当价单个ST币格高于三十元人民币时平台将所持币总量百分二十的币缓慢放还于市场，以此类推，随着平台所持币的数量的减少，放还的比例高了，但数量并没增加，反而到后期只会越来越少的币放还市场。
                    -->
				</strong>
			</div>
		</div>
		<!--<div class="row1_max">
			<img src="/Uploads/Public/Uploads/pic/pic4.png" alt="">
		</div>-->
		<!-- <div class="row3_max">
			<div class="w_1200">
				<p class="title">我们的初衷</p>
				<p class="ctn">
					我们创办本平台的初衷也是为实体做实在的事，为投资者争取最大的利润保障，所以本平台不花钱请任何对本平台技术、发展起不到任何作用的圈内大咖站台，把所有利润实实在在回报于投资者！
					<br><br><br>
					我们将通过多个资深商业人士对项目进行分析审核作出可行性报告进行筛选，选出优质的实体项目上线平台发行实体项目积分，对于该项目的优质等级以及不足之处我们也会附加我们客观的参考意见，请大家理性投资。
				</p>
			</div>
		</div> -->
	</div>
</div>
<script>
	function login() {
		var email = $('#email').val();
		var password = $('#password').val();
		var captcha = $('#captcha').val();

		if ($('#email').val().length <= 0) {
			layer.msg("请输入用户名");
			return;
		}
		if ($('#password').val().length <= 0) {
			layer.msg("请输入密码");
			return;
		}

		$.post("/Home/Login/checkLog", {
			email : email,
			pwd : password,
			captcha : captcha
		}, function(data) {
			layer.msg(data.info);
			 setTimeout(function(){
				 	 window.location.reload();
				}, 3000);
			<?php if(($_SESSION['is_phone']) == "1"): else: ?> $('.layui-layer-msg').css({position:'absolute',left:'88%',top:'15%'});<?php endif; ?>
			if (data.status == 1) {
				location.reload();
			}
		});

	}
</script>
<div class="hide">
	<input name="coin_type" value="cny_btc" type="hidden"> <input
		name="amount" value="" type="hidden">
</div>

<!-- <div style="padding-left:20%;" class="qh_wxts">温馨提示：<?php echo ($config["wenxin_tishi"]); ?></div> -->
<script type="text/javascript">
			$(function(){


				var a = 1;
			/*setTimeout(function() { }, 20);*/
				$(".qh_btc_table th").click(function(){
					
					$(".qh_btc_table th").css("color","#7d7875")
					$(this).css("color","#FF8839");
					if(a%2 == 0){
						$('.qh_btc_table').find("i.cagret-up").css("border-bottom-color","");
						$(this).find("i.cagret-down").css("border-top-color","red");	
									
					}else{
						
						$(".qh_btc_table").find("i").css("border-top-color","");
						$(this).find("i.cagret-up").css("border-bottom-color","red");						
					}
					a++;
				
				})
				/*$(document).on("click",".qh_btc_table th.descending",function(){
				
					$(".qh_btc_table th").css("color","#7d7875");
					$(this).css("color","#FF8839");
					$(".qh_btc_table th i.cagret-down").css("border-top-color","#7d7875");
					$(".qh_btc_table th i.cagret-up").css("border-bottom-color","#7d7875");
					$(this).find("i.cagret-down").css("border-top-color","#FF8839");
					
					
				})*/
			
			
				/*$(document).on("click",".qh_btc_table th.ascending",function(){
				
					$(".qh_btc_table th").css("color","#7d7875");
					$(this).css("color","#FF8839");
					$(".qh_btc_table th i.cagret-down").css("border-top-color","#7d7875");
					$(".qh_btc_table th i.cagret-up").css("border-bottom-color","#7d7875");
					$(this).find("i.cagret-up").css("border-bottom-color","#FF8839");
				
				})*/
						

		});
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
<script>
	$("#head_nav ul").children("li").eq(0).addClass("cur");
	$(".market_type").children("li").on("click",function(){
		$(this).addClass("cur").siblings().removeClass("cur");
	});
/*$(window).scroll(function(e){
	var h = $(".ab_box").offset().top+$(".ab_box").height();
	var dh = 1;
	if($(window).scrollTop()>h){
		$("#doc_head").addClass("fixed");
		$(".ab_box").css("padding-top","75px");
		//$(".head_masthead").css("height",dh++);
	}else{
		$("#doc_head").removeClass("fixed");
		$(".ab_box").css("padding-top",0);
	}
});
	$(".qh_img").css("height","360px")*/
</script>
<!--<script>
var souyexiaoxi=document.cookie.indexOf("souyexiaoxi=");

if(souyexiaoxi == -1){
	layer.open({
	  type: 1,
	  skin: 'layui-layer-demo', //样式类名
	  closeBtn: 2, //不显示关闭按钮
	  anim: 2,
	  title: false,
	  area: ['500px', '300px'],
	  shadeClose: true, //开启遮罩关闭
	  content: $.trim("<?php echo ($souyexiaoxi); ?>") 
	});
	document.cookie="souyexiaoxi=111";
}

</script>-->