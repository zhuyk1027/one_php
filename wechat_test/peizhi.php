<?php
//验证是否来自于微信
function checkWeixin(){
    //微信会发送4个参数到我们的服务器后台 签名 时间戳 随机字符串 随机数

    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    $echostr = $_GET["echostr"];
    $token = "zhuyktest";

    // 1）将token、timestamp、nonce三个参数进行字典序排序
    $tmpArr = array($nonce,$token,$timestamp);
    sort($tmpArr,SORT_STRING);

    // 2）将三个参数字符串拼接成一个字符串进行sha1加密
    $str = implode($tmpArr);
    $sign = sha1($str);

    // 3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
    if ($sign == $signature) {
        echo $echostr;
    }
}
checkWeixin();
?>