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
                <h2><i class="fa fa-table red"></i><span class="break"></span><strong><?php echo @$album->title;?></strong></h2>
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
                        <th>Picpath</th>
                        <th>Title</th>
                        <th>size</th>
                        <th>Carete time</th>
                        <th>What Do</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($gallery as $row){ ?>
                    <tr>
                        <td><?php echo $row->id; ?></td>
                        <td><img src="<?php echo $row->pic_path; ?>" height="50" ></td>
                        <td><?php echo $row->title; ?></td>
                        <td><?php echo $row->height; ?>*<?php echo $row->width; ?></td>
                        <td><?php echo date('Y-m-d H:i',$row->create_time); ?></td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-danger" id="<?php echo $row->id;?>">
                                <i class="fa fa-trash-o "></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <ul class="pagination">
                    <?php echo $paginate;?>
                </ul>
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
            show_message('图片删除中....');
            $.ajax({
                url: "/smg/gallery/del_img",
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
</script>