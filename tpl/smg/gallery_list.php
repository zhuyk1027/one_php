<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i>Home</li>
            <li><i class="fa fa-picture-o"></i>Gallery</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2><i class="fa fa-table red"></i><span class="break"></span><strong>Members</strong></h2>
                <div class="panel-actions">
                    <a class="btn-setting" href="table.html#"><i class="fa fa-rotate-right"></i></a>
                    <a class="btn-minimize" href="table.html#"><i class="fa fa-chevron-up"></i></a>
                    <a class="btn-close" href="table.html#"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Picpath</th>
                        <th>Img_num</th>
                        <th>Sort</th>
                        <th>Carete time</th>
                        <th>What Do</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($gallery as $row){ ?>
                    <tr>
                        <td><?php echo $row->id;?></td>
                        <td>
                            <span class="title_<?php echo $row->id;?>"><?php echo $row->title?></span>
                            <input type="text" class="input_<?php echo $row->id?>" value="<?php echo $row->title?>" onblur="up_title(<?php echo $row->id?>)" style="display: none;">
                        </td>
                        <td><img src="<?php echo $row->pic_path?>" height="50" onerror="this.src='<?php echo TPL_PATH?>images/none.png'"></td>
                        <td><?php echo $row->img_num?></td>
                        <td><span class="label label-warning"><?php echo $row->sort?></span></td>
                        <td><?php echo date('Y-m-d H:i',$row->create_time)?></td>
                        <td>
                            <a href="/smg/gallery/images/<?php echo $row->id?>/0" class="btn btn-success">
                                <i class="fa fa-search-plus "></i>
                            </a>
                            <a href="javascript:void(0)" class="btn btn-info" id="<?php echo $row->id?>">
                                <i class="fa fa-edit "></i>
                            </a>
                            <a href="javascript:void(0)" class="btn btn-danger" id="<?php echo $row->id?>">
                                <i class="fa fa-trash-o "></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!--/col-->
</div>
<script>
    //删除专辑
    $(".btn-danger").click(function(){
        var id = $(this).attr('id');
        var thiss = $(this);
        if(confirm('确认删除该相册吗')){
            show_message('相册删除中...');
            $.ajax({
                url: "/smg/gallery/del_album",
                data: { "id": id},
                type: "POST",
                dataType : "json",
                success: function (data) {
                    hide_message();
                    if(data==1){
                        thiss.parent().parent().remove();
                    }else{
                        alert('删除失败!');
                    }
                }
            });
        }
    })

    //修改专辑
    $(".btn-info").click(function(){
        var id = $(this).attr('id');
        var thiss = $(this);
        $(".title_"+id).hide();
        $(".input_"+id).show();
        $(".input_"+id).focus();
    })
    function up_title(tid){
        var title = $(".input_"+tid).val();
        $.ajax({
            url: "/smg/gallery/up_album",
            data: { "id": tid,'title':title},
            type: "POST",
            dataType : "json",
            success: function (data) {
                if(data==1){
                    $(".title_"+tid).html(title);
                    $(".title_"+tid).show();
                    $(".input_"+tid).hide();
                }
            }
        });
    }
</script>