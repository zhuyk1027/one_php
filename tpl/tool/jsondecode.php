<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:#CD919E;
        }
    </style>
</head>
<body>
<ul>
    <li class="backcolor"><a href="/tool">返回</a></li>
</ul>
<ul class="clear">
    <form method="post" action="/tool/tool/json_to_decode" target="froms">
    <li class="backcolor">
        <p class="text_center"><?php echo $title;?></p>
        <table>
            <tr>
                <td class="right"width="80">字符：</td>
                <td><textarea name="words" cols="33" rows="8"></textarea></td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="submit" class="button" value="加密">
                </td>
            </tr>
        </table>
    </li>

    <li class="backcolor clear">
        <iframe name="froms" id="froms" style="border:0px;width:100%;min-width:340px;height: auto;min-height: 300px;"  ></iframe>
    </li>
    </form>
</ul>

</body>
</html>