<?php  $this->load->view(BLOG.'header'); ?>

    <div class="content-wrap">
        <div class="content" style="background-color: white;">

            <div style="clear: left;padding: 5px;">

                <h3 style="padding-left: 10px;"><?=$album_title?></h3>

                <ul>
                    <?php foreach($images as $key=>$row){ ?>
                        <li style="float: left;margin-left: 1%;margin-bottom: 1%;">
                            <img src="<?=$row->pic_path?>" title="<?=$row->title?>" style="max-width: 150px;" >
                        </li>
                    <?php  }?>
                </ul>

            </div>

            <p style="clear: both;"></p>

            <h2 class="title" ><?php if(isset($paginate)){ echo $paginate; } ?></h2>
        </div>
    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<?php  $this->load->view(BLOG.'footer'); ?>
