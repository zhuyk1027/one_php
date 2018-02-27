<?php  $this->load->view(BLOG.'header'); ?>

    <div class="content-wrap">
        <div class="content">

            <?php  $this->load->view(BLOG.'user_home_head'); ?>

            <?php foreach($friendships as $key=>$row){ ?>
                <article class="excerpt excerpt-nothumbnail">
                    <img src="<?=$row->pic?>" style="max-width: 120px;max-height: 50px;"> &nbsp;&nbsp;&nbsp;&nbsp;
                    <font color="#428BCA">
                        <?=$row->fs_title?>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="<?=$row->hplink?>" target="_blank"><?=$row->hplink?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <?=date('Y-m-d',$row->start_time)?>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <?=date('Y-m-d',$row->end_time)?>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <?php if($row->is_on==1){echo '显示';}else{ echo '不显示'; } ?>
                    </font>
                    <span style="float:right;">
                        <a href="/friendship/up_friendship/<?php echo $row->fs_id?>">修改</a> &nbsp; | &nbsp;
                        <a href="javascript:void(0)" sid="<?php echo $row->fs_id?>" class="del_type">删除</a>
                    </span>

                </article>
            <?php  }?>

            <br />

        </div>


    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<script>
    $(".del_type").click(function(){
        var id = $(this).attr('sid');
        var thiss = $(this);
        if(confirm('确认删除该友情链接吗')){
            show_message('删除中...');
            $.ajax({
                url: "/friendship/del_friendship",
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
                        alert('该链接已被删除!');
                    }else{
                        alert('删除失败!');
                    }
                }
            });
        }
    })
</script>
<?php  $this->load->view(BLOG.'footer'); ?>
