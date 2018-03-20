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


<style>
    .passContent .tagContent .ybc_con li label{
        float:inherit;
        padding-left: 10px;
    }
    .error{
        color: #999;
    }
	.tishi1{ background-color:#FDF2DB; width:400px;}
	.tishi1 span.tishi_text{ color:#ccc; padding-left:40px; }
</style>

<script>
    $(document).ready(function(){
        start=document.cookie.indexOf("motip=")
        if (start!=-1){
            start=start + 6
            end=start + 1
            motip = unescape(document.cookie.substring(start,end))
            if(motip == 1)
                $('#phone_top_div').show();
        }
    });

    function hidephone(){
        $('#phone_top_div').hide();
        var d=new Date()
        d.setTime(d.getTime() + 7200)
        document.cookie="motip=2;expires="+d.toUTCString()
    }
</script>

<script src="js/index.js"></script>
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
            <div class="raise right clearfix">
            <link rel="stylesheet" href="/Public/Home/css/layer.css">
            <h2>修改密码</h2>
            <div class="support_ybc pass_ybc">
            <ul id="pass_change">
                <li class=""><a onclick="selectTag('tagContent0',this)" id="uppass" href="javascript:void(0)">修改密码</a> </li>
                <li class="selectTag"><a onclick="selectTag('tagContent1',this)" id="uptrade" href="javascript:void(0)">修改交易密码</a></li>
                <div class="clear"></div>
            </ul>

			<script type="text/javascript">
				$(document).ready(function(){
					var getc = "<?php echo ($_GET['type']); ?>";
					if(getc == '2'){
						$('#uptrade').click();
					}else{
						$('#uppass').click();
					}
				})				
		    </script>

            <div id="tagContent" class="passContent">
                <div class="tagContent selectTag" id="tagContent0" style="display: none;">
                    <form method="post" id="signupForm"  jump-url="<?php echo U('Login/index');?>"   action="<?php echo U('User/updatePassword');?>">
                        <ul class="ybc_con"  style="margin-left: 250px;">
                            <li class="tishi1"><span class="tishi_text">您正在为账户&nbsp;<span style="color:red"><?php echo (session('USER_KEY')); ?></span>&nbsp;修改登录密码</span></li>
                            <li><label for="password">登录密码：</label><input name="oldpwd" id="oldpwd" type="password" placeholder="请输入原登陆密码"></li>
                            <li><label for="newword">新密码：&nbsp;&nbsp;&nbsp;</label><input name="pwd" id="pwd" type="password" placeholder="请输入新的密码"></li>
                            <li><label for="repeat">重复密码：</label><input name="repwd" id="repwd" type="password" placeholder="两次密码必须保持一致"></li>
                            <li><label class="buys">&nbsp;</label><input class="tijiao" value="提交"  type="submit"></li>
                        </ul>
                    </form>
                </div>

            <div class="tagContent" id="tagContent1" style="display: block;">
                <script>
                    $("#signupForm").validate({
                        rules: {
                            oldpwd:{required: true,rangelength:[6,20]},
                            pwd:{required: true,rangelength:[6,20]},
                            repwd:{rangelength:[6,20],equalTo: "#pwd"}
                        },
                        messages: {
                            oldpwd:{required: "密码不能为空",rangelength:"密码长度在6-20个字符之间"},
                            pwd:{required: "密码不能为空",rangelength:"密码长度在6-20个字符之间"},
                            repwd:{rangelength:"密码长度在6-20个字符之间",equalTo: "两次输入不一样"}
                        },
                        submitHandler:function(form){
                            ajax_submit_form(form)
                            return false;
                        },
                        invalidHandler: function() {  //不通过回调
                            return false;
                        }
                    });
                </script>

                        <form method="post" id="PwdtradeForm" jump-url="<?php echo U('Login/index');?>"  enctype="multipart/form-data" action="<?php echo U('User/updatePwdtrade');?>">
                            <ul class="ybc_con" style=" margin-left:250px;">
                                <li class="tishi1"><span class="tishi_text">您正在为账户&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red"><?php echo (session('USER_KEY')); ?></span>&nbsp;修改交易密码，并却确认手机号码已绑定</span></li>
                                <li><label for="password">登录密码：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input name="oldpwd_b" type="password" placeholder="请输入登陆密码"></li>
                                <li><label for="newword">新交易密码：&nbsp;&nbsp;&nbsp;</label><input name="pwdtrade" id="pwdtrade" type="password" placeholder="只能包含数字、字母、下划线"></li>
                                <li><label for="repeat">重复交易密码：</label><input name="repwdtrade"  type="password" placeholder="两次输入必须报保持一致"></li>
                                <li>
									<label for="code" style='width:84px;float:left;' >短信验证：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input style='width:160px;float:left;' name="code" id="code" autocomplete="off" type="text"> 
								
								
									<input value="点击发送" style='width:160px;margin-left:5px;float:left;background-color:#f80;cursor:pointer;' type="button" id="msgt" data-key='off' onclick="sandPhone()">
                                </li>                              
								<li></li>
                                <li ><label class="buys">&nbsp;</label><input  style='margin-top:60px;' class="tijiao" value="提交" type="submit"></li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>

    <script>
        $("#PwdtradeForm").validate({
            rules: {
                oldpwd_b:{required: true,rangelength:[6,20]},
                oldpwdtrade_b:{required: true,rangelength:[6,20]},
                pwdtrade:{required: true,rangelength:[6,20]},
                repwdtrade:{rangelength:[6,20],equalTo: "#pwdtrade"}
            },
            messages: {
                oldpwd_b:{required: "登录密码不能为空",rangelength:"登录密码长度在6-20个字符之间"},
                oldpwdtrade_b:{required: "密码不能为空",rangelength:"交易密码长度在6-20个字符之间"},
                pwdtrade:{required: "密码不能为空",
                rangelength:"新交易密码长度在6-20个字符之间"},
                repwdtrade:{rangelength:"新交易密码长度在6-20个字符之间",equalTo: "两次输入不一样"}
           },
            submitHandler:function(form){
                if(!$("#code").val()){
                    layer.msg("请填写验证码");
                    return false;
                }
                ajax_submit_form(form)
                return false;
            },
            invalidHandler: function() {  //不通过回调
                return false;
            }
        });
				  
		function sandPhone(){
            var phone="<?php echo ($member["phone"]); ?>";		
    		var i=120;
            var tid2;
            tid2=setInterval(function(){
                if($("#msgt").attr("data-key")=='off'){
                    $("#msgt").attr("disabled",true);
                    $("#msgt").removeClass("class");
                    $("#msgt").addClass("button again");
                    i--;
                    $("#msgt").val(i+"秒后可重新发送");
                    if(i<=0){
                        $("#msgt").removeAttr("disabled").val("重新发送验证码");
                        $("#msgt").attr("data-key","on");
                    }
                }
        },1000);
        
        $.post("<?php echo U('Common/ajaxSandPhone');?>",{'phone':phone},
            function(d){
                layer.msg(d.info);
                    if(d.status==1){
                        i=120;
                        $("#msgt").attr("data-key","off");
            	}
            });
    }
</script>

                <script type="text/javascript" src="/Public/Home/js/tab2.js"></script>
                <script src="/Public/Home/js/layer.js"></script>
                <script src="/Public/Home/js/password.js"></script>
                <script type="text/javascript" src="/Public/Home/js/ajaxfileupload.js"></script>
            </div>
        <div class="clear"></div>
    </div>
</div>
                <script type="text/javascript" src="/Public/js/uploadify/jquery.uploadify.min.js"></script>
                <script type="text/javascript" src="/Public/Home/js/tab2.js"></script>
                <script src="/Public/Home/js/layer.js"></script>
                <script src="/Public/Home/js/password.js"></script>
                <script type="text/javascript" src="/Public/Home/js/ajaxfileupload.js"></script>
		    </div>
		<div class="clear"></div>
	</div>
</div>

<script>
    $(".menu8").addClass("uc-current");
</script>

<style> 
.rightwidth{ width:340px;}
/*.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6{
	line-height: 0;
}*/
</style>
<!--footer start-->




<div class="coin_footer">
	<!-- <div class="coin_hint" style="border:0">
		<h2><?php echo ((isset($info_one4["title"]) && ($info_one4["title"] !== ""))?($info_one4["title"]):"风险提示"); ?></h2>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($config["risk_warning"]); ?></p>
	</div> -->
	
	<div class="coin_footerbar" style="background:#333333; height:240px">
		<div class="coin_footer_nav clearfix">
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
<!--footer end-->




</body></html>