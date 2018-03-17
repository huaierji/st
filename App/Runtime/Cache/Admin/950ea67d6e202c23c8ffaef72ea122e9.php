<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" >
	<meta name="renderer" content="webkit">
    <title>网站后台管理</title>
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="/Public/Admin/iconfont/demo.css">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/iconfont/iconfont.css"/>
    <script type="text/javascript" src="/Public/Admin/js/libs/modernizr.min.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/js/layer/layer.js"></script>
    <script type="text/javascript" src="/Public/js/laydate/laydate.js"></script>
    
	<link type="text/css" href="/Public/Admin/css/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
    <link type="text/css" href="/Public/Admin/css/jquery-ui-timepicker-addon.css" rel="stylesheet" />
    <script type="text/javascript" src="/Public/Admin/js/jquery-ui-1.8.17.custom.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery-ui-timepicker-zh-CN.js"></script>

    <script>
        (function ($) {
            // 汉化 Datepicker
                $.datepicker.regional['zh-CN'] =
                {
                    clearText: '清除', clearStatus: '清除已选日期',
                    closeText: '关闭', closeStatus: '不改变当前选择',
                    prevText: '<上月', prevStatus: '显示上月',
                    nextText: '下月>', nextStatus: '显示下月',
                    currentText: '今天', currentStatus: '显示本月',
                    monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月'],
                    monthNamesShort: ['一', '二', '三', '四', '五', '六',
                    '七', '八', '九', '十', '十一', '十二'],
                    monthStatus: '选择月份', yearStatus: '选择年份',
                    weekHeader: '周', weekStatus: '年内周次',
                    dayNames: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
                    dayNamesShort: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
                    dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
                    dayStatus: '设置 DD 为一周起始', dateStatus: '选择 m月 d日, DD',
                    dateFormat: 'yy-mm-dd', firstDay: 1,
                    initStatus: '请选择日期', isRTL: false
                };
                $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
             
                //汉化 Timepicker
              $.timepicker.regional['zh-CN'] = {
                timeOnlyTitle: '选择时间',
                timeText: '时间',
                hourText: '小时',
                minuteText: '分钟',
                secondText: '秒钟',
                millisecText: '微秒',
                timezoneText: '时区',
                currentText: '现在时间',
                closeText: '关闭',
                timeFormat: 'hh:mm',
                amNames: ['AM', 'A'],
                pmNames: ['PM', 'P'],
                ampm: false
              };
            $.timepicker.setDefaults($.timepicker.regional['zh-CN']);
        })(jQuery);
    </script>

    <style>
        .iconfont{ padding-right:5px;}
        .fsize{ font-size:15px;}
    </style>
</head>

