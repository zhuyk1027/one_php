<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?=$title?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style>
        .backcolor {
            background-color:green;
            width: 315px;
        }

    </style>
</head>
<body>
<div align="center">
    <ul>
        <li style="background-color:green"><a href="/tool">返回</a></li>
    </ul>
    <ul class="clear">
        <li class="backcolor">
            <p>Mobile message send</p>
            <table>
                <tr>
                    <td class="right">Mobile：</td>
                    <td><input name="mobile_code" value=""></td>
                </tr><tr>
                    <td class="right">Number：</td>
                    <td><input name="mobile_message_number" value="1"></td>
                </tr><tr>
                    <td colspan="2" class="text_center"><input type="button" class="button" value="sign" onclick="jump_send_mobile_message()"></td>
                </tr>
            </table>
        </li>
    </ul>

</div>
<script src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
<script>
    function jump_send_mobile_message(){
        var mobile_code = $("input[name='mobile_code']").val();
        var Number = $("input[name='mobile_message_number']").val();
        window.location.href='/tool/send_message/send_mobile_code/'+mobile_code+'/'+Number;
    }

</script>
</body>
</html>