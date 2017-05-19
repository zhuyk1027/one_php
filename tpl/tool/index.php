<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>工具列表</title>
	<style type="text/css">
		ul {
			margin-left:10px;
			margin-right:10px;
			margin-top:10px;
			padding: 0;
		}
		li {
			/*width: 10%;*/
			float: left;
			margin: 0px;
			margin-left:1%;
			padding: 20px;
			/*height: 100px;*/
			display: inline;
			/*line-height: 100px;*/
			color: #fff;
			font-size: x-large;
			word-break:break-all;
			word-wrap : break-word;
			margin-bottom: 5px;
		}
		a {
			-webkit-tap-highlight-color: rgba(0,0,0,0);
			text-decoration:none;
			color:#fff;
		}
		a:link{
			-webkit-tap-highlight-color: rgba(0,0,0,0);
			text-decoration:none;
			color:#fff;
		}
		a:visited{
			-webkit-tap-highlight-color: rgba(0,0,0,0);
			text-decoration:none;
			color:#fff;
		}
		a:hover{
			-webkit-tap-highlight-color: rgba(0,0,0,0);
			text-decoration:none;
			color:#fff;
		}
		a:active{
			-webkit-tap-highlight-color: rgba(0,0,0,0);
			text-decoration:none;
			color:#fff;
		}
	</style>
</head>
<body>
<div align="center">
	<ul>
		<li style="background-color:#c09853"><a href="/">返回博客首页</a></li>
		<li style="background-color:coral"><a href="/tool/qrcode">生成二维码</a></li>
		<li style="background-color:orange"><a href="/tool/send_email">发送邮件</a></li>
		<li style="background-color:orange"><a href="/tool/tool/time_change">时间戳转化为日期</a></li>
		<li style="background-color:#545164"><a href="/tool/test">功能测试</a></li>

		<?php
			if($user==1){
				echo '<li style="background-color:green"><a href="/tool/sign/index">白羊</a></li>';
			}
		?>

		<li style="background-color:#994514"><a href="/tool/sign/julaibao">聚来宝</a></li>

	</ul>
</div>
</body>
</html>