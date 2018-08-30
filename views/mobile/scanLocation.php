<div class="panel col-xs-12 col-md-6 col-md-offset-3" id="loginbox">
    <div class="panel-heading">
        <h3>An Pickwagen anmelden</h3>
    </div>

    <div class="panel-body loginPanel">
        <p>Alle Artikel die gepickt werden, sind diesem Wagen zugewiesen. Melden Sie sich ab um sich an einem neuen
            Pickwagen anzumelden.</p>
        <div class="alert alert-info">
            Scannen Sie den Barcode an Ihrem Pickwagen.
        </div>
        <form id="loginform" class="form-horizontal" role="form" method="post" action="picker">
            <div class="input-group inputLogin">
                <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                <input id="locationBarcode" type="text" class="form-control" name="locationBarcode"
                       placeholder=" Barcode..." required
                       autofocus>
            </div>
            <div style="margin-top:10px" class="form-group">
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-xs-6 col-sm-3">
                            <button type="submit" class="btn btn-success btn-block">
                                weiter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>