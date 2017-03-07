<?php  $this->load->view(BLOG.'header_user'); ?>

    <div class="content-wrap">
        <div class="content">

            <?php  $this->load->view(BLOG.'user_home_head'); ?>

            <?php foreach($blog_group as $key=>$row){ ?>
                <article class="excerpt excerpt-nothumbnail">
                    <?=$row->group_name?>
                    <span style="float:right;">
                        <a href="javascript:void(0)" sid="<?php echo $row->blog_group_id?>" aname="<?=$row->group_name?>" class="sub_up">修改</a> &nbsp; | &nbsp;
                        <a href="javascript:void(0)" sid="<?php echo $row->blog_group_id?>" class="del_type">删除</a>
                    </span>

                </article>
            <?php  }?>

            <br />

            <div class="widget widget_text">
                <h3 class="widget_tit">添加分类</h3>
                <div class="textwidget">
                    <div style="padding:1em;">
                        <form>
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="typeahead">标题</label>
                                <div class="controls">
                                    <input type="text" class="span6 typeahead" name="title">
                                    <input type="hidden" name="group_id" value="0">
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary sub_type">Save changes</button>
                                <button type="reset" class="btn">Cancel</button>
                            </div>
                        </fieldset>
                        </form>
                    </div>
                </div>
            </div>

        </div>


    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<script>
    //删除专辑
    $(".sub_up").click(function(){
        var id = $(this).attr('sid');
        var aname = $(this).attr('aname');
        $("input[name='title']").val(aname);
        $("input[name='group_id']").val(id);
        $("input[name='title']").focus();
    })
    $(".sub_type").click(function(){
        var url = '';
        var title = $("input[name='title']").val();
        var group_id = $("input[name='group_id']").val();
        //alert(title);return false;
        show_message('操作中...');

        if(group_id!=0){
            url='/user/up_blog_type';
        }else{
            url='/user/add_group';
        }
        $.ajax({
            url: url,
            data: { "group_id": group_id,"group_name": title},
            type: "POST",
            dataType : "json",
            success: function (data) {
                hide_message();
                if(data==2){
                    alert('操作成功!');return false;
                }else if(data==-2){
                    alert('参数不足!');return false;
                }
            }
        });
    })
    $(".del_type").click(function(){
        var id = $(this).attr('sid');
        var thiss = $(this);
        if(confirm('确认删除该博文分类吗')){
            show_message('删除中...');
            $.ajax({
                url: "/user/del_blog_type",
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
