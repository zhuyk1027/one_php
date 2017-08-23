<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:#8B7D6B;
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
        <p class="text_center">邮件发送</p>
        <table style="width: 420px;">
            <tr>
                <td class="right">收件人：</td>
                <td><input type="text" name="to" placeholder="收件人" ></td>
            </tr><tr >
                <td class="right">标题：</td>
                <td>
                    <input type="text" name="title" placeholder="标题">
                </td>
            </tr><tr >
                <td class="right">内容：</td>
                <td>
                    <textarea name="conts" cols="40" rows="6"
                              placeholder="请输入您要发送的内容（ps：本邮件由本站公共邮箱发出，不承诺任何法律效益，不承担任何责任）"></textarea>
                </td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="submit" class="button">
                </td>
            </tr><tr >
                <td class="right">状态：</td>
                <td>
                    <iframe name="froms" id="froms" style="border:0px;" height="36"></iframe>
                </td>
            </tr>
        </table>
    </li>
    </form>
</ul>
</body>
</html>