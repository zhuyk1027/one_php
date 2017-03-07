<?php  $this->load->view(BLOG.'header'); ?>

<div class="content-wrap">
    <div class="content">
        <div class="breadcrumbs">你的位置：
            <a href="<?=WEB_URL?>"><?=WEB_NAME?></a> <small>&gt;</small>
            <a href="<?=WEB_URL?>" title="<?=$title?>"><?=$title?></a>
        </div>
        <header class="article-header">
            <h1 class="article-title"><a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>"><?=$title?></a></h1>
        </header>


        <article class="article-content">
            <p><?=@$station->cont?></p>
            <p>&nbsp;</p>
        </article>

    </div>
</div>
<?php  $this->load->view(BLOG.'right'); ?>
<?php  $this->load->view(BLOG.'footer'); ?>