<?php $this->load->view('smg/header'); ?>
<?php $this->load->view('smg/top'); ?>

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
                        <h2><i class="halflings-icon white edit"></i><span class="break"></span>添加/修改友情链接</h2>
                        <div class="box-icon">
                            <a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">标题</label>
                                    <div class="controls">
                                        <input class="input-xlarge focused" name="fs_title" type="text" placeholder="请输入标题" value="<?php if(isset($friendship)){ echo $friendship->fs_title; } ?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="fileInput">图标网址</label>
                                    <div class="controls">
                                        <input class="input-xlarge focused" name="pic" type="text" placeholder="请输入图片url" value="<?php if(isset($friendship)){ echo $friendship->pic; } ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">链接</label>
                                    <div class="controls">
                                        <input class="input-xlarge focused" name="hplink" type="text" placeholder="请输入跳转地址" value="<?php if(isset($friendship)){ echo $friendship->hplink; } ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="date01">开始时间</label>
                                    <div class="controls">
                                        <input type="text" class="input-xlarge datepicker" id="date01" name="start_time" value="<?php if(isset($friendship)){ echo date('m/d/Y',$friendship->start_time); }else{ echo date('m/d/Y');} ?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="date01">结束时间</label>
                                    <div class="controls">
                                        <input type="text" class="input-xlarge datepicker" id="date02" name="end_time" value="<?php if(isset($friendship)){ echo date('m/d/Y',$friendship->end_time); }else{ echo date('m/d/Y');} ?>">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input name="fs_id" type="hidden" value="<?php if(isset($friendship)){ echo $friendship->fs_id; }else{ echo 0; } ?>">
                                    <button type="button" class="btn btn-primary" onclick="add_album()">Save changes</button>
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
    function add_album(){
        var fs_title = $("input[name='fs_title']").val();
        var pic = $("input[name='pic']").val();
        var hplink = $("input[name='hplink']").val();
        var start_time = $("input[name='start_time']").val();
        var end_time = $("input[name='end_time']").val();
        var fs_id = $("input[name='fs_id']").val();
        show_message('添加友情链接中....');
        $.ajax({
            url: "/smg/friendship/add_friendship",
            data: { "fs_title": fs_title,"pic": pic,"hplink": hplink,"start_time": start_time,"end_time": end_time,"fs_id": fs_id},
            type: "POST",
            dataType : "json",
            success: function (data) {
                hide_message();
                if(data==3){
                    alert('请填写全部信息!');
                }else if(data==2){
                    alert('请求失败，请重试!');
                }else{
                    window.location.href='/smg/friendship/index';
                }
            }
        });
    }

</script>
<?php $this->load->view('smg/footer'); ?>