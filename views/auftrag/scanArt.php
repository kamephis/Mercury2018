<?php
if ($_REQUEST['e'] == 1) {
    $this->showAlert('danger', '', '<b>Diesem Artikel ist kein Auftrag zugewiesen. Überprüfen Sie die gescannte EAN.</b>');
}
?>
<div class="panel col-xs-12 col-md-6 col-md-offset-3" id="loginbox">
    <div class="panel-heading">
        <h3>Artikel EAN scannen</h3>
    </div>

    <div class="panel-body loginPanel">
        Letzter Aufrag: <?php echo Session::get('geschnitteneMeterGesamt') . ' m'; ?>
        <form id="frmScanArt" class="form-horizontal" role="form" method="POST" action="scanArt/chkItem">
            <div class="input-group inputLogin">
                <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                <input id="artEAN" type="text" class="form-control" name="artEAN" placeholder=" Art. EAN..." required
                       autofocus>
                <input type="hidden" name="eanScanned" value="1">
            </div>
            <div style="margin-top:10px" class="form-group">
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-xs-6 col-sm-3">
                            <input type="submit" class="btn btn-success btn-block" value="weiter">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>