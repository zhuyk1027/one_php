<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?=$title?></title>
	<style type="text/css">
        .d_tags{padding: 5%}
        .d_tags a:nth-child(9n){background-color: #4A4A4A;}
        .d_tags a:nth-child(9n+1){background-color: #428BCA;}
        .d_tags a:nth-child(9n+2){background-color: #5CB85C;}
        .d_tags a:nth-child(9n+3){background-color: #D9534F;}
        .d_tags a:nth-child(9n+4){background-color: #567E95;}
        .d_tags a:nth-child(9n+5){background-color: #B433FF;}
        .d_tags a:nth-child(9n+6){background-color: #00ABA9;}
        .d_tags a:nth-child(9n+7){background-color: #B37333;}
        .d_tags a:nth-child(9n+8){background-color: #FF6600;}

        .d_tags a{
            opacity: 0.80;
            filter:alpha(opacity=80);
            color: #fff;
            background-color: #428BCA;
            display: inline-block;
            margin: 5px 5px;
            padding: 3px 20px;
            line-height: 48px}
        .d_tags a:hover{
            opacity: 1;
            filter:alpha(opacity=100);
        }

	</style>
</head>
<body>
    <div class="d_tags">
        <a href="/">返回博客</a>
        <a href="/tool/tool/qrcode">生成二维码</a>
        <a href="/tool/tool/email">发送邮件</a>
        <a href="/tool/tool/md5_encode">md5加密</a>
        <a href="/tool/tool/time_change">时间戳转化为日期</a>
        <a href="/tool/tool/json_decode">json解析</a>
        <a href="/tool/tool/test">功能测试</a>
        <a href="/tool/tool/base64_change">Base64转化</a>

        <?php   if($user==1){   ?>
            <a href="/tool/tool/send_message_page">短信发送</a>
            <a href="/tool/sign/index">白羊</a>
            <a href="/tool/sign/julaibao">聚来宝</a>
        <?php   }   ?>

    </div>
</body>
</html>