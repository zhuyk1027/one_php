<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?></title>
    <style type="text/css">
        body{font-size: 12px;}
        ul {
            margin: 10px;;
            padding: 0;
        }
        li {
            float: left;
            margin: 0px;
            margin-left:1%;
            padding: 10px;
            display: inline;
            color: #fff;
            font-size: x-large;
            word-break:break-all;
            word-wrap : break-word;
            margin-bottom: 5px;
        }
        a {
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            text-decoration:none;
            color:#fff;
        }
        .backcolor {
            background-color:green;
        }
        .clear {
            clear:both;
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
        <p>百洋商城登录:</p>
        <input type="text" name="account" placeholder="账户" style="width: 100%;" size="20" value="17620016576">
        <input type="text" name="pass" placeholder="密码" style="width: 100%;" size="20" value="qqqqqq">
        <p><input type="button" value="sign" onclick="sign()"></p>
        <input type="text" name="token" placeholder="token" style="width: 100%;" size="20">
        <p><input type="button" value="签到" onclick="sign_in()"></p>
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
