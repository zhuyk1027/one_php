<script src="<?php echo TPL_PATH?>js/ajaxfileupload.js"></script>
<style>
    .mark{display: none;}
    #pick_img li{max-height: 100px;max-width: 100px;float: left;list-style-type:none;}
    #pick_img li img{max-height: 100px;max-width: 100px;}
    ul li .cancel {
        background: rgba(0, 0, 0, 0) url("/public/images/aui_close.hover.png") no-repeat scroll center center;
        cursor: pointer;
        height: 15px;
        position: absolute;
        right: 0;
        top: 0;
        width: 15px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i>Home</li>
            <li><i class="fa fa-picture-o"></i>Gallery</li>
            <li><i class="fa fa-picture-o"></i>Uploads</li>
        </ol>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><i class="fa fa-indent red"></i><strong>Add Album</strong></h2>
        </div>
        <form class="form-horizontal " enctype="multipart/form-data" method="post" action="/smg/gallery/upload_success">
            <div class="panel-body">
                <div class="form-group">
                    <label for="file-input" class="col-md-3 control-label">Add Album</label>
                    <div class="col-md-9">
                        <input type="text" name="title" >
                        <input type="button" value="sub" onclick="add_album()">
                    </div>
                </div>
                <br>
            </div>
        </form>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><i class="fa fa-indent red"></i><strong>Basic Form Elements</strong></h2>
        </div>
        <form class="form-horizontal " enctype="multipart/form-data" method="post" action="/smg/gallery/upload_success">
        <div class="panel-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Inline Radios</label>
                    <div class="col-md-9 radio_album">
                        <?php foreach($gallery as $k=>$row){ ?>
                        <label for="inline-radio1" class="radio-inline">
                            <input type="radio" <?php if($k==0){ echo "checked"; } ?>  value="<?php echo $row->id?>" name="aid"> <?php echo $row->title?>
                        </label>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="file-input" class="col-md-3 control-label">File input</label>
                    <div class="col-md-9">
                        <input type="file" name="pic_path[]" multiple id="imgs">
                    </div>
                </div>
                <div class="form-group">
                    <label for="file-input" class="col-md-3 control-label"></label>
                    <div class="col-md-9">
<!--                        <span id="sp1"></span><span id="sp2"></span>-->
<!--                        <input type="hidden" name="ids">-->
                        <ul id="pick_img">
                        </ul>
                    </div>
                </div>
                <br>
        </div>
        <div class="panel-footer">
            <button class="btn btn-sm btn-success" id="sub_img" type="button" onclick="pick_image(this)"><i class="fa fa-dot-circle-o"></i> Submit</button>
        </div>
        </form>
    </div>
</div>

<script>
    var cur_index = 0;
    $('body').on('change','#imgs',function(){
        for (var i = 0; i < $(this).get(0).files.length; ++i) {
            var alt = 'alt="'+$(this).get(0).files[i].name+'"';
            var html = $('#pick_img').html();
            if(html.indexOf(alt) != -1) continue;
            var reader = new FileReader();
            reader.onload = function (evt) {
                $('<li><img src="'+evt.target.result+'" '+alt+' /><span class="cancel" title="删除图片"></span><div class="mark"><span>上传中</span></div></li>').appendTo('#pick_img');
            }
            reader.readAsDataURL($(this).get(0).files[i]);
        }
    })
    $('body').on('click','span.cancel',function(){
        $(this).parent().remove();
    });

    function pick_image()
    {
        if(cur_index >= $('#pick_img li').length)
        {
            alert('全部上传完毕');
            $('#pick_img').html('');
            document.getElementById("sub_img").disabled = false;
            return false;
        }
        $('#pick_img li').eq(cur_index).find('.mark').show();
        var aid = $('input[name=aid]:checked').val();
        var img = $('#pick_img li').eq(cur_index).find('img').attr('src');
        var alt = $('#pick_img li').eq(cur_index).find('img').attr('alt');

        $.ajax({
            url:'/smg/gallery/upload_form1',
            data:{aid:aid,imgs:img,alt:alt},
            type:'POST',
            success:function(msg)
            {
                $('#pick_img li').eq(cur_index).find('.mark span').html('').css({
                    "background-image":"url(/public/images/gou.png)",
                    "background-size":"30%",
                    "background-position":"center center",
                    "background-repeat":"no-repeat"
                });
                cur_index = cur_index + 1;
                setTimeout(function(){pick_image();},1000);
            },
            error:function(msg)
            {
                $('#pick_img li').eq(cur_index).find('.mark span').html('').css({
                    "background-image":"url(/public/images/gou.png)",
                    "background-size":"30%",
                    "background-position":"center center",
                    "background-repeat":"no-repeat"
                });
                cur_index = cur_index + 1;
                setTimeout(function(){pick_image();},1000);
            }
        })
    }

    function add_album(){
        var title = $("input[name='title']").val();
        show_message('相册创建中....');
        $.ajax({
            url: "/smg/gallery/add_album",
            data: { "title": title},
            type: "POST",
            dataType : "json",
            success: function (data) {
                hide_message();
                if(data==-2){
                    alert('请求失败，请重试!');
                }else{
                    $("input[name='title']").val('');
                    var str = '<label for="inline-radio1" class="radio-inline"><input type="radio" value="'+data+'" name="aid">'+title+'</lable>';
                    $(".radio_album").append(str);
                }
            }
        });
    }

//    $("#imgs").change(function(){
//        var files=document.getElementById('imgs').files,
//            fs=files.length;
//        var size = 0;
//        var max_size = 3*1024*1024;
//        //判断图片的大小，超过2m张就不能上传
//        for(var i=0 ; i<fs ; i++){
//            size = size+files[i].size;
//        }
//        if(size>max_size){
//            alert('请保持总文件小于3M');return;
//        }
//        show_message('图片上传中');
//        $.ajaxFileUpload
//        ({
//            url:'/smg/gallery/upload_form',
//            secureuri:false,//一般设置为false
//            fileElementId:$('#imgs'),
//            dataType: 'json',//返回值类型 一般设置为json
//            success: function (data, status)  //服务器成功响应处理函数
//            {
//                hide_message();
//                //{"all":1,"success_num":1,"pic_path":["http:\/\/happylife.image.alimmdn.com\/images\/20160806\/7f90005fe01dc3b3fb3.jpg"],"id":[33]}
//                $("#sp1").html('共计：'+data['all']+'个，');
//                $("#sp2").html('成功：'+data['success_num']+'个');
//                var id_str = data['id'].join(",");
//                $("input[name='ids']").val(id_str);
//            }
//        })
//        event.stopPropagation();
//    })
</script>