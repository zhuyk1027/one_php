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
                        <h2><i class="halflings-icon white edit"></i><span class="break"></span>添加博文标签</h2>
                        <div class="box-icon">
                            <a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content" style="display: none;">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">博文标签</label>
                                    <div class="controls">
                                        <input class="input-xlarge focused" name="group_name" type="text" placeholder="博文标签">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn btn-primary" onclick="add_tag()">Save changes</button>
                                </div>
                            </fieldset>
                        </form>

                    </div>
                </div><!--/span-->

            </div><!--/row-->

            <div class="row-fluid sortable">
                <div class="box span12">
                    <div class="box-header" data-original-title>
                        <h2><i class="halflings-icon white user"></i><span class="break"></span>标签列表</h2>
                    </div>
                    <div class="box-content">
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($blog_tag as $row){ ?>
                                <tr>
                                    <td><?php echo $row->tag_id;?></td>
                                    <td>
                                        <input type="text" id="group_<?php echo $row->tag_id;?>" value="<?php echo $row->tag_name;?>"
                                               onblur="change_name(<?php echo $row->tag_id;?>,this.value)">
                                    </td>
                                    <td>
                                        <a class="btn btn-danger" href="javascript:void(0)"  sid="<?php echo $row->tag_id?>">
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
    function change_name(tag_id,group_name){
        //alert(group_id+'_'+group_name);
        show_message('修改中...');
        $.ajax({
            url: "/smg/blog/up_blog_tag",
            data: { "tag_id": tag_id,"tag_name": group_name},
            type: "POST",
            dataType : "json",
            success: function (data) {
                hide_message();
                if(data==1){

                }else if(data==2){
                    alert('update失败!');
                }else if(data==3){
                    alert('参数不足!');
                }else{
                    alert('update失败!');
                }
            }
        });
    }
    function add_tag(){
        var title = $("input[name='group_name']").val();
        show_message('创建分类中....');
        $.ajax({
            url: "/smg/blog/add_tag",
            data: { "title": title},
            type: "POST",
            dataType : "json",
            success: function (data) {
                hide_message();
                if(data==-2){
                    alert('请求失败，请重试!');
                }else{
                    $("input[name='group_name']").val('');
                    document.location.reload();
            }
            }
        });
    }

    $(".btn-danger").click(function(){
        var id = $(this).attr('sid');
        var thiss = $(this);
        if(confirm('确认删除该标签吗')){
            show_message('删除中...');
            $.ajax({
                url: "/smg/blog/del_blog_tag",
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
                        alert('该博文分类已被删除!');
                    }else{
                        alert('删除失败!');
                    }
                }
            });
        }
    })
</script>


<?php $this->load->view('smg/footer'); ?>