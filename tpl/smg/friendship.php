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
                <li><?php echo $crumbs; ?></li>
            </ul>

            <div class="row-fluid sortable">
                <div class="box span12">
                    <div class="box-header" data-original-title>
                        <h2><i class="halflings-icon white user"></i><span class="break"></span>Members</h2>
                    </div>
                    <div class="box-content">
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Pic</th>
                                <th>Hplink</th>
                                <th>Start time</th>
                                <th>Ened time</th>
                                <th>Update time</th>
                                <th>Carete time</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($friendship as $row){ ?>
                                <tr>
                                    <td><?php echo $row->fs_title;?></td>
                                    <td><img src="<?php echo $row->pic;?>" height="80"></td>
                                    <td><?php echo $row->hplink;?></td>
                                    <td><?php echo date('Y-m-d H:i:s',$row->start_time);?></td>
                                    <td><?php echo date('Y-m-d H:i:s',$row->end_time);?></td>
                                    <td><?php if($row->update_time){ echo date('Y-m-d H:i:s',$row->update_time);}?></td>
                                    <td><?php echo date('Y-m-d H:i',$row->create_time)?></td>
                                    <td width="100px">
                                        <a class="btn btn-info" href="/smg/friendship/up_friendship/<?php echo $row->fs_id?>" >
                                            <i class="halflings-icon white edit"></i>
                                        </a>
                                        <a class="btn btn-danger" href="javascript:void(0)"  sid="<?php echo $row->fs_id?>">
                                            <i class="halflings-icon white trash"></i>

                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div><!--/span-->
            </div><!--/row-->

        </div><!--/.fluid-container-->

        <!-- end: Content -->
    </div><!--/#content.span10-->
</div><!--/fluid-row-->

<script>
    //删除专辑
    $(".btn-danger").click(function(){
        var id = $(this).attr('sid');
        var thiss = $(this);
        if(confirm('确认删除吗')){
            show_message('删除中...');
            $.ajax({
                url: "/smg/friendship/del_friendship",
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
                    }else if(data==3){
                        alert('已被删除!');
                    }else{
                        alert('删除失败!');
                    }
                }
            });
        }
    })
</script>


<?php $this->load->view('smg/footer'); ?>