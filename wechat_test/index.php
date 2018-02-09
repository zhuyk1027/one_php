<?php

include_once('weixin.class.php');//引用刚定义的微信消息处理类

define("TOKEN", "zhuyktest");//自己定义的token 就是个通信的私钥
define('DEBUG', false);
$weixin = new Weixin(TOKEN,DEBUG);//实例化

$weixin->getMsg();
$type = $weixin->msgtype;//消息类型

$username = $weixin->msg['FromUserName'];//哪个用户给你发的消息,这个$username是微信加密之后的，但是每个用户都是一一对应的

if ($type==='text') {
    if ($weixin->msg['Content']=='Hello2BizUser') {
        //微信用户第一次关注你的账号的时候，你的公众账号就会受到一条内容为'Hello2BizUser'的消息
        //$reply = $weixin->makeText('欢迎你关注哦，屌丝');
        $results['items'] = search('first');//查询的代码
        if(empty($results['items'][0]['picurl'])){
            $reply = $weixin->makeText($results['items']['title']);
        }else{
            $reply = $weixin->makeNews($results);
        }
    }else{
        //这里就是用户输入了文本信息
        $keyword = $weixin->msg['Content'];   //用户的文本消息内容
        $results['items'] = search($keyword);//查询的代码
        if(empty($results['items'][0]['picurl'])){
            $reply = $weixin->makeText($results['items']['title']);
        }else{
            $reply = $weixin->makeNews($results);
        }
    }
}elseif ($type==='location') {
    //用户发送的是位置信息  稍后的文章中会处理
}elseif ($type==='image') {
    //用户发送的是图片 稍后的文章中会处理
}elseif ($type==='voice') {
    //用户发送的是声音 稍后的文章中会处理
}
$weixin->reply($reply);

function search($keyword){

    $dsn="mysql:host=bdm242626193.my3w.com;dbname=bdm242626193_db";
    $pdo=new PDO($dsn,'bdm242626193','rootroot');
    $pdo->query("set names utf8");

    if($keyword=='first'){
        $sql = "select * from wx_auto_replay where re_id=1 or keyword='first'";
    }else{
        $sql = "select * from wx_auto_replay where keyword like '%".$keyword."%'";
    }
    $rs=$pdo->query($sql);
    $row=$rs->fetch();

    if(!$row){
        $row = $pdo->query("select * from wx_auto_replay where re_id<10 and keyword='others'")->fetchAll();
        $row = $row[rand(0,count($row)-1)];
    }

    $record[]=array(//以下代码，将数据库中查询返回的数组格式化为微信返回消息能接收的数组形式，即title、description、picurl、url 详见微信官方的文档描述
        'title' =>$row['title'],
        'description' =>$row['description'],
        'picurl' => $row['picurl'],
        'url' =>$row['url']
    );

    return $record;
}

?>