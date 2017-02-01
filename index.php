<?php
require('weixin.php');
define ( 'TOKEN', 'weixin' );
$echostr = $_GET ['echostr'];

$wx = new WeixinApi();
$wx->valid();
?>