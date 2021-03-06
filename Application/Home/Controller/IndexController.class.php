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
		$postObj = simplexml_load_string($postArr,'SimpleXMLElement',LIBXML_NOCDATA);
		switch ($postObj->MsgType) {
			case 'event':
					if(strtolower($postObj->Event) == 'subscribe'){
						$toUser = $postObj->FromUserName;
						$fromUser = $postObj->ToUserName;
						$time = time();
						$msgType = 'text';
						$content = '欢迎关注诗歌班里的小男孩';
						$template = "<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[%s]]></MsgType>
									<Content><![CDATA[%s]]></Content>
									</xml>";
						$info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
						echo $info;
					}
				break;
			case 'text':
					switch ($postObj->Content) {
						case '金响是谁':
							$content ='金响是你大爷';
							$result = $this->http_post_data($url, $data);
							$result = json_decode($result[1]);
							$template = "<xml>
										<ToUserName><![CDATA[%s]]></ToUserName>
										<FromUserName><![CDATA[%s]]></FromUserName>
										<CreateTime>%s</CreateTime>
										<MsgType><![CDATA[%s]]></MsgType>
										<Content><![CDATA[%s]]></Content>
										</xml>";
							$toUser = $postObj->FromUserName;
							$fromUser = $postObj->ToUserName;
							$time =time();
							$msgType = 'text';
							
							echo $info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
							break;
						case '1':
							 $toUser = $postObj->FromUserName;
							 $fromUser = $postObj->ToUserName;
							 $time =time();
							 $msgType = 'news';
							 $arr = array(
							 		array(
							 			'title'=>'ccc',
							 			'description'=>'ccc',
							 			'picUrl'=>'http://182.254.137.49/weixin/timg.jpg',
							 			'url'=>'http://www.baidu.com',
							 			),
							 		array(
							 			'title'=>'taobao',
							 			'description'=>'ccc',
							 			'picUrl'=>'http://182.254.137.49/weixin/timg.jpg',
							 			'url'=>'http://www.baidu.com',
							 			),
							 	);
							$template ='<xml>
										<ToUserName><![CDATA[%s]]></ToUserName>
										<FromUserName><![CDATA[%s]]></FromUserName>
										<CreateTime>%s</CreateTime>
										<MsgType><![CDATA[%s]]></MsgType>
										<ArticleCount>'.count($arr).'</ArticleCount>
										<Articles>';
							foreach ($arr as $k => $v) {
								$template.=	'<item>
										<Title><![CDATA['.$v["title"].']]></Title> 
										<Description><![CDATA['.$v["description"].']]></Description>
										<PicUrl><![CDATA['.$v["picUrl"].']]></PicUrl>
										<Url><![CDATA['.$v["url"].']]></Url>
										</item>';
							}
							
										
							$template.=	'</Articles>
										</xml> ';
							 echo $info = sprintf($template,$toUser,$fromUser,$time,$msgType);
							break;
						default:
							$url  = "http://www.tuling123.com/openapi/api";
							$data = json_encode(array('key'=>'ce469e196b9d46f0bda1391afe181aab', 'info'=>$postObj->Content,'userid'=>$postObj->FromUserName));
							
							$result = $this->http_post_data($url, $data);
							$result = json_decode($result[1]);
							$content = $result->text;
							$template = "<xml>
										<ToUserName><![CDATA[%s]]></ToUserName>
										<FromUserName><![CDATA[%s]]></FromUserName>
										<CreateTime>%s</CreateTime>
										<MsgType><![CDATA[%s]]></MsgType>
										<Content><![CDATA[%s]]></Content>
										</xml>";
							$toUser = $postObj->FromUserName;
							$fromUser = $postObj->ToUserName;
							$time =time();
							$msgType = 'text';
							
							echo $info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
							break;
					}
					
					
				break;
			default:
				# code...
				break;
		}
		if($postObj->MsgType == 'event'){
			
		}
	}
	public function http_post_data($url, $data_string) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8',
			'Content-Length: ' . strlen($data_string))
			);
	ob_start();
	curl_exec($ch);
	echo $return_content = ob_get_contents();
	ob_end_clean();

	$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	return array($return_code, $return_content);
}
}