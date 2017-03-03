<div class="widget widget_recent_entries">
    <h3 class="widget_tit">广告轮播</h3>
    <ul id="banner_list">
    <li id="banner_1" style="display: none">
        <a href='https://shop414524429.taobao.com/shop/view_shop.htm?spm=a1z0k.6846577.0.0.T1M2JN&shop_id=414524429'  target="_blank">
            <img src="/tpl/blog/img/advertisement/1.jpg" class="jd_ad_img">
        </a>
    </li>
    <li id="banner_2" style="display: none">
        <a href='https://shop414524429.taobao.com/shop/view_shop.htm?spm=a1z0k.6846577.0.0.T1M2JN&shop_id=414524429'  target="_blank">
            <img src="/tpl/blog/img/advertisement/2.jpg" class="jd_ad_img">
        </a>
    </li>
    </ul>
</div>

<script>

    var banner_count = $('#banner_list li').length;
    var i=1;

    banner();
    setInterval("banner()",5000);

    function banner(){
        $('#banner_'+i).css("display",'block');
        for (var j=1,len=banner_count+1; j<len; j++) {
            if(i!=j){
                $('#banner_'+j).css("display",'none');
            }
        }
        i = i+1;
        if(i>banner_count){ i=1;    }
    }
</script>