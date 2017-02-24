<?php  $this->load->view(BLOG.'header'); ?>

    <div class="content-wrap">
        <div class="content">

            <h2 class="title">最新发布</h2>


            <?php foreach($blog as $key=>$row){ ?>
                <article class="excerpt excerpt-nothumbnail">
                    <header>
                        <a class="label label-important" href="/blog/blog_list?type=<?=$row->group_id?>"> <?=$row->group_name?> <i class="label-arrow"></i></a>
                        <h2><a href="/blog/detail/<?=$row->blog_id?>" title="<?=$row->title?>"><?=$row->title?></a></h2>
                    </header>
                    <p>
                        <span class="muted"><i class="icon-time icon12"></i><?=date('Y/m/d H:i',$row->create_date)?>&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="icon-eye-open icon12"></i><?=$row->click?>浏览&nbsp;&nbsp;&nbsp;&nbsp;
                        <i class="icon-comment icon12"></i><?=$row->comment?>评论</span>
                    </p>
                    <p class="note"><?=$row->cont?></p>
                </article>
            <?php  }?>
            <h2 class="title"><?php if(isset($paginate)){ echo $paginate; } ?></h2>
        </div>
    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<?php  $this->load->view(BLOG.'footer'); ?>
