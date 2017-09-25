<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:#545164;
        }
    </style>
    <script src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
</head>
<body>
<ul>
    <li class="backcolor"><a href="JavaScript:void(history.go(-1))">返回</a></li>
</ul>
<ul class="clear">
    <li class="backcolor">
        <p class="text_center"><?php echo $title ?></p>
        <table>
            <tr>
                <td class="right">推荐人：</td>
                <td><input type="text" name="recommender" placeholder="推荐人"  size="20" value=""></td>
            </tr><tr >
                <td class="right">生成密码：</td>
                <td>
                    <input type="text" name="pass" placeholder="生成密码"  size="20" value="q12345">
                </td>
            </tr><tr >
                <td class="right">注册人数：</td>
                <td>
                    <input type="text" name="num" placeholder="注册人数"  size="20" value="1">
                </td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="button" value="sign" onclick="sign()" class="button">
                </td>
            </tr>
        </table>
    </li>
</ul>
</body>
<script>
    //登录
    function sign(){
        var recommender = $("input[name='recommender']").val();
        var num = $("input[name='num']").val();
        var pass = $("input[name='pass']").val();
        $.ajax({
            url: "/tool/sign/julaibao_sign",
            data: { "recommender": recommender,"num":num,"pass":pass},
            type: "POST",
            dataType : "json",
            success: function (data) {
                alert('处理完毕，账户分别为'+data+'操作结束。');
            }
        });
    }

</script>
</html>
