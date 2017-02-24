<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title ?></title>
    <style type="text/css">
        ul {
            margin: 10px;;
            padding: 0;
        }
        li {
            float: left;
            margin: 0px;
            margin-left:1%;
            padding: 10px;
            display: inline;
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
        .backcolor {
            background-color:orange;
        }
        .clear {
            clear:both;
        }
    </style>
</head>
<body>
<ul>
    <li class="backcolor"><a href="/tool">返回</a></li>
</ul>
<ul class="clear">
    <form method="post" action="/tool/send_email/send_email" target="froms">
    <li class="backcolor">
        <p>收件人:</p>
        <input type="text" name="to" placeholder="收件人" style="width: 100%;" size="50">
        <p>标题:</p>
        <input type="text" name="title" placeholder="邮件标题">
        <p>内容:</p>
        <textarea name="conts" cols="30" rows="5" placeholder="邮件内容"></textarea>
        <br />
        <input type="submit">
    </li>
    </form>
    <li class="backcolor clear">
        <p>发送状态:</p>
        <iframe name="froms" id="froms" style="border:0px;" height="36"></iframe>
    </li>
</ul>
</body>
</html>