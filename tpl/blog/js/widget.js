function show_message(msg,is_over)
{
    var pos = 0;
    if (window.innerHeight)
    {
        pos = window.pageYOffset;
    }
    else if (document.documentElement && document.documentElement.scrollTop)
    {
        pos = document.documentElement.scrollTop;
    }
    else if (document.body)
    {
        pos = document.body.scrollTop;
    }
    var d_width = $(document).width();
    var mark_width = 350;
    var off_left = d_width / 2 - mark_width / 2;
    var off_top = pos + 250;
    if($('#ui-tx-mark-message').length > 0)
    {
        $('#ui-tx-mark-message').css({left:off_left,top:off_top}).html(msg).show();
    }
    else
    {
        $('<div id="ui-tx-mark-message"></div>').css({left:off_left,top:off_top}).appendTo("body").html(msg).show();
    }
    if(is_over)
    {
        setTimeout(function(){$('#ui-tx-mark-message').remove()},2000);
    }
}

function hide_message()
{
    if($('#ui-tx-mark-message').length > 0)
    {
        $('#ui-tx-mark-message').remove();
    }
}

$(function(){
    //后台登录
    $(".login_but").click(function(){
        var name = $("input[name='username']").val();
        var pass = $("input[name='password']").val();
        if(name==''){
            Alert('请输入用户名');return false;
        }if(pass==''){
            Alert('请输入密码');return false;
        }
        show_message('登录中。。。');
        $("#mess").html('登录中...');
        $.ajax({
            url: "/login/verification",
            data: { "name": name,"pass": pass},
            type: "POST",
            dataType : "json",
            success: function (stu) {
                hide_message();
                if(stu==100001){
                    window.location.href='/User'
                }else if(stu==100002){
                    Alert('密码不正确');
                    $("#mess").html('密码不正确');
                    return false;
                }else if(stu==100003){
                    Alert('您的账户已被关闭');
                    $("#mess").html('您的账户已被关闭');
                    return false;
                }else{
                    Alert(stu);
                    $("#mess").html(stu);
                    return false;
                }
            }
        });
    })

})

