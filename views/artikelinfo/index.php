<?php
$arrItemResult = null;

if ($_REQUEST['searchType']) {
    switch ($_REQUEST['searchType']) {
        case 'ean':
            $arrItemResult = $this->Pixi->getItemInfo($_REQUEST['artikelnr'], '');
            break;
        case 'artnr':
            $arrItemResult = $this->Pixi->getItemInfo('', $_REQUEST['artikelnr']);
            break;
    }
}
?>
<div class="panel panel-primary">
    <div class="panel-heading">Artikelinfo</div>
    <div class="panel-body">
        <form action="artikelinfo" method="post" role="form">
            <input type="hidden" name="showInfo" value="1">
            <div class="form-group">
                <input type="search" name="artikelnr" class="form-control" placeholder="Artikelnummer / EAN">
                <br>
                <label><input type="radio" name="searchType" value="ean"> EAN</label>
                <label><input type="radio" name="searchType" value="artnr">Art. Nr.</label><br><br>
                <button type="submit" class="btn btn-primary">Suchen</button>
            </div>
        </form>

        <?php if ($_REQUEST['artikelnr']){
        if ($arrItemResult){
        ?>
        <h2>Artikelinfo</h2>
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $arrItemResult['ItemName']; ?></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12"><b>Lagerplatz</b></div>
                    <div class="col-xs-12"><?php echo $arrItemResult['ItemInfo']['BinName']; ?></div>
                    <div class="clearfix"></div>

                    <div class="col-xs-12"><b>Pixi Bestand</b></div>
                    <div class="col-xs-12"><?php echo $arrItemResult['ItemInfo']['Quantity']; ?></div>
                    <div class="clearfix"></div>

                    <div class="col-xs-12"><b>Kategorie</b></div>
                    <div class="col-xs-12"><?php echo $arrItemResult['Category']; ?></div>
                    <div class="clearfix"></div>

                    <div class="col-xs-12"><b>Art.Nr.</b></div>
                    <div class="col-xs-12"><?php echo $arrItemResult['ItemNrInt']; ?></div>
                    <div class="clearfix"></div>

                    <div class="hidden-print">
                        <div class="col-xs-12"><b>EAN</b></div>
                        <div class="col-xs-12"><?php echo $arrItemResult['EANUPC']; ?></div>
                    </div>

                    <div class="visible-print">
                        <div class="col-xs-12">
                            <img
                                    src="libs/Barcode_org.php?text=<?php echo $arrItemResult['EANUPC']; ?>&size=80&orientation=horizontal&codetype=code128"><br>
                            <?php echo $arrItemResult['EANUPC']; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-xs-12">
                        <img src="<?php echo $arrItemResult['PicLinkLarge']; ?>"
                             class="img img-responsive img-thumbnail">
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <?php }
    } ?>
</div>



