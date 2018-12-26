var flag=0;
//json解析
function tojsondecode(){
    if(flag==1){
        return false;
    }

    flag=1;

    var words = $("textarea[name='words']").val();
    $.ajax({
        url: "/tool/tool/json_to_decode",
        data: { "words": words},
        type: "POST",
        dataType : "text",
        success: function (data) {
            $("span[name='froms']").html(data);
            $("input[name='words']").val('');
            flag=0;
        }
    });
}

//md5加密
function tomd5(){
    if(flag==1){
        return false;
    }flag=1;

    var words = $("input[name='words']").val();
    $.ajax({
        url: "/tool/tool/md5_to_encode",
        data: { "words": words},
        type: "POST",
        dataType : "text",
        success: function (data) {
            $("span[name='froms']").html(data);
            $("input[name='words']").val('');
            flag=0;
        }
    });
}

//邮件发送
function send_email(){
    if(flag==1){
        return false;
    }flag=1;

    var to = $("input[name='to']").val();
    var title = $("input[name='title']").val();
    var conts = $("textarea[name='conts']").val();
    $.ajax({
        url: "/tool/tool/send_email",
        data: { "to": to,"title": title,"conts": conts},
        type: "POST",
        dataType : "text",
        success: function (data) {
            $("span[name='froms']").html(data);
            $("input[name='to']").val('');
            $("input[name='title']").val('');
            $("textarea[name='conts']").val('');
            flag=0;
        }
    });
}

//base64数据转化
function base_64change(dos){
    var strword = $("input[name='strword']").val();
    $.ajax({
        url: "/tool/tool/to_base64_change",
        data: { "strword": strword,'dos':dos},
        type: "POST",
        dataType : "json",
        success: function (data) {
            $("input[name='strword']").val('');
            $("span[name='froms']").html(data);
        }
    });
}

//请求注册聚来宝
function julaibao_sign(){
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

//时间转换
function time_changes(dos){
    var shijianchuo = $("input[name='dateinfo']").val();
    $.ajax({
        url: "/tool/tool/to_time_change",
        data: { "shijianchuo": shijianchuo,"dos": dos},
        type: "POST",
        dataType : "json",
        success: function (data) {
            $("span[name='time_change']").html(data);
        }
    });
}

//js生成二维码
$(".create_qrcode").click(function(){
    var url = $("input[name='url']").val();
    if(url==''){
        alert('请输入要生成的网址!');return false;
    }
    if( url.indexOf('http://')<0 ){
        url = 'http://'+url;
    }
    $('#qrcode').html('');
    //jQuery('#qrcode').qrcode("http://blog.wpjam.com");    //不控制大小
    jQuery('#qrcode').qrcode({width: 128,height: 128,text: url});   //控制大小
})




