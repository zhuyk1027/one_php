<?php  $this->load->view(BLOG.'header_user'); ?>

    <div class="content-wrap">
        <div class="content">

            <?php  $this->load->view(BLOG.'user_home_head'); ?>


            <?php foreach($blog as $key=>$row){ ?>
                <article class="excerpt excerpt-nothumbnail">
                    <?=$row->title?>
                    <span style="float:right;">
                        <?=$row->group_name?> &nbsp; | &nbsp;
                        <?=date('Y/m/d H:i',$row->create_date)?> &nbsp; | &nbsp;
                        <a href="/blog/detail/<?php echo $row->blog_id?>" target="_blank">查看</a> &nbsp; | &nbsp;
                        <a href="/user/up_blog/<?php echo $row->blog_id?>" >修改</a> &nbsp; | &nbsp;
                        <a href="javascript:void(0)"  sid="<?php echo $row->blog_id?>" class="del_blog">删除</a>
                    </span>

                </article>
            <?php  }?>

            <h2 class="title"><?php if(isset($paginate)){ echo $paginate; } ?></h2>
        </div>
    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<script>
    //删除专辑
    $(".del_blog").click(function(){
        var id = $(this).attr('sid');
        var thiss = $(this);
        if(confirm('确认删除该博文吗')){
            show_message('删除中...');
            $.ajax({
                url: "/user/del_blog",
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
                        alert('该博文已被删除!');
                    }else{
                        alert('删除失败!');
                    }
                }
            });
        }
    })
</script>
<?php  $this->load->view(BLOG.'footer'); ?>
