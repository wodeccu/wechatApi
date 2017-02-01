<?php


class WeixinApi {
	public function valid() {
		if ($this->checksignature ()) {
			echo $echostr;
		} else {
			echo 'error';
		}
	}
	private function checksignature() {
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
		
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
}