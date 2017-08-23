<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>工具列表</title>
    <link href="<?=TOOL_CSS?>main.css" rel="stylesheet" type="text/css">
    <style>
        .backcolor {
            background-color:green;
        }

    </style>
</head>
<body>
<div align="center">
    <ul>
        <li style="background-color:green"><a href="/tool">返回</a></li>
    </ul>
    <ul class="clear">
        <li class="backcolor">
            <p>All Sign</p>
            <table>
                <tr>
                    <td class="right">pay密码：</td>
                    <td><input type="radio" name="pay_pass_sign" value="1" checked>是
                        <input type="radio" name="pay_pass_sign" value="2">否
                        <input type="radio" name="pay_pass_sign" value="3">全部</td>
                </tr><tr>
                    <td class="right">page：</td>
                    <td><select id="sign_page">
                            <?php for($i=1;$i<100;$i++){
                                echo ' <option value="'.$i.'">'.$i.'</option>';
                            } ?>
                        </select></td>
                </tr><tr>
                    <td class="right">pagesize：</td>
                    <td><input name="sign_page_size" value="100"></td>
                </tr><tr>
                    <td colspan="2" class="text_center"><input type="button" class="button" value="sign" onclick="jump_sign()"></td>
                </tr>
            </table>
        </li>
    </ul>
    <ul class="clear">
        <li class="backcolor">
            <p>All Lottery</p>
            <table>
                <tr>
                    <td class="right">活动ID：</td>
                    <td><input type="text" name="act_id" placeholder="活动ID"  size="19" value=""></td>
                </tr><tr>
                    <td class="right">pay密码：</td>
                    <td><input type="radio" name="pay_pass" value="1" checked>是
                        <input type="radio" name="pay_pass" value="2">否
                        <input type="radio" name="pay_pass" value="3">全部</td>
                </tr><tr>
                    <td class="right">page：</td>
                    <td><select id="lottery_sign_page">
                            <?php for($i=1;$i<100;$i++){
                                echo ' <option value="'.$i.'">'.$i.'</option>';
                            } ?>
                        </select></td>
                </tr><tr>
                    <td class="right">pagesize：</td>
                    <td><input name="lottery_page_size" value="100"></td>
                </tr><tr>
                    <td colspan="2" class="text_center"><input type="button" class="button" value="sign" onclick="jump()"></td>
                </tr>
            </table>
        </li>
    </ul>
    <ul class="clear">
        <li class="backcolor">
            <p>Register</p>
            <table>
                <tr>
                    <td class="right">随机邀请码：</td>
                    <td><input type="radio" name="is_auto" value="1" checked>是
                        <input type="radio" name="is_auto" value="2">否</td>
                </tr><tr>
                    <td class="right">指定邀请码：</td>
                    <td><input type="text" name="invite_code" value="1" ></td>
                </tr><tr>
                    <td class="right">注册数量：</td>
                    <td><select id="register_num">
                            <?php for($i=1;$i<100;$i++){
                                echo ' <option value="'.$i.'">'.$i.'</option>';
                            } ?>
                        </select></td>
                </tr><tr>
                    <td colspan="2" class="text_center">
                        <input type="button" class="button" value="sign" onclick="jump_register()">
                        <input type="button" class="button" value="清除TOKEN" onclick="clear_session()">
                    </td>
                </tr>
            </table>
        </li>
    </ul>
    <ul class="clear">
        <li class="backcolor">
            <p>查看返利金额</p>

            <table>
                <tr>
                    <td class="right">时间：</td>
                    <td><input type="radio" name="datetime" value="1" checked>本月
                        <input type="radio" name="datetime" value="2">上月</td>
                </tr><tr>
                    <td colspan="2" class="text_center">
                        <input type="button" class="button" value="查看" onclick="show_backmoney()">
                    </td>
                </tr>
            </table>
        </li>
    </ul>
    <ul class="clear">
        <li class="backcolor">
            <p>Register</p>

            <table>
                <tr>
                    <td class="right">手机：</td>
                    <td><input type="text" name="phone1"  ></td>
                </tr><tr>
                    <td class="right">验证码：</td>
                    <td><input type="text" name="code1" ></td>
                </tr><tr>
                    <td class="right">邀请码：</td>
                    <td><input type="text" name="invite_code1"></td>
                </tr><tr>
                    <td colspan="2" class="text_center">
                        <input type="button" class="button" value="sign" onclick="register()">
                    </td>
                </tr>
            </table>
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
    }
    function clear_session(){
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
    function show_backmoney(){
        var datetime = $("input[name='datetime']:checked").val();
        window.location.href='/tool/sign/show_backmoney/'+datetime;
    }
</script>
</body>
</html>