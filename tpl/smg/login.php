<?php $this->load->view(SMG.'header'); ?>
<div class="container-fluid-full">
    <div class="row-fluid">

        <div class="row-fluid">
            <div class="login-box">
                <div class="icons">
                    <a href="/home/homepage"><i class="halflings-icon home"></i></a>
                </div>
                <h2>Login to your account</h2>
                <form class="form-horizontal" action="index.html" method="post">
                    <fieldset>

                        <div class="input-prepend" title="Username">
                            <span class="add-on"><i class="halflings-icon user"></i></span>
                            <input class="input-large span10" name="username" id="username" type="text" placeholder=" username"/>
                        </div>
                        <div class="clearfix"></div>

                        <div class="input-prepend" title="Password">
                            <span class="add-on"><i class="halflings-icon lock"></i></span>
                            <input class="input-large span10" name="password" id="password" type="password" placeholder=" password"/>
                        </div>
                        <div class="clearfix"></div>

<!--                        <label class="remember" for="remember"><input type="checkbox" id="remember" />Remember me</label>-->

                        <div class="button-login">
                            <button type="button" class="btn btn-primary login_but">Login</button>
                        </div>
                        <div class="clearfix"></div>
                </form>
                <hr>
<!--                <h3>Forgot Password?</h3>-->
<!--                <p>-->
<!--                    No problem, <a href="#">click here</a> to get a new password.-->
<!--                </p>-->
            </div><!--/span-->
        </div><!--/row-->


    </div><!--/.fluid-container-->

</div><!--/fluid-row-->
</body>
</html>