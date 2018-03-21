<?php
return array (
		// 加载语言包
		'LOAD_EXT_CONFIG' => 'adminEntrance,db', // 加载扩展配置文件
		'LANG_AUTO_DETECT' => false, // 关闭语言的自动检测，如果你是多语言可以开启		
		'LANG_SWITCH_ON' => TRUE,    // 开启语言包功能，这个必须开启		
		'DEFAULT_LANG' => 'zh-cn',   // zh-cn文件夹名字 /lang/zh-cn/common.php
		'TMPL_PARSE_STRING' => array (
				'__ADMINCSS__' => '/Public/admin/css',
				'__ADMINJS__' => '/Public/admin/js',
				'__ADMINIMG__' => '/Public/admin/images',
				'__HOMECSS__' => '/Public/home/css',
				'__HOMEJS__' => '/Public/home/js',
				'__HOMEIMG__' => '/Public/home/images',
				'__STATICCSS__' => '/Public/static/css',
				'__STATICJS__' => '/Public/static/js',
				'__STATICCIMG__' => '/Public/static/images',
				'__ADMIN__' => '/Public/admin',
				'__HOME__' => '/Public/Home',
				'__STATIC__' => '/Public/static',
				'__PLUGINS__' => '/Public/plugins',
				'__PLUGINSJS__' => '/Public/plugins/js',
				'__PLUGINSCSS__' => '/Public/plugins/css',
				'__PUBLICNEWJS__' => '/Public/new/js',
				'__PUBLICNEWCSS__' => '/Public/new/css' 
		),
		
		'SZ_QQ_SCOPE'=> 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr',
		'SZ_QQ_APP_ID'=> '101293145',
		'SZ_QQ_CALLBACK'=> 'http://www.bsd38.com/Home/Login/qqLogin',
		'SZ_QQ_APP_KEY'=> '50388dac9fd9fea0ac2e842b46fb9782',
		// URl
		'URL_CASE_INSENSITIVE' => false,
		'URL_MODEL' => 2,
		'URL_HTML_SUFFIX' => 'html',
		'URL_ROUTER_ON' => true, // 是否开启URL路由		                                
		'COOKIE_PREFIX' => 'odr',  // Cookie				
		'APP_GROUP_LIST' => 'Home,Admin', // 项目分组设定		                                 
		'TMPL_ACTION_SUCCESS' => './App/Common/jump.html', // 模版
		'TMPL_ACTION_ERROR' => './App/Common/jump.html',		
		'TMPL_L_DELIM' => '{', // 模板引擎普通标签开始标记
		'TMPL_R_DELIM' => '}', // 模板引擎普通标签结束标记
		           		
		// 显示错误信息
 		'SHOW_ERROR_MSG' => true,
        //'SHOW_PAGE_TRACE' =>false,
		//关闭debug模式错误跳入404 
  		// 'TMPL_EXCEPTION_FILE' =>'./404.html' ,		
        //是否开启提现功能
		'DRAW'  =>   true,
		'REQUEST_VARS_FILTER'=>true,
		//memcache session配置
		//定义session为memcache
		//'SESSION_TYPE' => 'Memcache',
		//Memcache服务器
		//'MEMCACHE_HOST' => '139.196.24.35',
		//Memcache端口
		//'MEMCACHE_PORT' => 11211,
		//Memcache的session信息有效时间
		//'SESSION_EXPIRE' => 10

		'ALIYUN_CONFIG' => array (		
    		//'OSS_ACCESS_ID' => 'LTAI0lTLrXyDmKyp',
    		//'OSS_ACCESS_KEY' => 'OnFvoyauTdMzVMDdgwN8dhkinjc5mW',
    		//'OSS_ACCESS_ID' => 'LTAIOrX3JoYcvr5T',
    		//'OSS_ACCESS_KEY' => 'rtxU922Y8T1xgSphp1hmVrpZvWw2gO',
    		'OSS_ACCESS_ID' => 'LTAIwt8HsTRyDstM',
    		'OSS_ACCESS_KEY' => '5KnX4OHHYuVhPWa2SbYUJCl5Bohqcw',    		    		
    		'OSS_ENDPOINT' => 'oss-cn-qingdao.aliyuncs.com',
    		'OSS_BUCKET' => 'stjfw-idcard-photos',	
		),
		//redis配置
		/*'DATA_CACHE_PREFIX' => 'Redis_',//缓存前缀
		'DATA_CACHE_TYPE'=>'Redis',//默认动态缓存为Redis
		'REDIS_RW_SEPARATE' => true, //Redis读写分离 true 开启
		'REDIS_HOST'=>'127.0.0.1', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
		'REDIS_PORT'=>'6379',//端口号
		'REDIS_TIMEOUT'=>'300',//超时时间
		'REDIS_PERSISTENT'=>false,//是否长连接 false=短连接
		'REDIS_AUTH'=>'',//AUTH认证密码*/

);
