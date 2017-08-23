<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?></title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        .backcolor {
            background-color:#BC8F8F;
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
        <input type="text" name="shijianchuo" placeholder="时间戳" style="width: 100%;" size="20"
               onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();"><br />
        <p><input type="button" value="转化" class="button" onclick="time_changes()"></p>
        <span name="time_change"></span>

    </li>
    <li class="backcolor">
        <p>日期转化为时间戳:</p>
        <input type="text" name="dates" placeholder="日期" style="width: 100%;" size="20" ><br />
        <p><input type="button" class="button" value="转化" onclick="time_changes1()"></p>
        <span name="time_change1"></span>
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
                if(!data){
                    alert('请输入正确的时间格式!');
                }
                $("span[name='time_change1']").html(data);
            }
        });
    }

</script>
</html>
