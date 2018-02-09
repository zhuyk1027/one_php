<aside class="sidebar">

    <div class="widget ">
        <p class="user1" style="text-align: center;">
            <p class="user3"><?=WEB_MASTER?></p>
            <p class="user4">
                <?=SIGN?><br /><br />
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=&site=qq&menu=yes">
                    <img src="/tpl/blog/img/icon_QQ.png" width="20">
                    联系站长
                </a>&nbsp;&nbsp;
                <a target="_blank" href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=GSgpListKi4sKS1ZaGg3enZ0" style="text-decoration:none;">
                    <img src="http://rescdn.qqmail.com/zh_CN/htmledition/images/function/qm_open/ico_mailme_01.png"/></a>
            </p>
        </p>
    </div>

    <div class="widget d_tag">
        <h3 class="widget_tit">Tags</h3>
        <div class="d_tags">
            <?php foreach($groups as $key=>$row){ ?>
                <a href="/blog/blog_list?type=<?=$row->blog_group_id?>"><?=$row->group_name?> (<?=$row->num?>)</a>
            <?php  }?>
            <?php foreach($tags as $key=>$row){ ?>
                <a href="/blog/blog_list?tag=<?=$row->tag_id?>"><?=$row->tag_name?> (<?=$row->num?>)</a>
            <?php  }?>
        </div>
    </div>

    <?php  $this->load->view(BLOG.'banner'); ?>

    <div class="widget widget_recent_entries">
        <h3 class="widget_tit">Hot Article</h3>
        <ul>
            <?php foreach($ranking as $key=>$row){ ?>
                <li>
                    <a href="/blog/detail/<?=$row->blog_id?>"><?=$row->title?></a>
                </li>
            <?php  }?>
        </ul>
    </div>

    <?php foreach($friendship as $key=>$row){ ?>
        <p>
            <a href="javascript:void(0);" class="fiend_click" fsid="<?php echo $row->fs_id; ?>" title="<?php echo$row->fs_title;?>" target="_blank">
            </a>
        </p>
    <?php }?>

</aside>
<script>
    $(function(){
        $(".fiend_click").click(function(){
            var fd_id = this.getAttribute("fsid");
            $.ajax({
                url: "/dos/jump",
                data: { "id": fd_id},
                type: "POST",
                dataType : "json",
                success: function (data) {
                    window.open(data);
                }
            });
        })
        $(".ad_list").click(function(){
            var ad_id = this.getAttribute("adid");
            $.ajax({
                url: "/dos/jump_ad",
                data: { "id": ad_id},
                type: "POST",
                dataType : "json",
                success: function (data) {
                    window.open(data);
                }
            });
        })
    })
</script>