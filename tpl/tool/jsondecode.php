<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:#CD919E;
            width: 315px;
        }
    </style>
</head>
<body>
<ul>
    <li style="background-color:#CD919E"><a href="/tool">返回</a></li>
</ul>
<ul class="clear">
    <li class="backcolor">
        <p class="text_center"><?php echo $title;?></p>
        <table>
            <tr>
                <td><textarea name="words" cols="40" rows="8"></textarea></td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="button" class="button" value="解析" onclick="tojsondecode()">
                </td>
            </tr>
        </table>
    </li>

    <li class="backcolor clear">
        <span name="froms" ></span>
    </li>
</ul>
</body>
<script src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
<script>
    var flag=0;
    function tojsondecode(){
        if(flag==1){
            return false;
        }flag=1;

        var words = $("textarea[name='words']").val();
        $.ajax({
            url: "/tool/tool/json_to_decode",
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