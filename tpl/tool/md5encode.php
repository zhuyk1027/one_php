<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:#CD8162;
            width: 315px;
        }
    </style>
</head>
<body>
<ul>
    <li style="background-color:#CD8162"><a href="/tool">返回</a></li>
</ul>
<ul class="clear">
    <li class="backcolor">
        <p class="text_center"><?php echo $title;?></p>
        <table>
            <tr>
                <td class="right" style="width: 80px;">字符：</td>
                <td><input type="text" name="words" placeholder="字符串" ></td>
            </tr>
            <tr>
                <td colspan="2" class="text_center">
                    <input type="button" class="button" value="加密" onclick="tomd5()">
                </td>
            </tr>
            <tr >
                <td class="right">密文：</td>
                <td>
                    <span name="froms"></span>
                </td>
            </tr>
        </table>
    </li>
</ul>
</body>
<script src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
<script>
    var flag=0;
    function tomd5(){
        if(flag==1){
            return false;
        }flag=1;

        var words = $("input[name='words']").val();
        $.ajax({
            url: "/tool/tool/md5_to_encode",
            data: { "words": words},
            type: "POST",
            dataType : "text",
            success: function (data) {
                $("span[name='froms']").html(data);
                $("input[name='words']").val('');
                flag=0;
            }
        });
    }
</script>
</html>