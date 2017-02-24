<?php $this->load->view('smg/header'); ?>
<?php $this->load->view('smg/top'); ?>
<!-- 配置文件 -->
<script type="text/javascript" src="<?php echo PUB_PATH; ?>/js/ueditor1_4_utf8_php/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="<?php echo PUB_PATH; ?>/js/ueditor1_4_utf8_php/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>

<div class="container-fluid-full">
    <div class="row-fluid">
        <?php $this->load->view('smg/left'); ?>

        <!-- start: Content -->
        <div id="content" class="span10">

            <ul class="breadcrumb">
                <li>
                    <i class="icon-home"></i>
                    <?php echo $home; ?>
                    <i class="icon-angle-right"></i>
                </li>
                <li>
                    <i class="icon-edit"></i>
                    <?php echo $crumbs; ?>
                </li>
            </ul>

            <div class="row-fluid sortable">
                <div class="box span12">
                    <div class="box-header" data-original-title>
                        <h2><i class="halflings-icon white edit"></i><span class="break"></span>发布博文</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" action="/smg/blog/blog_add" method="post" enctype="multipart/form-data" onsubmit="return check_form()">
                            <fieldset>
                                <div class="control-group">
                                    <label class="control-label" for="typeahead">标题</label>
                                    <div class="controls">
                                        <input type="text" class="span6 typeahead" name="title">
                                    </div>
                                </div>
                                <div class="control-group" id="types">
                                    <label class="control-label">博文分类</label>
                                    <div class="controls" id="types">
                                        <?php foreach($blog_group as $k=>$row){ ?>
                                            <label class="radio">
                                                <input type="radio" name="group_id"  value="<?php echo $row->blog_group_id?>" ><?php echo $row->group_name?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">标签</label>
                                    <div class="controls">
                                        <?php foreach($tags as $k=>$row){ ?>
                                        <label class="radio">
                                            <label class="checkbox inline">
                                                <input type="checkbox" name="tags[]" value="<?php echo $row->tag_id?>"> <?php echo $row->tag_name?>
                                            </label>
                                        </label>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="date01">简要</label>
                                    <div class="controls">
                                        <textarea name="briefly" height="500px" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="control-group hidden-phone">
                                    <label class="control-label" for="textarea2">博文内容</label>
                                    <div class="controls">
                                        <!-- 加载编辑器的容器 -->
                                        <script id="container" name="content" type="text/plain" ></script>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <button type="reset" class="btn">Cancel</button>
                                </div>
                            </fieldset>
                        </form>

                    </div>
                </div><!--/span-->

            </div><!--/row-->
        </div><!--/.fluid-container-->

        <!-- end: Content -->
    </div><!--/#content.span10-->
</div><!--/fluid-row-->
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
<?php $this->load->view('smg/footer'); ?>