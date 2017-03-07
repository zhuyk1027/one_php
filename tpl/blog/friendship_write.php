<?php  $this->load->view(BLOG.'header_user'); ?>

<div class="content-wrap">
    <div class="content">

        <?php  $this->load->view(BLOG.'user_home_head'); ?>

        <div class="widget widget_text">
            <h3 class="widget_tit">添加友情链接</h3>
            <div class="textwidget">
                <div style="padding:1em;">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="focusedInput">标题</label>
                                <div class="controls">
                                    <input class="input-xlarge focused" name="fs_title" type="text" placeholder="请输入标题" value="<?php if(isset($friendship_info)){ echo $friendship_info->fs_title; } ?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="fileInput">图标网址</label>
                                <div class="controls">
                                    <input class="input-xlarge focused" name="pic" type="text" placeholder="请输入图片url" value="<?php if(isset($friendship_info)){ echo $friendship_info->pic; } ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="focusedInput">链接</label>
                                <div class="controls">
                                    <input class="input-xlarge focused" name="hplink" type="text" placeholder="请输入跳转地址" value="<?php if(isset($friendship_info)){ echo $friendship_info->hplink; } ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date01">开始时间</label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge datepicker" id="date01" name="start_time" value="<?php if(isset($friendship_info)){ echo date('m/d/Y',$friendship_info->start_time); }else{ echo date('m/d/Y');} ?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date01">结束时间</label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge datepicker" id="date02" name="end_time" value="<?php if(isset($friendship_info)){ echo date('m/d/Y',$friendship_info->end_time); }else{ echo date('m/d/Y');} ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date01">是否有效</label>
                                <div class="controls">
                                    <input type="radio" name="is_on" value="1" <?php if(isset($friendship_info) && $friendship_info->is_on=='1'){ echo "checked"; } if(!isset($friendship_info)){ echo "checked"; } ?>>&nbsp;是 &nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="is_on" value="0" <?php if(isset($friendship_info) && $friendship_info->is_on=='0'){ echo "checked"; } ?>>&nbsp;否
                                </div>
                            </div>
                            <div class="form-actions">
                                <input name="fs_id" type="hidden" value="<?php if(isset($friendship_info)){ echo $friendship_info->fs_id; }else{ echo 0; } ?>">
                                <button type="button" class="btn btn-primary" onclick="add_album()">Save changes</button>
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
    function add_album(){
        var fs_title = $("input[name='fs_title']").val();
        var pic = $("input[name='pic']").val();
        var hplink = $("input[name='hplink']").val();
        var start_time = $("input[name='start_time']").val();
        var end_time = $("input[name='end_time']").val();
        var fs_id = $("input[name='fs_id']").val();
        var is_on = $("input[name='is_on']:checked").val();
        show_message('添加友情链接中....');
        $.ajax({
            url: "/friendship/add_friendship",
            data: { "fs_title": fs_title,"pic": pic,"hplink": hplink,"start_time": start_time,"end_time": end_time,"fs_id": fs_id,"is_on": is_on},
            type: "POST",
            dataType : "json",
            success: function (data) {
                hide_message();
                if(data==3){
                    alert('请填写全部信息!');
                }else if(data==2){
                    alert('请求失败，请重试!');
                }else{
                    window.location.href='/friendship/index';
                }
            }
        });
    }

</script>
<?php  $this->load->view(BLOG.'footer'); ?>
