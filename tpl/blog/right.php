<aside class="sidebar">

    <?php  $this->load->view(BLOG.'banner'); ?>
    <div class="widget widget_categories"><h3 class="widget_tit">分类目录</h3>		<ul>
            <?php foreach($groups as $key=>$row){ ?>
                <li class="cat-item cat-item-19">
                    <a href="/blog/blog_list?type=<?=$row->blog_group_id?>" title="查看<?=$row->group_name?>下的所有文章"><?=$row->group_name?></a> (<?=$row->num?>)
                </li>
            <?php  }?>
        </ul>
    </div>

    <div class="widget d_tag">
        <h3 class="widget_tit">标签云</h3>
        <div class="d_tags">
            <?php foreach($tags as $key=>$row){ ?>
                <a href="/blog/blog_list?tag=<?=$row->tag_id?>"><?=$row->tag_name?> (<?=$row->num?>)</a>
            <?php  }?>
        </div>
    </div>

    <div class="widget widget_recent_entries">
        <h3 class="widget_tit">最热文章</h3>
        <ul>
            <?php foreach($ranking as $key=>$row){ ?>
                <li>
                    <a href="/blog/detail/<?=$row->blog_id?>"><?=$row->title?></a>
                </li>
            <?php  }?>
        </ul>
    </div>

    <div class="widget widget_recent_entries">
        <h3 class="widget_tit">资助博客</h3>
        <ul>
            <li><a href="javascript:void(0)" >联系方式：<?=EMAIL?></a></li>
            <li>
                <img src="<?=PAY_PHOTO?>" heigt="150" width="150">
            </li>
        </ul>
    </div>
    <div class="widget widget_text">
        <h3 class="widget_tit">友情链接</h3>
        <div class="textwidget">
            <div style="padding:1em;">
                <?php foreach($friendship as $key=>$row){ ?>
                    <p><a href="/dos/jump/<?=$row->fs_id?>" title="<?=$row->fs_title?>" target="_blank"><?=$row->fs_title?></a></p>
                <?php  }?>
            </div>
        </div>
    </div>
</aside>