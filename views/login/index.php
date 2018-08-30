<?php
if ($_REQUEST['msg'] == 'e401') {
    echo "Ihre Zugangsdaten sind nicht korrekt.";
}
?>
<div class="panel col-xs-12 col-md-4 col-md-offset-4" id="loginbox">

    <div class="panel-heading">
        <img src="<?php echo IMG_PATH; ?>logo_600.jpg" style="width:350px" class="img img-responsive">
    </div>

    <div class="panel-body loginPanel">
        <h3>Mercury Login : <?php echo Session::get('appName'); ?></h3>

        <form id="loginform" class="form-horizontal" role="form" method="post" action="<?php echo URL; ?>login/run">

            <div class="input-group inputLogin">
                <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                <input id="login-username" type="password" class="form-control" name="userPasswd" value=""
                       placeholder="Login Barcode scannen..." required autofocus>
            </div>

            <button type="submit" class="btn btn-success btn-block">
                Anmelden
            </button>
        </form>
    </div>
    <?php
    if (isset($this->view->message) && strlen($this->message) > 0) {
        echo $this->view->message;
    }
    ?>
</div>