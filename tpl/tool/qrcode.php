<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:coral;
        }
    </style>
    <script src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
    <script src="<?=PUB_PATH?>js/jquery_qrcode_master/jquery.qrcode.min.js"></script>
</head>
<body>
<ul>
    <li class="backcolor"><a href="JavaScript:void(history.go(-1))">返回</a></li>
</ul>
<form >
<ul class="clear">
    <li class="backcolor">
        <p class="text_center">生成二维码</p>
        <table>
            <tr>
                <td class="right">链接：</td>
                <td><input type="text" name="url" placeholder="请输入网址..." size="25"></td>
            </tr><tr>
                <td colspan="2" class="text_center">
                    <input type="button" class="create_qrcode button" value="生成二维码">
                    <input type="reset" value="重置" class="button">
                </td>
            </tr><tr style="line-height: 128px;">
                <td class="right">二维码：</td>
                <td>
                    <div id="qrcode" style="line-height: 128px;"></div>
                </td>
            </tr>
        </table>
    </li>
</ul>
</form>
</body>
<script>
    //js生成二维码
    $(".create_qrcode").click(function(){
        var url = $("input[name='url']").val();
        if(url==''){
            alert('请输入要生成的网址!');return false;
        }
        if( url.indexOf('http://')<0 ){
            url = 'http://'+url;
        }
        $('#qrcode').html('');
        //jQuery('#qrcode').qrcode("http://blog.wpjam.com");    //不控制大小
        jQuery('#qrcode').qrcode({width: 128,height: 128,text: url});   //控制大小
    })
</script>
</html>
