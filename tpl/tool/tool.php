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
        <p>时间戳转化为日期:</p>
        <input type="text" name="shijianchuo" placeholder="时间戳" style="width: 100%;" size="20" ><br />
        <span name="time_change"></span>
        <p><input type="button" value="转化" onclick="time_changes()"></p>

    </li>
</ul>
</body>
<script>
    //登录
    function time_changes(){
        var shijianchuo = $("input[name='shijianchuo']").val();
        $.ajax({
            url: "/tool/tool/to_time_change",
            data: { "shijianchuo": shijianchuo},
            type: "POST",
            dataType : "json",
            success: function (data) {
                $("span[name='time_change']").html(data);
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
