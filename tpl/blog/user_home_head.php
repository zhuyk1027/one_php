            <h2 class="title">
                <a href="/user/user_blog">博文</a> &nbsp;&nbsp; | &nbsp;&nbsp;
                <a href="/gallery">相片</a> &nbsp;&nbsp; |  &nbsp;&nbsp;
                <a href="/about/user">关于我</a>

                <?php if($this->session->userdata('id')==1){ ?>
                    &nbsp;&nbsp; |  &nbsp;&nbsp;
                    <a href="/friendship">友情链接</a>
                    &nbsp;&nbsp; |  &nbsp;&nbsp;
                    <a href="/about/station">关于本站</a>
                    &nbsp;&nbsp; |  &nbsp;&nbsp;
                    <a href="/Count/friend_count">跳转统计</a>
                <?php } ?>
            </h2>

            <?php if($head['this_page']=='blog'){ ?>
            <div class="widget widget_text">
                <div class="textwidget">
                    <div style="padding:1em;">
                        <ul >
                            <li style="float:left;padding: 5px;"> <a href="/user/user_blog">博文列表</a></li>
                            <li style="float:left;padding: 5px;"> <a href="/user/write_blog">博文发布</a></li>
                            <li style="float:left;padding: 5px;"> <a href="/user/blog_type">博文分类</a></li>
                            <li style="float:left;padding: 5px;"> <a href="/user/blog_tag">博文标签</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php }else if($head['this_page']=='friendship'){ ?>
                <div class="widget widget_text">
                    <div class="textwidget">
                        <div style="padding:1em;">
                            <ul >
                                <li style="float:left;padding: 5px;"> <a href="/friendship">友情链接</a></li>
                                <li style="float:left;padding: 5px;"> <a href="/friendship/write_friendship">添加链接</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php }else if($head['this_page']=='gallery'){ ?>
                <div class="widget widget_text">
                    <div class="textwidget">
                        <div style="padding:1em;">
                            <ul >
                                <li style="float:left;padding: 5px;"> <a href="/gallery">相册</a></li>
                                <li style="float:left;padding: 5px;"> <a href="/gallery/images">图片</a></li>
                                <li style="float:left;padding: 5px;"> <a href="/gallery/upload">上传图片</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php }else if($head['this_page']=='count'){ ?>
                <div class="widget widget_text">
                    <div class="textwidget">
                        <div style="padding:1em;">
                            <ul >
                                <li style="float:left;padding: 5px;"> <a href="/count/friend_count">友链统计</a></li>
                                <li style="float:left;padding: 5px;"> <a href="/count/ad_count">广告统计</a></li>
                                <li style="float:left;padding: 5px;"> <a href="/count/pv_count">PV统计</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
