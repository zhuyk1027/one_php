<?php  $this->load->view(BLOG.'header'); ?>

<style>
    #pick_img li{float:left;}
    #pick_img li img{max-height: 100px;max-width: 150px;}
</style>

<div class="content-wrap">
    <div class="content">

        <?php  $this->load->view(BLOG.'user_home_head'); ?>

        <div class="widget widget_text">
            <h3 class="widget_tit">修改博文</h3>
            <div class="textwidget" style="min-height: 700px;">
                <div style="padding:1em;">
                    <form class="form-horizontal" action="/user/blog_add" method="post" enctype="multipart/form-data" onsubmit="return check_form()">
                        <fieldset>
                            <div class="control-group" id="types">
                                <label class="control-label">相册：</label>
                                <div class="controls" id="types">
                                    <?php foreach($gallery as $k=>$row){ ?>
                                        <label style="float: left;padding: 5px;line-height: 24px;">
                                            <input type="radio" name="aid" <?php if($k==0){ echo "checked"; } ?> value="<?php echo $row->id?>" ><?php echo $row->title?>
                                        </label>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date01">File input</label>
                                <div class="controls">
                                    <input type="file" name="pic_path[]" multiple id="imgs">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date01">预览：</label>
                                <div class="controls">
                                    <ul id="pick_img">
                                    </ul>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-primary" id="sub_img" onclick="pick_image()">Save changes</button>
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

    var cur_index = 0;
    $('body').on('change','#imgs',function(){
        for (var i = 0; i < $(this).get(0).files.length; ++i) {
            var alt = 'alt="'+$(this).get(0).files[i].name+'"';
            var html = $('#pick_img').html();
            if(html.indexOf(alt) != -1) continue;
            var reader = new FileReader();
            reader.onload = function (evt) {
                $('<li><img src="'+evt.target.result+'" '+alt+'  /><br /><span class="cancel" title="删除图片">删除</span>'
                    +'<div class="mark"><span>准备上传中...</span></div></li>').appendTo('#pick_img');
            }
            reader.readAsDataURL($(this).get(0).files[i]);
        }
    })
    $('body').on('click','span.cancel',function(){
        $(this).parent().remove();
    });

    function pick_image()
    {
        console.log(cur_index);
        if(cur_index >= $('#pick_img li').length)
        {
            alert('全部上传完毕');
            //$('#pick_img').html('');
            document.getElementById("sub_img").disabled = false;
            return false;
        }
        $('#pick_img li').eq(cur_index).find('.mark').show();
        var aid = $('input[name=aid]:checked').val();
        var img = $('#pick_img li').eq(cur_index).find('img').attr('src');
        var alt = $('#pick_img li').eq(cur_index).find('img').attr('alt');

        $.ajax({
            url:'/gallery/upload_form',
            data:{aid:aid,imgs:img,alt:alt},
            type:'POST',
            success:function(msg)
            {
                $('#pick_img li').eq(cur_index).find('.mark span').html('已上传').css({
                    "background-image":"url(/tpl/blog/img/gougou.png)",
                    "background-size":"30%",
                    "background-position":"center center",
                    "background-repeat":"no-repeat"
                });
                cur_index = cur_index + 1;
                setTimeout(function(){pick_image();},1000);
            },
            error:function(msg)
            {
                $('#pick_img li').eq(cur_index).find('.mark span').html('上传失败').css({
                    "background-image":"url(/tpl/blog/img/aui_close.hover.png)",
                    "background-size":"30%",
                    "background-position":"center center",
                    "background-repeat":"no-repeat"
                });
                cur_index = cur_index + 1;
                setTimeout(function(){pick_image();},1000);
            }
        })
    }

</script>
<?php  $this->load->view(BLOG.'footer'); ?>
