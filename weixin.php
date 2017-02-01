<?php
	define('TOKEN', 'weixin');
	$signature = $_GET['signature'];
	$timestamp = $_GET['timestamp'];
	$nonce = $_GET['nonce'];
	$echostr = $_GET['echostr'];
	
	$tmpArr = array(TOKEN,$timestamp,$nonce);
	sort($tmpArr,SORT_STRING);
	
	$tmpStr = implode($tmpArr);
	$tmpStr = sha1($tmpStr);
	
	if($tmpStr == $signature){
		echo $echostr;
	}else{
		echo 'error'; 
	}