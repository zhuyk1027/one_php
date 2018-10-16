<?php  $this->load->view(BLOG.'header'); ?>

    <div class="content-wrap">
        <div class="content" style="background-color: white;">

            <?php foreach($album as $key=>$row){ ?>
                <div style="clear: left;padding: 3%;">
                    <a href="/album/images/<?=$row->id?>">
                    <h3><?=$row->title?></h3>

                    <ul>
                        <?php foreach($row->images as $k=>$r){ ?>
                            <li style="float: left;padding-left: 1%;"><img src="<?=$r->pic_path?>" title="<?=$r->title?>" style="max-width: 120px;height: 80px;" ></li>
                        <?php  }?>
                    </ul>
                    </a>
                </div>
            <?php  }?>

            <p style="clear: both;"></p>

            <h2 class="title" ><?php if(isset($paginate)){ echo $paginate; } ?></h2>
        </div>
    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<?php  $this->load->view(BLOG.'footer'); ?>
