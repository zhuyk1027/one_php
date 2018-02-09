</section>
<footer class="footer">
    <div class="widget widget_text">
        <div class="textwidget">
            <div style="padding:1em;">
                <?php foreach($friendship as $key=>$row){ ?>
                    <a href="javascript:void(0);" class="fiend_click" style="text-shadow:0 0px 0 #333333;" fsid="<?=$row->fs_id?>" title="<?=$row->fs_title?>" target="_blank">
                        <?=$row->fs_title?>
                    </a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php  }?>
            </div>
        </div>
    </div>
    <div class="footer-inner">
        <div class="copyright pull-left">
            Copyright © 2017 <a href="<?=WEB_URL?>"><?=$website_title?></a>　<?=BEIAN?>
        </div>

    </div>
</footer>
<script type="text/javascript">SyntaxHighlighter.all();</script>

<div class="rollto" style="display: none;">
    <button class="btn btn-inverse" data-type="totop" title="回顶部"><i class="icon-eject icon-white"></i></button>
</div>
</body>
<script type="text/javascript" src="<?=PUB_PATH?>js/aiguo.js?uU4"></script>
</html>