<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:#8B7D6B;
            width: 315px;
        }
    </style>
</head>
<body>
<ul>
    <li style="background-color:#8B7D6B"><a href="/tool">返回</a></li>
</ul>
<ul class="clear">
    <li class="backcolor">
        <p class="text_center">邮件发送</p>
        <table>
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
                    <textarea name="conts" cols="30" rows="8"
                              placeholder="请输入您要发送的内容（ps：本邮件由本站公共邮箱发出，不承诺任何法律效益，不承担任何责任）"></textarea>
                </td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="button" onclick="send_email()" class="button" value="发送">
                </td>
            </tr><tr >
                <td class="right">状态：</td>
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
    function send_email(){
        if(flag==1){
            return false;
        }flag=1;

        var to = $("input[name='to']").val();
        var title = $("input[name='title']").val();
        var conts = $("textarea[name='conts']").val();
        $.ajax({
            url: "/tool/send_email/send_email",
            data: { "to": to,"title": title,"conts": conts},
            type: "POST",
            dataType : "text",
            success: function (data) {
                $("span[name='froms']").html(data);
                $("input[name='to']").val('');
                $("input[name='title']").val('');
                $("textarea[name='conts']").val('');
                flag=0;
            }
        });
    }
</script>
</html>