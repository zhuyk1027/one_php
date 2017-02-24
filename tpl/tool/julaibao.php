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
            background-color:994514;
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
        <p><?php echo $title ?>:</p>
        推 荐 人：<input type="text" name="recommender" placeholder="推荐人"  size="20" value=""><br />
        生成密码：<input type="text" name="pass" placeholder="生成密码"  size="20" value="q12345"><br />
        注册人数：<input type="text" name="num" placeholder="注册人数"  size="20" value="1">
        <p><input type="button" value="sign" onclick="sign()"></p>

    </li>
</ul>
</body>
<script>
    //登录
    function sign(){
        var recommender = $("input[name='recommender']").val();
        var num = $("input[name='num']").val();
        var pass = $("input[name='pass']").val();
        $.ajax({
            url: "/tool/sign/julaibao_sign",
            data: { "recommender": recommender,"num":num,"pass":pass},
            type: "POST",
            dataType : "json",
            success: function (data) {
                alert('处理完毕，账户分别为'+data+'操作结束。');
            }
        });
    }

</script>
</html>
