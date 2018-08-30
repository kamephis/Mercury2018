
<!-- fehlerartikel -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span class="panel-title"><?php echo $this->tFehler; ?></span>

                <span class="hidden-print">
                    <button type="button" class="btn btn-xs btn-primary pull-right" id="btnToggleFehlerArea">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>

                <span class="pull-right hidden-print" style="margin-right:5px;">
                    <button type="button" class="btn btn-xs btn-warning btn-block" id="btnGetPixiStock">
                        <span class="glyphicon glyphicon-refresh"></span> Pixi*
                    </button>
                </span>

                <span class="pull-right hidden-print" style="margin-right:5px;">
                    <button type="button" class="btn btn-xs btn-default btnPrint hidden-print" style="margin-left:1em;">
                        <span class="glyphicon glyphicon-print"></span>
                    </button>
                </span>


            </div>
            <div class="panel-body" id="pnlFehler">
                <?php
                if (sizeof($this->backend->getFehlerhafteArtikel()) > 0) {
                    ?>

                    <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
                        <thead>
                        <tr>
                            <th>
                                <center>#</center>
                            </th>
                            <th>
                                Lagerplatz
                            </th>
                            <th>
                                Artikelbez.
                            </th>
                            <th>
                                Artikelnr.
                            </th>
                            <th>
                                EAN
                            </th>
                            <th>
                                Fehlertext
                            </th>
                            <th>
                                Max. verf.<br>Menge
                            </th>

                            <th>
                                Best.<br class="visible-print">Menge<br>Gesamt
                            </th>
                            <th class="alert-warning">
                                <strong>
                                    Pixi-<br>Bestand
                                </strong>
                            </th>
                            <th class="alert-warning">
                                <strong>
                                Pixi-<br>Pickliste
                                </strong>
                            </th>

                            <th>
                                <span class="hidden-print">Bearbeiter(in)</span>
                                <span class="visible-print">Bearb.</span>
                            </th>

                            <th>
                                <center>
                                <span class="hidden-print">
                                    Geprüft
                                </span></center>
                                <span class="visible-print">Gep.</span>
                            </th>

                            <th>
                                Kommentar

                            </th>
                            <th class="hidden-print">
                                <center>Aktion</center>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                    <?php
                    $cntRow = 0;
                    $aFehlerArtikel = $this->backend->getFehlerhafteArtikel();
                    foreach ($aFehlerArtikel as $fArtikel) {
                        $cntRow++;
                        ?>
                        <tr id="rowError_<?php echo $fArtikel['ID']; ?>">
                            <td>
                                <center><?php echo $cntRow; ?></center>
                            </td>
                            <td>
                                <?php echo $fArtikel['BinName']; ?>
                            </td>
                            <td>
                                <?php echo $fArtikel['ItemName']; ?>
                            </td>
                            <td>
                                <?php echo $fArtikel['ItemNrSuppl']; ?>
                            </td>
                            <td class="hidden-print">
                                <?php echo $fArtikel['EanUpc']; ?>
                            </td>
                            <td class="visible-print-block">
                                    <img src="libs/Barcode_org.php?text=<?php echo $fArtikel['EanUpc']; ?>&size=60&orientation=horizontal&codetype=code128"
                                         style="width:7cm!important;">
                                <?php echo $fArtikel['EanUpc']; ?>
                            </td>


                            <td>
                                <?php echo $fArtikel['ItemFehler']; ?>
                            </td>

                            <td>
                                <center>
                                <?php echo $fArtikel['ItemFehlbestand']; ?>
                                </center>
                            </td>

                            <td>
                                <center>
                                    <?php
                                    echo $fArtikel['BestMenge'];
                                    ?>
                                </center>
                            </td>
                            <td class="alert-warning">
                                <?php if (isset($_REQUEST['getPixiBestand'])) { ?>
                                    <span class="text-pixi">
                                <center>
                                    <?php
                                    // aktivieren falls onDemand Abfrage gewünscht
                                    //if ($_REQUEST['getPixiBestand']) {
                                    if ($this->Pixi->getItemStock($fArtikel['EanUpc'])) {
                                        $pBestand = $this->Pixi->getItemStock($fArtikel['EanUpc']);
                                        echo $pBestand['PhysicalStock'];
                                    } else {
                                        echo 'k. A.';
                                    }
                                    //} else {
                                    //    echo '---';
                                    //}
                                    ?>

                                    <?php
                                    ?></center></span>
                                <?php } ?>
                            </td>
                            <td class="alert-warning">
                                <?php if (isset($_REQUEST['getPixiBestand'])) { ?>
                                    <span class="text-pixi">
                                <?php
                                echo $fArtikel['PLIheaderRef'];
                                ?>
                                    </span>
                                    <?php
                                }
                                ?>
                            </td>


                            <td>
                                <?php
                                echo $fArtikel['ItemFehlerUser'];
                                ?>
                            </td>

                            <td>
                                <center>
                                    <form method="post" id="frmChk" name="frmChk">
                                        <?php if ($fArtikel['geprueft'] == '1') {
                                    $bChecked = 'checked';
                                        } else {
                                            $bChecked = '';
                                        }
                                        ?>
                                        <input type="hidden" name="itemCheckUpdate" value="1">
                                        <input type="hidden" name="itemID" value="<?php echo $fArtikel['ID']; ?>">
                                        <input type="hidden" value="<?php echo $_SESSION['name']; ?>" name="setUser">

                                        <input type="checkbox" class="chkFehler"
                                               name="chkFehler_<?php echo $fArtikel['ID']; ?>"
                                               id="chkFehler_<?php echo $fArtikel['ID']; ?>" <?php echo $bChecked; ?>
                                               value="<?php echo $fArtikel['EanUpc']; ?>">
                                    </form>
                                </center>
                            </td>
                            <td>
                                    <textarea name="txtInfoText_<?php echo $fArtikel['ID']; ?>"
                                              id="txtInfoText_<?php echo $fArtikel['ID']; ?>"><?php if (isset($fArtikel['EscComment'])) {
                                            echo $fArtikel['EscComment'];
                                        } ?></textarea>
                                <button
                                        type="button"
                                        class="btn-xs btn-default btnSaveFehlerKommentar hidden-print"
                                        style="vertical-align: top;"
                                        title="Kommentar speichern"
                                        data-id= <?php echo $fArtikel['ID']; ?>
                                >
                                    <i class="glyphicon glyphicon-check"></i>
                                </button>
                            </td>
                            <td class="hidden-print">
                                <form method="post" id="frmDelete">
                                    <input type="hidden" name="itemFehlerUpdate" value="1">
                                    <input type="hidden" name="itemID" value="<?php echo $fArtikel['ID']; ?>">

                                    <button type="button"
                                            class="btn btn-warning btn-xs hidden-print btn-block btnSaveFehlerKommentar"
                                            data-toggle="modal"
                                            data-id="<?php echo $fArtikel['ID']; ?>"
                                            data-target="#modEscalateItem_<?php echo $fArtikel['ID']; ?>">
                                        <i class="glyphicon glyphicon-user"></i> Service
                                    </button>

                                    <button type="button"
                                            class="btn btn-danger btn-xs hidden-print btn-block btnDelError"
                                            id="btnError_<?php echo $fArtikel['ID']; ?>"
                                            data-id="<?php echo $fArtikel['ID']; ?>"
                                            data-sUser="<?php echo $_SESSION['vorname'] . " " . $_SESSION['name']; ?>"
                                    >
                                        <i class="glyphicon glyphicon-remove"></i> l&ouml;schen
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal Escalate Item-->
                        <div id="modEscalateItem_<?php echo $fArtikel['ID']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header alert-info">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Artikel an Kundenservice melden</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <img src="<?php echo IMG_ART_PATH . $fArtikel['PicLinkLarge']; ?>"
                                                     class="img-responsive">
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="row">
                                                    <div class="col-lg-2"><b>Artikel:</b></div>
                                                    <div class="col-lg-10"><?php echo utf8_encode($fArtikel['ItemName']); ?></div>
                                                    <div class="clearfix"></div>

                                                    <div class="col-lg-2"><b>Art.Nr:</b></div>
                                                    <div class="col-lg-10"><?php echo $fArtikel['OrderNrExternal']; ?></div>
                                                    <div class="clearfix"></div>

                                                    <div class="col-lg-2"><b>EAN:</b></div>
                                                    <div class="col-lg-10"><?php echo $fArtikel['EanUpc']; ?></div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <br>
                                            <div class="col-lg-12">
                                                <label>Nachricht an den Kundenservice<br>
                                                    <textarea id="txtaServiceInfoText_<?php echo $fArtikel['ID']; ?>"
                                                              class="form-control" cols="60" rows="7"><?php
                                                        if (isset($fArtikel['EscComment'])) {
                                                            echo $fArtikel['EscComment'];
                                                        }
                                                        ?></textarea>

                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-success hidden-print btnEscalate"
                                                id="btnEscalate_<?php echo $fArtikel['ID']; ?>"
                                                data-id="<?php echo $fArtikel['ID']; ?>"
                                                data-toggle="modal"
                                                data-target="#modEscalateItem"
                                        >
                                            <i class="glyphicon glyphicon-user"></i> Artikel Melden
                                        </button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ./ Modal Escalate Item -->
                    <?php } ?>
                        </tbody>
                    </table>

                <?php } else {
                    echo '<div class="alert alert-info">';
                    echo 'Es wurden noch keine Fehlerhaften Artikel gemeldet.';
                    echo '</div>';
                } ?>
            </div>
        </div>
    </div>
</div><!-- ./row-->
<div class="clearfix"></div>

