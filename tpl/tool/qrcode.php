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
            background-color:coral;
        }
        .clear {
            clear:both;
        }
    </style>
    <script src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
    <script src="<?=PUB_PATH?>js/jquery_qrcode_master/jquery.qrcode.min.js"></script>
</head>
<body>
<ul>
    <li class="backcolor"><a href="JavaScript:void(history.go(-1))">返回</a></li>
</ul>
<ul class="clear">
    <li class="backcolor">
        <p>链接:</p>
        <input type="text" name="url" placeholder="请输入你要生成的二维码链接" style="width: 100%;" size="50">
        <p><input type="button" class="create_qrcode" value="Create"></p>
    </li>
</ul>
<ul class="clear">
    <li class="backcolor">
        <p>二维码:</p>
        <div id="qrcode" style="line-height: 128px;"></div>
    </li>
</ul>
</body>
<script>
    //js生成二维码
    $(".create_qrcode").click(function(){
        var url = $("input[name='url']").val();
        if(url==''){
            alert('请输入要生成的网址!');return false;
        }
        $('#qrcode').html('');
        var url = $("input[name='url']").val();
        //jQuery('#qrcode').qrcode("http://blog.wpjam.com");    //不控制大小
        jQuery('#qrcode').qrcode({width: 128,height: 128,text: url});   //控制大小
    })
</script>
</html>
