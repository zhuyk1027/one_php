<!--加载bannercss-->
<link rel="stylesheet" href="<?php echo BLOG_CSS; ?>banner_common.css" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo BLOG_CSS; ?>banner_jd.css" type="text/css" media="all">
<style>
    .zxx_body .zxx_constr {width: 1210px;}
</style>

<div class="jd_body">
    <div id="jdAdSlide" class="jd_ad_slide" style="width: 100%;">
        <a href='https://shop414524429.taobao.com/shop/view_shop.htm?spm=a1z0k.6846577.0.0.T1M2JN&shop_id=414524429' target="_blank"><img src="/tpl/blog/img/advertisement/2.jpg" class="jd_ad_img"></a>
        <div id="jdAdBtn" class="jd_ad_btn"></div><!-- data-src='' -->
    </div>
</div>

<script src="<?php echo BLOG_JS; ?>jquery.min.js"></script>
<script src="<?php echo BLOG_JS; ?>jquery-powerSwitch.js"></script>
<script>
    // 大的图片广告
    // 根据图片创建id,按钮元素等，实际开发建议使用JSON数据类似
    var htmlAdBtn = '';
    $("#jdAdSlide img").each(function(index, image) {
        var id = "adImage" + index;
        htmlAdBtn = htmlAdBtn + '<a href="javascript:" class="jd_ad_btn_a" data-rel="'+ id +'">'+ (index + 1) +'</a>';
        image.id = id;
    });
    $("#jdAdBtn").html(htmlAdBtn).find("a").powerSwitch({
        eventType: "hover",
        classAdd: "active",
        animation: "fade",
        autoTime: 5000,
        onSwitch: function(image) {
            if (!image.attr("src")) {
                image.attr("src", image.attr("data-src"));
            }
        }
    }).eq(0).trigger("mouseover");

    // 便民服务
    $("#servNav a").powerSwitch({
        classAdd: "active",
        eventType: "hover",
        onSwitch: function() {
            $("#pointLine").animate({ "left": $(this).position().left}, 200);
        }
    });
</script>