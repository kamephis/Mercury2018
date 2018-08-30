<?php
// Speichern der Location in die Session
if (isset($_REQUEST['locationBarcode'])) {
    if (!isset($_SESSION['locationID'])) {
        Session::set('locationID', $_REQUEST['locationBarcode']);
    }
}
?>
<div class="well-sm">
    <!-- Anzeige Picklisten -->
    <div class="row">
        <div class="col-xs-12">
            <h1>Aktive Picklisten</h1>
            <?php if (sizeof($this->masterPicklist) > 0) { ?>
                <p class="lead"><b>Hallo <?php echo Session::get('vorname'); ?></b>, folgende Picklisten sind für dich
                    zum Picken freigegeben.</p>
            <?php } else { ?>
                <div class="alert alert-info">
                    <p class="lead"><b>Hallo <?php echo Session::get('vorname'); ?></b>, Dir wurden noch keine neuen
                        Picklisten zugewiesen.<br><br><b>Bitte versuche es später nochmal.</b></p>
                    <br>
                    <a href="picker" class="btn btn-default btn-block">
                        <span class="glyphicon glyphicon-refresh"></span>
                        aktualisieren
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php
    foreach ($this->masterPicklist as $pickList) {
        ?>
        <div class="well" style="padding:10px!important;">
        <div class="row">
            <div class="col-xs-2 col-md-1">
                <h4><b>#</b></h4>
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo $pickList['PLHkey']; ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-10 col-md-4 text-left">
                <h4><b>Picklisten Details</b></h4>
                <div class="row">
                    <div class="col-xs-5 col-md-2 text-left">
                        Ersteller:
                    </div>
                    <div class="col-xs-7">
                        <?php echo $pickList['CreatedBy']; ?>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-xs-5 col-md-2 text-left">
                        Datum:
                    </div>
                    <div class="col-xs-7 text-left">
                        <?php echo $pickList['createDate']; ?>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-xs-5 col-md-2 text-left">
                        Positionen:
                    </div>
                    <div class="col-xs-7 text-left	">
                        <?php echo $this->PickerModel->getPicklistItemCount($pickList['PLHkey']); ?>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-xs-5 col-md-2 text-left">
                        Kommentar:
                    </div>
                    <div class="col-xs-7 text-left	">
                        <?php echo $pickList['PLcomment']; ?>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-xs-5 col-md-2 text-left">
                        Pickwagen:
                    </div>
                    <div class="col-xs-7 text-left">
                        <?php echo Session::get('locationID'); ?>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <form action="<?php echo URL; ?>picklist" method="post" role="form">
                    <input type="hidden" name="picklistNr" value="<?php echo $pickList['PLHkey']; ?>">
                    <input type="hidden" name="referer" value="picker">
                    <button type="submit" class="btn btn-success btn-block btn-lg" name="btnStartPick"
                            id="btnStartPick">
                        <span class="glyphicon glyphicon-time"></span> Picken beginnen
                    </button>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
        </div><?php } ?>
</div>