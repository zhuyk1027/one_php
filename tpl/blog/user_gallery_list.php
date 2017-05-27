<?php  $this->load->view(BLOG.'header_user'); ?>

    <div class="content-wrap">
        <div class="content">

            <?php  $this->load->view(BLOG.'user_home_head'); ?>
            <?php foreach($gallery as $key=>$row){ ?>
                <article class="excerpt excerpt-nothumbnail">
                    <?=$row->title?>
                    <span style="float:right;">
                        <a href="/gallery/images/<?php echo $row->id?>" >查看</a> &nbsp; | &nbsp;
                        <a href="javascript:void(0)" sid="<?php echo $row->id?>" aname="<?=$row->title?>" class="sub_up">修改</a> &nbsp; | &nbsp;
                        <a href="javascript:void(0)" sid="<?php echo $row->id?>" class="del_blog">删除</a>
                    </span>

                </article>
            <?php  }?>

            <div class="widget widget_text">
                <h3 class="widget_tit">相册操作</h3>
                <div class="textwidget">
                    <div style="padding:1em;">
                        <form>
                            <fieldset>
                                <div class="control-group">
                                    <label class="control-label" for="typeahead">标题</label>
                                    <div class="controls">
                                        <input type="text" class="span6 typeahead" name="title">
                                        <input type="hidden" name="gallery_id" value="0">
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
        $("input[name='title']").val(aname);
        $("input[name='gallery_id']").val(id);
        $("input[name='title']").focus();
    })
    $(".sub_type").click(function(){
        var url = '';
        var title = $("input[name='title']").val();
        var gallery_id = $("input[name='gallery_id']").val();
        //alert(title);return false;
        show_message('操作中...');

        if(gallery_id!=0){
            url='/gallery/up_album';
        }else{
            url='/gallery/add_album';
        }
        $.ajax({
            url: url,
            data: { "gallery_id": gallery_id,"title": title},
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

    //删除专辑
    $(".del_blog").click(function(){
        var id = $(this).attr('sid');
        var thiss = $(this);
        if(confirm('确认删除该相册吗')){
            show_message('删除中...');
            $.ajax({
                url: "/gallery/del_album",
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
                    }else{
                        alert('删除失败!');
                    }
                }
            });
        }
    })
</script>
<?php  $this->load->view(BLOG.'footer'); ?>
