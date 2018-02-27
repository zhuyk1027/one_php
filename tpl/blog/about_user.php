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
                    <form class="form-horizontal" action="/about/user" method="post" enctype="multipart/form-data" onsubmit="return check_form()">
                        <fieldset>
                            <?php if($this->session->userdata('id')==1){ ?>
                            <div class="control-group hidden-phone">
                                <label class="control-label" for="textarea2">是否显示</label>
                                <div class="controls">
                                    <select name="stu">
                                        <option value="1">显示</option>
                                        <option value="0" <?php if(isset($station->user_id) && $station->stu==2){ echo "selected"; } ?>>不显示</option>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="control-group hidden-phone">
                                <label class="control-label" for="textarea2">关于本站</label>
                                <div class="controls">
                                    <!-- 加载编辑器的容器 -->
                                    <script id="container" name="content" type="text/plain" >
                                        <?php if(isset($station->cont)){ echo $station->cont; } ?>
                                    </script>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                            </div>

                            <div class="form-actions">
                                <span style="color:gray;">专访地址：
                                    <?php if(isset($station->key)){ ?>
                                    <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/about/user_show/'.$station->key; ?>"><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/about/user_show/'.$station->key; ?></a>
                                    <?php } ?>
                                </span>
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
        this.submit();
    }

</script>
<?php  $this->load->view(BLOG.'footer'); ?>
