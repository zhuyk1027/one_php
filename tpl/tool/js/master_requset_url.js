//发送短信
function jump_send_mobile_message(){
    var mobile_code = $("input[name='mobile_code']").val();
    var Number = $("input[name='mobile_message_number']").val();
    window.location.href='/tool/tool/send_mobile_code/'+mobile_code+'/'+Number;
}

//聚来宝注册
function julaibao_signup(){
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


//批量签到
function jump_sign(){
    var page = $('#sign_page option:selected') .val();
    var pagesize = $("input[name='sign_page_size']").val();
    var pay_pass = $("input[name='pay_pass_sign']:checked").val();
    window.location.href='/tool/sign/baiy_all_sign/'+pay_pass+'/'+page+'/'+pagesize;
}

//批量抽奖
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

//批量注册
function jump_register(){
    var register_num = $('#register_num option:selected') .val();
    var is_auto = $("input[name='is_auto']:checked").val();
    var invite_code = $("input[name='invite_code']").val();
    window.location.href='/tool/sign/register_do/'+is_auto+'/'+invite_code+'/'+register_num;
}

//单个注册
function register(){
    var phone = $("input[name='phone1']").val();
    var code = $("input[name='code1']").val();
    var invite_code = $("input[name='invite_code1']").val();
    window.location.href='/tool/sign/register/'+phone+'/'+invite_code+'/'+code;
}

//显示返利
function show_backmoney(){
    var datetime = $("input[name='datetime']:checked").val();
    window.location.href='/tool/sign/show_backmoney/'+datetime;
}

//清除token
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



