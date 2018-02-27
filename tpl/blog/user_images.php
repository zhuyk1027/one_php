<?php  $this->load->view(BLOG.'header'); ?>

    <div class="content-wrap">
        <div class="content">

            <?php  $this->load->view(BLOG.'user_home_head'); ?>


            <?php foreach($gallery as $key=>$row){ ?>
                <article class="excerpt excerpt-nothumbnail">
                    <img src="<?=$row->pic_path?>"  style="max-width: 120px;height: 80px;" > &nbsp;  &nbsp;
                    <?=$row->title?>&nbsp; | &nbsp;
                    <?=$row->height?>*<?=$row->width?>&nbsp; | &nbsp;
                    <?=date('Y-m-d H:i')?>
                    <span style="float:right;">
                        <a href="<?=$row->pic_path?>" target="_blank">查看</a> &nbsp; | &nbsp;
                        <a href="javascript:void(0)"  sid="<?php echo $row->id?>" class="del_img">删除</a>
                    </span>

                </article>
            <?php  }?>

            <h2 class="title"><?php if(isset($paginate)){ echo $paginate; } ?></h2>
        </div>
    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<script>
    //删除专辑
    $(".del_img").click(function(){
        var id = $(this).attr('sid');
        var thiss = $(this);
        if(confirm('确认删除该图片吗')){
            show_message('删除中...');
            $.ajax({
                url: "/gallery/del_img",
                data: { "id": id},
                type: "POST",
                dataType : "json",
                success: function (data) {
                    hide_message();
                    if(data==1){
                        thiss.parent().parent().remove();
                    }else if(data==2){
                        alert('删除失败!');
                    }else if(data==-2){
                        alert('参数不足!');
                    }else if(data==3){
                        alert('已被删除!');
                    }else{
                        alert('删除失败!');
                    }
                }
            });
        }
    })
</script>
<?php  $this->load->view(BLOG.'footer'); ?>