<body>
<div class="topbar-wrap white">
    <div class="topbar-inner clearfix">
        <div class="topbar-logo-wrap clearfix">
            <h1 class="topbar-logo none"><a href="index.html" class="navbar-brand">后台管理</a></h1>
            <ul class="navbar-list clearfix">
                <li><a class="on" href="index.html">首页</a></li>
                <li><a href="<?php echo U('Home/Index/index');?>" target="_blank">网站首页</a></li>
                <li><a href="<?php echo U('Index/infoStatistics');?>" target="_blank">全站统计信息</a></li>
            </ul>
        </div>
        <div class="top-info-wrap">
            <ul class="top-info-list clearfix">
                <li><a href="<?php echo U('Manage/index');?>">管理员</a></li>
                <li><a href="<?php echo U('Manage/pwdUpdate');?>">修改密码</a></li>
                <li><a href="<?php echo U('Login/loginout');?>">退出</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="container clearfix">
    <div class="sidebar-wrap">
        <div class="sidebar-title">
            <h1>菜单</h1>
        </div>
            <div class="sidebar-content">
                <ul class="sidebar-list">
                    <?php if(!empty($sys_nav)): ?><li>
                        <a href="#"><i class="iconfont">&#xe614;</i><span class="fsize">系统管理</span></a>
                        <ul class="sub-menu">                            
                            	<?php if(is_array($sys_nav)): $i = 0; $__LIST__ = $sys_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li>
                                <!--<li><a href="system.html"><i class="icon-font">&#xe037;</i>清理缓存</a></li>
                                <li><a href="system.html"><i class="icon-font">&#xe046;</i>数据备份</a></li>
                                <li><a href="system.html"><i class="icon-font">&#xe045;</i>数据还原</a></li>--><?php endforeach; endif; else: echo "" ;endif; ?>                            
                        </ul>
                      </li><?php endif; ?>			   
			   				
                    <?php if(!empty($common_nav)): ?><li>
                        <a href="#"><i class="iconfont">&#xe635;</i><span class="fsize">常用操作</span></a>
                        <ul class="sub-menu">
                            	<?php if(is_array($common_nav)): $i = 0; $__LIST__ = $common_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                      </li><?php endif; ?>
                
                    <?php if(!empty($user_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe64d;</i><span class="fsize">会员管理</span></a>
                            <ul class="sub-menu">                            
                                	<?php if(is_array($user_nav)): $i = 0; $__LIST__ = $user_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li><?php endif; ?>
				
                    <?php if(!empty($bonus_nav)): ?><li>
                        <a href="#"><i class="icon-font">&#xe018;</i><span class="fsize">分红管理</span></a>
                        <ul class="sub-menu">                       
                            	<?php if(is_array($bonus_nav)): $i = 0; $__LIST__ = $bonus_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="icon-font">&#xe017;</i><?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                      </li><?php endif; ?>
				               
                    <?php if(!empty($finance_nav)): ?><li>
                        <a href="#"><i class="iconfont">&#xe6c8;</i><span class="fsize">财务管理</span></a>
                          <ul class="sub-menu">                        
                            	<?php if(is_array($finance_nav)): $i = 0; $__LIST__ = $finance_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                          </ul>
                      </li><?php endif; ?>

                    <?php if(!empty($trade_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe631;</i><span class="fsize">交易管理</span></a>
                            <ul class="sub-menu">
                                <?php if(is_array($trade_nav)): $i = 0; $__LIST__ = $trade_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                <!--<li><a href="<?php echo U('Trade/trade');?>"><i class="icon-font">&#xe017;</i>交易记录</a></li>-->
                                <!--<li><a href="<?php echo U('Trade/orders');?>"><i class="icon-font">&#xe017;</i>委托记录</a></li>-->
                            </ul>
                        </li><?php endif; ?>
                
                    <?php if(!empty($wallet_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe631;</i><span class="fsize">钱包币种管理</span></a>
                            <ul class="sub-menu">
                                <?php if(is_array($wallet_nav)): $i = 0; $__LIST__ = $wallet_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                <!--<li><a href="<?php echo U('Trade/trade');?>"><i class="icon-font">&#xe017;</i>交易记录</a></li>-->
                                <!--<li><a href="<?php echo U('Trade/orders');?>"><i class="icon-font">&#xe017;</i>委托记录</a></li>-->
                            </ul>
                        </li><?php endif; ?>
                
                    <?php if(!empty($article_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe6f7;</i><span class="fsize">文章管理</span></a>
                            <ul class="sub-menu">
                                <?php if(is_array($article_nav)): $i = 0; $__LIST__ = $article_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                <!--<li><a href="<?php echo U('Trade/trade');?>"><i class="icon-font">&#xe017;</i>交易记录</a></li>-->
                                <!--<li><a href="<?php echo U('Trade/orders');?>"><i class="icon-font">&#xe017;</i>委托记录</a></li>-->
                            </ul>
                        </li><?php endif; ?>
                
                    <?php if(!empty($admin_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe64d;</i><span class="fsize">管理员管理</span></a>
                            <ul class="sub-menu">
                                <?php if(is_array($admin_nav)): $i = 0; $__LIST__ = $admin_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                <!--<li><a href="<?php echo U('Trade/trade');?>"><i class="icon-font">&#xe017;</i>交易记录</a></li>-->
                                <!--<li><a href="<?php echo U('Trade/orders');?>"><i class="icon-font">&#xe017;</i>委托记录</a></li>-->
                            </ul>
                        </li><?php endif; ?>

                    <?php if(!empty($tongji_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe64d;</i><span class="fsize">统计</span></a>
                            <ul class="sub-menu">
                                <?php if(is_array($tongji_nav)): $i = 0; $__LIST__ = $tongji_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                                <!--<li><a href="<?php echo U('Trade/trade');?>"><i class="icon-font">&#xe017;</i>交易记录</a></li>-->
                                <!--<li><a href="<?php echo U('Trade/orders');?>"><i class="icon-font">&#xe017;</i>委托记录</a></li>-->
                            </ul>
                        </li><?php endif; ?>

    				<?php if(!empty($zhongchou_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe650;</i><span class="fsize">众筹管理</span></a>
                             <ul class="sub-menu">
                             
                                	<?php if(is_array($zhongchou_nav)): $i = 0; $__LIST__ = $zhongchou_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li><?php endif; ?>

    			    <?php if(!empty($bank_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe635;</i><span class="fsize">银行管理</span></a>
                            <ul class="sub-menu">
                                	<?php if(is_array($bank_nav)): $i = 0; $__LIST__ = $bank_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li><?php endif; ?>

                    <!--白皮书管理  -->
                    <?php if(!empty($baipishu_nav)): ?><li>
                            <a href="#"><i class="iconfont">&#xe637;</i><span class="fsize">白皮书管理</span></a>
                            <ul class="sub-menu">
                                    <?php if(is_array($baipishu_nav)): $i = 0; $__LIST__ = $baipishu_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["nav_url"]); ?>"><i class="iconfont"><?php echo ($vo["nav_e"]); ?></i>&nbsp;<?php echo ($vo["nav_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li><?php endif; ?>    

                </ul>
            </div>
        </div>
<script>
$(".sidebar-list li").children("a").on("click",function(){
	$(this).next(".sub-menu").toggle();
});
</script>




    <div class="main-wrap">
        <div class="result-wrap">
            <div class="result-title">
                <div class="result-list">
                    <a href="<?php echo U('Vote/addvote');?>">新增积分</a>
                </div>                
            </div>
            <div class="result-content">
                <table class="result-tab" width="100%">
                    <tr>
                        <th>积分图片</th>
                        <th>积分名称</th>          
                        <th>投票开始时间</th>
                        <th>投票结束时间</th>
                        <th>抢购开始时间</th>
                        <th>抢购结束时间</th>
                        <th>抢购总量</th>
                        <th>对外抢购总量</th>
                        <th>发行价格</th>
                        <th>支持人数</th>
                        <th>反对人数</th>                       
                        <th>操作</th>
                    </tr>
            	<?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$info): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($info["logo"]); ?></td>
                        <td><?php echo ($info["vote_name"]); ?></td>                           
                        <td><?php echo (date('Y-m-d H:i:s',$info["vote_start_time"])); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$info["vote_end_time"])); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$info["buy_start_time"])); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$info["buy_end_time"])); ?></td>
                        <td><?php echo ($info["total"]); ?></td>
                        <td><?php echo ($info["surplus"]); ?></td>
                        <td><?php echo ($info["price"]); ?></td>                       
                        <td><?php echo ($info["support"]); ?></td>
                        <td><?php echo ($info["nonsupport"]); ?></td>
                        
                        <td>
                            <a class="link-update" href="<?php echo U('Vote/updatevote',array('id'=>$info['id']));?>">修改</a>
                            <a class="link-del" href="<?php echo U('Vote/deletevote',array('id'=>$info['id']));?>" onclick="return confirm('确认删除吗？')">删除</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
                <div class="list-page"> <ul><?php echo ($page); ?></ul></div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
$(".sub-menu").eq(7).show();
$(".sub-menu").eq(7).children("li").eq(0).addClass("on");
</script>