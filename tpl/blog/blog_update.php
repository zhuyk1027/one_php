<?php  $this->load->view(BLOG.'header'); ?>
<!-- 配置文件 -->
<script type="text/javascript" src="<?php echo PUB_PATH; ?>/js/ueditor1_4_utf8_php/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="<?php echo PUB_PATH; ?>/js/ueditor1_4_utf8_php/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>
<div class="content-wrap">
    <div class="content">

        <?php  $this->load->view(BLOG.'user_home_head'); ?>

        <div class="widget widget_text">
            <h3 class="widget_tit">修改博文</h3>
            <div class="textwidget">
                <div style="padding:1em;">
                    <form class="form-horizontal" action="/user/blog_update" method="post" enctype="multipart/form-data" onsubmit="return check_form()">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="typeahead">标题</label>
                                <div class="controls">
                                    <input type="text"  name="title" value="<?php echo $blog->title; ?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="typeahead">是否置顶</label>
                                <div class="controls">
                                    <input type="radio" name="is_top"  <?php if($blog->is_top==0){ echo "checked"; } ?> value="0" >不置顶
                                    <input type="radio" name="is_top"  <?php if($blog->is_top==1){ echo "checked"; } ?> value="1" >置顶
                                </div>
                            </div>

                            <div class="control-group" id="types">
                                <label class="control-label">博文分类</label>
                                <div class="controls" id="types">
                                    <?php foreach($blog_group as $k=>$row){ ?>
                                        <label style="float: left;padding: 5px;line-height: 24px;">
                                            <input type="radio" name="group_id"  <?php if($blog->group_id==$row->blog_group_id){ echo "checked"; } ?> value="<?php echo $row->blog_group_id?>" ><?php echo $row->group_name?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">标签</label>
                                <div class="controls">
                                    <?php
                                    $checked = explode(',',$blog->tags);
                                    foreach($tags as $k=>$row){ ?>
                                        <label class="checkbox inline">
                                            <input type="checkbox" name="tags[]" <?php if($checked)if(in_array($row->tag_id,$checked)){ echo "checked"; } ?> value="<?php echo $row->tag_id?>"> <?php echo $row->tag_name?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="date01">简要</label>
                                <div class="controls">
                                    <textarea name="briefly" height="500px" rows="2"><?php echo $blog->briefly; ?></textarea>
                                </div>
                            </div>

                            <div class="control-group hidden-phone">
                                <label class="control-label" for="textarea2">博文内容</label>
                                <div class="controls">
                                    <!-- 加载编辑器的容器 -->
                                    <script id="container" name="content" type="text/plain"><?php echo $blog->cont; ?></script>
                                </div>
                            </div>
                            <div class="form-actions">
                                <input type="hidden" name="blog_id" value="<?php echo $blog->blog_id; ?>">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <button type="reset" class="btn" onclick="history.go(-1)">Cancel</button>
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
    function check_form()
    {
        //title group_id tags[] briefly container/content
        var title = $("input[name='title']").val();
        var group_id = $("input[name='group_id']:checked").val();;
        var briefly = $("textarea[name='briefly']").val();
        var html = ue.getContent();

        if(title.length<1){
            alert('请输入标题');return false;
        }if(briefly.length<1){
        alert('请输入简要内容');return false;
    }if(html.length<1){
        alert('请输入内容后再选择提交');return false;
    }if(group_id==0){
        alert('请选择博文分类');return false;
    }

        this.submit();
    }

</script>
<?php  $this->load->view(BLOG.'footer'); ?>
