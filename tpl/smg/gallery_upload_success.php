<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i>Home</li>
            <li><i class="fa fa-picture-o"></i>Gallery</li>
            <li><i class="fa fa-picture-o"></i>Uploads Success</li>
        </ol>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><i class="fa fa-indent red"></i><strong>Success</strong></h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-3 control-label">Inline Radios</label>
                <div class="col-md-9">
                   <?php if($success==1){echo '更改专辑成功';}else{ echo "更改专辑失败"; } ?>  。<br />
                </div>
                <div class="col-md-9">
                    <a href="/smg/gallery/upload">继续上传</a><br />
                    <a href="/smg/gallery">查看专辑</a>
                </div>

            </div>
            <br>
        </div>
    </div>
</div>