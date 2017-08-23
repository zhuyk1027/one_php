<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:#CD8162;
        }
    </style>
</head>
<body>
<ul>
    <li class="backcolor"><a href="/tool">返回</a></li>
</ul>
<ul class="clear">
    <form method="post" action="/tool/tool/md5_to_encode" target="froms">
    <li class="backcolor">
        <p class="text_center"><?php echo $title;?></p>
        <table>
            <tr>
                <td class="right"width="80">字符：</td>
                <td><input type="text" name="words" placeholder="字符串" ></td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="submit" class="button" value="加密">
                </td>
            </tr><tr >
                <td class="right">密文：</td>
                <td>
                    <iframe name="froms" id="froms" style="border:0px;" width="270" height="36"></iframe>
                </td>
            </tr>
        </table>
    </li>
    </form>
</ul>
</body>
</html>