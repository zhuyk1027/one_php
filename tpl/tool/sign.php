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
            <input type="radio" name="pay_pass_sign" value="3">全部<br />
            page : <select id="sign_page">
                <?php for($i=1;$i<100;$i++){
                    echo ' <option value="'.$i.'">'.$i.'</option>';
                } ?>
            </select><br />
            pagesize : <input name="sign_page_size" value="100">
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
            <br />
            page : <select id="lottery_sign_page">
                <?php for($i=1;$i<100;$i++){
                    echo ' <option value="'.$i.'">'.$i.'</option>';
                } ?>
            </select><br />
            pagesize : <input name="lottery_page_size" value="100">
            <p><input type="button" value="sign" onclick="jump()"></p>
        </li>
    </ul>

    <ul class="clear aul">
        <li class="backcolor ali">
            <p>Register</p>
            是否随机邀请码：<input type="radio" name="is_auto" value="1" checked>是
                            <input type="radio" name="is_auto" value="2">否<br />
            指定邀请码：<input type="text" name="invite_code" value="1" ><br />
            注册数量 : <select id="register_num">
                <?php for($i=1;$i<100;$i++){
                    echo ' <option value="'.$i.'">'.$i.'</option>';
                } ?>
            </select><br />
            <p>
                <input type="button" value="sign" onclick="jump_register()">
                <input type="button" value="清除TOKEN" onclick="clear_session()">
            </p>
        </li>
    </ul>
    <ul class="clear aul">
        <li class="backcolor ali">
            <p>Register</p>
            手机：<input type="text" name="phone1"  ><br />
            验证码：<input type="text" name="code1" ><br />
            邀请码：<input type="text" name="invite_code1"><br />
            <p><input type="button" value="sign" onclick="register()"></p>
        </li>
    </ul>
</div>
<script src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
<script>
    function jump(){
        var page = $('#lottery_sign_page option:selected') .val();
        var pagesize = $("input[name='lottery_page_size']").val();
        var act_id = $("input[name='act_id']").val();
        var pay_pass = $("input[name='pay_pass']:checked").val();
        if(act_id==''){
            return false;
        }
        window.location.href='/tool/sign/lottery/'+act_id+'/'+pay_pass+'/'+page+'/'+pagesize;
    }
    function jump_sign(){
        var page = $('#sign_page option:selected') .val();
        var pagesize = $("input[name='sign_page_size']").val();
        var pay_pass = $("input[name='pay_pass_sign']:checked").val();
        window.location.href='/tool/sign/baiy_all_sign/'+pay_pass+'/'+page+'/'+pagesize;
    }
    function jump_register(){
        var register_num = $('#register_num option:selected') .val();
        var is_auto = $("input[name='is_auto']:checked").val();
        var invite_code = $("input[name='invite_code']").val();
        window.location.href='/tool/sign/register_do/'+is_auto+'/'+invite_code+'/'+register_num;
    }
    function register(){
        var phone = $("input[name='phone1']").val();
        var code = $("input[name='code1']").val();
        var invite_code = $("input[name='invite_code1']").val();
        window.location.href='/tool/sign/register/'+phone+'/'+invite_code+'/'+code;
    }function clear_session(){
        $.ajax({
            url: "/tool/sign/unset_session",
            data: { "is_clean": 1},
            type: "POST",
            dataType : "json",
            success: function (data) {
                alert('清除成功');
            }
        });

    }

</script>
</body>
</html>