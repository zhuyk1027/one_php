<div class="widget widget_recent_entries">
    <h3 class="widget_tit">广告轮播</h3>
    <ul id="banner_list">
        <?php foreach($ad as $key=>$row){ ?>
            <li id="banner_<?=$key+1?>" style="display: none">
                <a href='javascript:void(0)' adid="<?=$row->ad_id?>" class="ad_click">
                    <img src="<?=$row->pic?>" class="jd_ad_img">
                </a>
            </li>
        <?php } ?>
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