<?php 
	$type = $_POST['type'];
// 	'zh-cn': {'line':'(分时)','0':'(1分钟)','1':'(5分钟)','2':'(15分钟)','9':'(30分钟)','10':'(1小时)','3':'(日线)','4':'(周线)'
// 			,'7':'(3分钟)', '11':'(2小时)','12':'(4小时)','13':'(6小时)','14':'(12小时)','15':'(3天)'},
			
	switch ($type){
		case 0:
			$type = '1min';
			break;
		case 1:
			$type = '5min';
			break;
		case 2:
			$type = '15min';
			break;
		case 9:
			$type = '30min';
			break;
		case 10:
			$type = '1hour';
			break;
		case 3:
			$type = '1day';
			break;
		case 4:
			$type = '1week';
			break;
		case 7:
			$type = '3min';
			break;
		case 11:
			$type = '2hour';
			break;
		case 12:
			$type = '4hour';
			break;
		case 13:
			$type = '6hour';
			break;
		case 14:
			$type = '12hour';
			break;
		default:
			$type = 0;
			break ;
	}


	echo file_get_contents("https://www.okcoin.cn/api/v1/kline.do?symbol=btc_cny&type=".$type."&size=100");
?>