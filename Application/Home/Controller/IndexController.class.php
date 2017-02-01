<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	define ( 'TOKEN', 'weixin' );
		$echostr = $_GET ['echostr'];
    	$signature = $_GET ['signature'];
		$timestamp = $_GET ['timestamp'];
		$nonce = $_GET ['nonce'];
		
		$tmpArr = array (
				TOKEN,
				$timestamp,
				$nonce 
		);
		sort ( $tmpArr, SORT_STRING );
		
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		
		if ($tmpStr == $signature && $echostr) {
			echo  $echostr;
			exit;
		}else{
			$this->reponseMsg();
		}
	}
	public function reponseMsg(){
		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
		$postObj = simplexml_load_string($postArr);
		if($postObj->MsgType == 'event'){
			if(strtolower($postObj->Event) == 'subscribe'){
				$toUser = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time = time();
				$msgtype = 'text';
				$content = '1';
				$template = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
				$info = sprintf($template,$toUser,$fromUser,$time,$msgtype,$content);
				echo $info;
			}
		}
	}
}