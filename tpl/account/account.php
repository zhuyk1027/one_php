<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="<?=PUB_PATH?>js/jquery-1.8.3.min.js"></script>
</head>
<style>
    body{margin: 0px;padding: 0px;color:#5C5C5C;}
    .head{width: 96%;height: 100px;line-height: 90px;font-size:46px;padding:2%;background-color: #E8E8E8;}

    /*列表*/
    .list{width: 96%;height: 115px;padding: 2%;margin-top: 0.5%;background-color: #F7F7F7;}
    .title{font-size:42px;padding: 0.5%;}
    .flag{float: right;font-size:28px;}
    .desc{clear: both;font-size:34px;}

    /*详情*/
    .overBox {position: fixed;width: 100%;height: 100%;top: 0;bottom: 0;left: 0;right: 0;background: rgba(0,0,0,0.65);z-index: 6;overflow: hidden;}
    .detail{position: absolute;top: 50%;left: 50%;width: 80%;padding: 2%;transform: translate(-50%, -50%);border-radius: 25px;background-color: #FFF0F5;z-index: 8;}
    li{padding: 7px;font-size:38px;list-style-type:none;}
    .tit{display: inline-block;text-align: right;width: 130px;}

    /*搜索*/
    .sear{height: 120px;width: 98%;line-height: 120px;margin: 1%;}
    .input{font-size: 42px;width: 78%;height: 110px;border: 1px solid #555555;}
    .button{border-radius: 15px;width: 20%;height: 115px;font-size: 42px;}

    .show_message{position: absolute;top: 85%;left: 50%;padding: 2%;transform: translate(-50%, -50%);border-radius: 25px;color:#FCFCFC;background-color: #969696;font-size: 36px;z-index: 8;}

    /*添加*/
    .show_jia{position: fixed;top: 85%;left: 90%;padding: 5px 23px;transform: translate(-50%, -50%);border-radius: 100%;color:#FCFCFC;background-color: #969696;font-size: 60px;z-index: 5;}
    .add{position: absolute;top: 50%;left: 50%;width: 80%;padding: 2%;transform: translate(-50%, -50%);border-radius: 25px;background-color: #FFF0F5;z-index: 8;}
    .add select{font-size: 42px;height: 70px;padding: 5px;}
    .add_input{margin-top: 2%;font-size: 38px;height: 60px;width: 78%;}
    .sub_button{margin-left:5%;border-radius: 15px;width: 20%;height: 75px;font-size: 42px;}
</style>
<body>
<div class="head">
    <span><?php echo $title; ?></span>
</div>
<div class="sear">
    <input class="input" type="text" name="keystr" placeholder="Please enter keywords">
    <button class="button search_submit">Search</button>
</div>
<div id="listdiv">
<!--    <div class="list" sn="sn1">-->
<!--        <span class="title">微信公众平台</span>-->
<!--        <span class="flag">网络</span><br />-->
<!--        <span class="desc">zhuyaokun1027@126.com</span>-->
<!--    </div>-->
</div>

<div class="overBox" id="overBox" style="display: none;">
    <ul class="detail" id="detail">
        <li><span class="ps1"></span> - <span class="ps2"></span></li>
        <li><span class="ps3"></span></li>
        <li><span class="tit">User：</span><span class="ps4"></span></li>
        <li><span class="tit">Pass：</span><span class="ps5"></span></li>
        <li><span class="tit">Ques：</span><span class="ps6"></span></li>
        <li><span class="tit">Msg：</span><span class="ps7"></span></li>
        <li>
            <input type="button" class="sub_button change_account" value="Update">
            <input type="button" class="sub_button del_account" sn="" value="Delete">
            <input type="button" class="sub_button hide_jia" value="cancel">
        </li>
    </ul>
</div>

<div class="overBox" id="addBox" style="display: none;">
    <ul class="add" id="add">
        <li><span class="tit">Plat：</span><input type="text" name="platform" class="add_input"> </li>
        <li><span class="tit">Type：</span>
            <select id="platform_type">
                <?php foreach($platform_type as $key=>$value){ ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </li>
        <li><span class="tit">Link：</span><input type="text" name="platform_link" class="add_input"></li>
        <li><span class="tit">User：</span><input type="text" name="account" class="add_input"></li>
        <li><span class="tit">Pass：</span><input type="text" name="password" class="add_input"></li>
        <li><span class="tit">Ques：</span><input type="text" name="answer" class="add_input"></li>
        <li><span class="tit">Msg：</span><input type="text" name="msg" class="add_input"></li>
        <li><input type="hidden" name="do" value="insert"></li>
        <li><input type="hidden" name="sn" value=""></li>
        <li><input type="button" class="sub_button sub_plat" value="submit"><input type="button" class="sub_button hide_jia" value="cancel"></li>
    </ul>
</div>

<div class="show_message" style="display: none;">
    <span id="show_message"></span>
</div>

<div class="show_jia">
    <span>+</span>
</div>

<script>
    $(function(){
        $(".search_submit").click(function () {
            var keyword = $("input[name='keystr']").val();
            if(keyword.length<1){
                $("#listdiv").html('');
                show_message('请输入关键字');
                return false;
            }
            $.ajax({
                url: "/account/account/get_account_list",
                data: { "keyword": keyword},
                type: "POST",
                dataType : "json",
                success: function (data) {
                    if(data.error==1){
                        show_message(data.message);
                    }else{
                        $("#listdiv").html(data.liststr)
                    }
                }
            });
        });
        $(document).on("click",".list",function(){
            var sn = $(this).attr('sn');
            $.ajax({
                url: "/account/account/get_account_info",
                data: { "sn": sn},
                type: "POST",
                dataType : "json",
                success: function (data) {
                    if(data.error==1){
                        show_message(data.message);
                    }else{
                        $(".ps1").html(data.account.platform);
                        $(".ps2").html(data.account.platform_type);
                        $(".ps3").html('<a href="'+data.account.platform_link+'" target="_blank">'+data.account.platform_link+'</a>');
                        $(".ps4").html(data.account.account);
                        $(".ps5").html(data.account.password);
                        $(".ps6").html(data.account.safe_answer);
                        $(".ps7").html(data.account.remarks);
                        $(".del_account").attr('sn','sn'+data.account.id);
                        document.getElementById("overBox").style.display = "block";

                        $("input[name='platform']").val(data.account.platform);
                        document.getElementById('platform_type').onclick=function(){
                            document.getElementById('select').value=data.account.platform_type;
                        }
                        $("input[name='platform_link']").val(data.account.platform_link);
                        $("input[name='account']").val(data.account.account);
                        $("input[name='password']").val(data.account.password);
                        $("input[name='answer']").val(data.account.safe_answer);
                        $("input[name='msg']").val(data.account.remarks);
                        $("input[name='do']").val('update');
                        $("input[name='sn']").val('sn'+data.account.id);
                    }
                }
            });
        });
        $(".detail").click(function () {
            document.getElementById("overBox").style.display = "none";
        });
        $(".change_account").click(function () {
            document.getElementById("overBox").style.display = "none";
            $("input[name='do']").val('update');
            document.getElementById("addBox").style.display = "block";
        });

        $(".show_jia").click(function () {
            $("input[name='platform']").val('');
            document.getElementById('platform_type').onclick=function(){
                document.getElementById('select').value=1;
            }
            $("input[name='platform_link']").val('');
            $("input[name='account']").val('');
            $("input[name='password']").val('');
            $("input[name='answer']").val('');
            $("input[name='msg']").val('');
            $("input[name='sn']").val('');
            $("input[name='do']").val('insert');
            document.getElementById("addBox").style.display = "block";
        });
        $(".hide_jia").click(function () {
            document.getElementById("addBox").style.display = "none";
        });
        $(".sub_plat").click(function () {

            var platform = $("input[name='platform']").val();
            var platform_type = $('#platform_type option:selected') .val();
            var platform_link = $("input[name='platform_link']").val();
            var account = $("input[name='account']").val();
            var password = $("input[name='password']").val();
            var answer = $("input[name='answer']").val();
            var msg = $("input[name='msg']").val();
            var sn = $("input[name='sn']").val();
            var dos = $("input[name='do']").val();

            if(platform.length<1){
                show_message('请输入平台');
                return false;
            }if(account.length<1){
                show_message('请输入账号');
                return false;
            }
            $.ajax({
                url: "/account/account/add_account",
                data: { "do": dos,"platform": platform,"platform_type": platform_type,"platform_link": platform_link,"account": account,"password": password,"answer": answer,"sn": sn,"msg": msg},
                type: "POST",
                dataType : "json",
                success: function (data) {
                    show_message(data.message);
                    if(data.error==0){
                        document.getElementById("addBox").style.display = "none";
                    }
                }
            });
        });
        $(document).on("click",".del_account",function(){
            var sn = $(this).attr('sn');
            $.ajax({
                url: "/account/account/del_account",
                data: { "sn": sn},
                type: "POST",
                dataType : "json",
                success: function (data) {
                    if(data.error==1){
                        show_message(data.message);
                    }else{
                        $("#"+sn).remove();
                    }
                }
            });
        });
    })
    function show_message(str) {
        $("#show_message").html(str);
        $(".show_message").show();
        setTimeout(hide_message,2500);
    }
    function hide_message() {
        $(".show_message").hide();
    }
</script>
</body>
</html>