            <h2 class="title">
                <a href="/user/user_blog">博文</a> &nbsp;&nbsp; | &nbsp;&nbsp;
                <a href="">相片</a> &nbsp;&nbsp; |  &nbsp;&nbsp;
                <a href="">关于我</a>

                <?php if(@$this->session->userdata('id')){ ?>
                    &nbsp;&nbsp; |  &nbsp;&nbsp;
                    <a href="">友情链接</a>
                <?php } ?>
            </h2>

            <?php if($head['this_page']=='blog'){ ?>
            <div class="widget widget_text">
                <div class="textwidget">
                    <div style="padding:1em;">
                        <ul >
                            <li style="float:left;padding: 5px;"> <a href="/user/user_blog">博文列表</a></li>
                            <li style="float:left;padding: 5px;"> <a href="/user/write_blog">博文发布</a></li>
                            <li style="float:left;padding: 5px;"> <a href="/user/user_blog">博文分类</a></li>
                            <li style="float:left;padding: 5px;"> <a href="/user/user_blog">博文标签</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>
