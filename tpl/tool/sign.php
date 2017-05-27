<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>工具列表</title>
    <style type="text/css">
        ul {
            margin-left:10px;
            margin-right:10px;
            margin-top:10px;
            padding: 0;
        }
        li {
            float: left;
            margin: 0px;
            margin-left:1%;
            padding: 20px;
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
        a:link{
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            text-decoration:none;
            color:#fff;
        }
        a:visited{
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            text-decoration:none;
            color:#fff;
        }
        a:hover{
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            text-decoration:none;
            color:#fff;
        }
        a:active{
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            text-decoration:none;
            color:#fff;
        }


        .aul {
            margin: 10px;;
            padding: 0;
        }
        .ali {
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
</head>
<body>
<div align="center">
    <ul>
        <li style="background-color:#c09853"><a href="/">返回博客首页</a></li>
    </ul>
    <ul class="clear aul">
        <li class="backcolor ali">
            <p>All Sign</p>
            pay密码：<input type="radio" name="pay_pass_sign" value="1" checked>是
            <input type="radio" name="pay_pass_sign" value="2">否
            <input type="radio" name="pay_pass_sign" value="3">全部
            <p><input type="button" value="sign" onclick="jump_sign()"></p>
        </li>
    </ul>
    <ul class="clear aul">
        <li class="backcolor ali">
            <p>All Lottery</p>
            活动 ID：<input type="text" name="act_id" placeholder="活动ID"  size="19" value=""><br />
            pay密码：<input type="radio" name="pay_pass" value="1" checked>是
                    <input type="radio" name="pay_pass" value="2">否
                    <input type="radio" name="pay_pass" value="3">全部
            <p><input type="button" value="sign" onclick="jump()"></p>
        </li>
    </ul>
</div>
<script src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
<script>
    function jump(){
        var act_id = $("input[name='act_id']").val();
        var pay_pass = $("input[name='pay_pass']:checked").val();
        if(act_id==''){
            return false;
        }

        window.location.href='/tool/sign/lottery/'+act_id+'/'+pay_pass;
    }
    function jump_sign(){
        var pay_pass = $("input[name='pay_pass_sign']:checked").val();
        window.location.href='/tool/sign/baiy_all_sign/'+pay_pass;
    }

</script>
</body>
</html>