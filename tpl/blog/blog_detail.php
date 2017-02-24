<?php  $this->load->view(BLOG.'header'); ?>

<div class="content-wrap">
    <div class="content">
        <div class="breadcrumbs">你的位置：
            <a href="<?=WEB_URL?>"><?=WEB_NAME?></a> <small>&gt;</small>
            <a href="<?=WEB_URL?>" title="查看默认中的全部文章">博文详情</a> <small>&gt;</small>
            <span class="muted"><?=$blog->title?></span>
        </div>
        <header class="article-header">
            <h1 class="article-title"><a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>"><?=$blog->title?></a></h1>
            <div class="meta">
                <span class="muted"><a href="/blog/list?type=<?=$blog->group_info->blog_group_id?>">
                        <i class="icon-list-alt icon12"></i> <?=$blog->group_info->group_name?></a>
                </span>
<!--                <span class="muted"><i class="icon-user icon12"></i> <a href="http://www.zftlive.com/archives/author/zengfantian">Ajava攻城师</a></span>-->
                <time class="muted"><i class="ico icon-time icon12"></i> <?=date('Y/m/d H:i',$blog->create_date)?></time>
                <span class="muted"><i class="ico icon-eye-open icon12"></i> <?=$blog->click?>浏览</span>
                <span class="muted"><i class="icon-comment icon12"></i> <?=$blog->comment?>评论</span>
            </div>
        </header>


        <article class="article-content">
            <p><?=$blog->cont?></p>
            <p>&nbsp;</p>
            <p>转载请注明：
                <a href="<?=WEB_URL?>" data-original-title="" title=""><?=WEB_NAME?></a> »
                <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" data-original-title="" title=""><?=$blog->title?></a></p>
        </article>


        <footer class="article-footer">
            <div class="share">
                <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
                    <!-- JiaThis Button BEGIN -->
                    <div class="jiathis_style_32x32">
                        <a class="jiathis_button_qzone"></a>
                        <a class="jiathis_button_tsina"></a>
                        <a class="jiathis_button_tqq"></a>
                        <a class="jiathis_button_renren"></a>
                        <a class="jiathis_button_kaixin001"></a>
                        <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jiathis_separator jtico jtico_jiathis" target="_blank"></a>
                        <a class="jiathis_counter_style"></a>
                    </div>
                    <script type="text/javascript" >
                        var jiathis_config={
                            siteNum:6,
                            sm:"email,qzone,weixin,tsina,ydnote,fav",
                            url:"zhuyk.pub",
                            summary:"朱耀昆的博客，如何做一个优秀的开发工程师。",
                            title:"朱耀昆的博客 ##",
                            shortUrl:true,
                            hideMore:true
                        }
                    </script>
                    <script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js" charset="utf-8"></script>
                    <!-- JiaThis Button END -->
                </div></div>
        </footer>

        <nav class="article-nav">
            <span class="article-nav-prev">上一篇
                <a href="/blog/detail/<?=@$before_blog->blog_id?>" rel="prev"><?=@$before_blog->title?></a>
            </span>
            <span class="article-nav-next"></span>
        </nav>

        <div class="relates">
            <h3>与本文相关的文章</h3>
            <ul>
                <?php foreach($about_blog as $row){ ?>
                    <li><a href="/blog/detail/<?=$row->blog_id?>"><?=$row->title?></a></li>
                <?php } ?>
            </ul>
        </div>



    </div>
</div>
<?php  $this->load->view(BLOG.'right'); ?>
<?php  $this->load->view(BLOG.'footer'); ?>