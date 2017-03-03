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
    <li class="backcolor">
        <p>日期转化为时间戳:</p>
        <input type="text" name="dates" placeholder="时间戳" style="width: 100%;" size="20" ><br />
        <span name="time_change1"></span>
        <p><input type="button" value="转化" onclick="time_changes1()"></p>

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
    function time_changes1(){
        var dates = $("input[name='dates']").val();
        $.ajax({
            url: "/tool/tool/to_time_change1",
            data: { "dates": dates},
            type: "POST",
            dataType : "json",
            success: function (data) {
                $("span[name='time_change1']").html(data);
            }
        });
    }

</script>
</html>
