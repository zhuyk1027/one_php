<?php  $this->load->view(BLOG.'header'); ?>
    <div class="content-wrap">
        <div class="content">
            <div class="breadcrumbs">登录</div>
            <article class="article-content" STYLE="text-align:center;max-height:100%;min-height:780px;padding-top:20%;">
                <form class="form-horizontal" action="index.html" method="post">
                   <P>
                       <input class="input-large span10" name="username" id="username" type="text" placeholder=" username"/>
                   </P>
                   <P>
                       <input class="input-large span10" name="password" id="password" type="password" placeholder=" password"/>
                   </P>
                    <P><button type="button" class="login_but">Login</button></P>
                    <P id="mess"></P>
                </form>
            </article>
        </div>
    </div>
<?php  $this->load->view(BLOG.'right'); ?>
<?php  $this->load->view(BLOG.'footer'); ?>