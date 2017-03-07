<?php  $this->load->view(BLOG.'header_user'); ?>

    <div class="content-wrap">
        <div class="content">

            <?php  $this->load->view(BLOG.'user_home_head'); ?>

            <?php foreach($blog_tag as $key=>$row){ ?>
                <article class="excerpt excerpt-nothumbnail">
                    <?=$row->tag_name?>
                    <span style="float:right;">
                        <a href="javascript:void(0)" sid="<?php echo $row->tag_id?>" aname="<?=$row->tag_name?>" class="sub_up">修改</a> &nbsp; | &nbsp;
                        <a href="javascript:void(0)" sid="<?php echo $row->tag_id?>" class="del_type">删除</a>
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
                                    <input type="text" class="span6 typeahead" name="tag_name">
                                    <input type="hidden" name="tag_id" value="0">
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
    $(".sub_up").click(function(){
        var id = $(this).attr('sid');
        var aname = $(this).attr('aname');
        $("input[name='tag_name']").val(aname);
        $("input[name='tag_id']").val(id);
        $("input[name='tag_name']").focus();
    })
    $(".sub_type").click(function(){
        var url = '';
        var tag_name = $("input[name='tag_name']").val();
        var tag_id = $("input[name='tag_id']").val();
        //alert(title);return false;
        show_message('操作中...');

        if(tag_id!=0){
            url='/user/up_blog_tag';
        }else{
            url='/user/add_tag';
        }
        $.ajax({
            url: url,
            data: { "tag_id": tag_id,"tag_name": tag_name},
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
                url: "/user/del_blog_tag",
                data: { "tag_id": id},
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
