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
    <li class="backcolor"><a href="/tool">返回</a></li>
</ul>
<ul class="clear">
    <li class="backcolor">
        <a href="/tool/test/ci_shiwu" target="_blank">CI事务测试</a>
    </li>
</ul>
</body>
<script>
    //登录
    function sign(){
        var account = $("input[name='account']").val();
        var password = $("input[name='pass']").val();
        $.ajax({
            url: "/tool/sign/baiy_login",
            data: { "account": account,"password":password},
            type: "POST",
            dataType : "json",
            success: function (data) {
                $("input[name='token']").val(data);
            }
        });
    }
    //签到
    function sign_in(){
        var token = $("input[name='token']").val();
        $.ajax({
            url: "/tool/sign/baiy_sign",
            data: { "token": token},
            type: "POST",
            dataType : "json",
            success: function (data) {
                alert(data[0]+'，获得'+data[1]+'积分.');
            }
        });
    }
</script>
</html>
