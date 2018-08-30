<?php

?>

<div class="row">
    <div class="col-sm-12">
        <a class="btn btn-default hidden-print pull-left" href="<?php echo URL . 'kundenservice'; ?>">
            <span class="glyphicon glyphicon-refresh"></span>&nbsp;Anischt aktualisieren
        </a>
        &nbsp;
        <button type="button" class="btn btn-default btnPrint hidden-print">
            <span class="glyphicon glyphicon-print"></span>&nbsp;Drucken
        </button>
        <input type="hidden" id="getPixiBestand" name="getPixiBestand" value="1">
        <button type="submit" class="btn btn-warning  hidden-print pull-right">
            <span class="glyphicon glyphicon-refresh"></span>&nbsp;Pixi* Daten abrufen
        </button>
        <div class="clearfix"></div>
        <br>
    </div>
</div>


<div class="alert alert-danger">
    <b>Bitte überprüfen ob bei Artikeln mit mehreren Lieferanten auch mehrere Lieferanten angezeigt werden.</b>
</div>

<!-- Kundenservice Info -->
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="hidden-print">
                <button type="button" class="btn btn-xs btn-default pull-right" id="btnToggleFilter">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
</span>

                        Filter
                    </div>
                    <div class="panel-body">
                        <form name="frmFilterKusListe" method="POST">
                            <input type="hidden" name="filterList" value="1">
                            <div class="col-lg-3">
                                <label>Von
                                    <input type="date" id="dateVon" name="dateVon" class="form-control"
                                           value="<?php if (isset($_POST['dateVon'])) {
                                               echo $_POST['dateVon'];
                                           } else {
                                               echo date("Y-m-d");
                                           } ?>">
                                </label>
                                <label>Bis
                                    <input type="date" id="dateBis" name="dateBis" class="form-control"
                                           value="<?php if (isset($_POST['dateBis'])) {
                                               echo $_POST['dateBis'];
                                           } else {
                                               echo date("Y-m-d");
                                           } ?>">
                                </label>
                            </div>
                            <div class="col-lg-1">
                                <label>Archiv
                                    <input type="checkbox" name="chkStatus" id="chkStatus" class="form-control"
                                        <?php
                                        if (isset($_POST['chkStatus'])) {
                                            echo 'checked';
                                        }
                                        ?>
                                    >
                                </label>
                            </div>
                            <div class="col-lg-1">
                                <label>&nbsp;
                                    <button type="submit" class="btn btn-primary">
                                        <i class="glyphicon glyphicon-filter"></i> Filter anwenden
                                    </button>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span class="hidden-print">
                    <button type="button" class="btn btn-xs btn-primary pull-right" id="btnToggleFehlerArea">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
                <span class="panel-title"><?php echo $this->title; ?></span>
            </div>
            <div class="panel-body" id="pnlFehler">
                <?php if (sizeof($this->back->getKusInfo()) > 0) { ?>

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

                            <th class="alert-warning">
                                Lieferanteninfo
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
                                Nachricht
                            </th>
                            <th class="hidden-print">
                                Aktion
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        $cntRow = 0;

                        if (isset($_POST['filterList'])) {
                            $sFilter = '';

                            if (isset($_POST['dateBis'])) {
                                $dateBis = $_POST['dateBis'];
                            } else {
                                // heutiges Datum

                                $dateBis = date("Y-m-d");
                            }

                            // Datumsfilter
                            if (isset($_POST['dateVon'])) {
                                $sFilter .= "AND TimestampUpdateStatus BETWEEN '{$_POST['dateVon']}' AND '{$_POST['dateBis']}'";
                            }

                            // Archivierte Anzeigen
                            if (isset($_POST['chkArchiv'])) {
                                $sFilter .= 'AND ItemStatus = 6';
                            }

                            $aFehlerArtikel = $this->back->getKusInfoArchiv($sFilter);
                        } else {
                            $aFehlerArtikel = $this->back->getKusInfo();
                        }

                        foreach ($aFehlerArtikel as $fArtikel) {
                            // Auslesen des Lieferanten des aktuellen Artikels
                            $aSuppliers = $this->Pixi->getItemSuppliers($fArtikel['ItemNrSuppl']);
                            $aSupplier = $this->Pixi->getSuppliers($aSuppliers['SupplNr']);

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
                                <td class="alert-warning">
                                    <?php
                                    echo '<table class="table table-condensed table-bordered table-striped">';
                                    echo '<tr>';
                                    echo '<td><b>Lieferant</b></td>';
                                    echo '<td><b>Art.Nr</b></td>';
                                    echo '<td><b>EK</b></td>';
                                    echo '</tr>';

                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<span title="' . $aSupplier['SupplNr'] . '">' . $aSupplier['SupplName'] . '</span>';
                                    echo '</td>';

                                    echo '<td>';
                                    echo $aSuppliers['ItemNrSuppl'];
                                    echo '</td>';

                                    echo '<td>';
                                    echo $aSuppliers['SupplPrice'] . '&nbsp;&euro;';
                                    echo '</td>';
                                    echo '</tr>';


                                    echo '</table>';
                                    ?>
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
                                <span class="text-pixi">
                                <center>
                                    <?php
                                    // aktivieren falls onDemand Abfrage gewünscht
                                    if ($_REQUEST['getPixiBestand']) {
                                        if ($this->Pixi->getItemStock($fArtikel['EanUpc'])) {
                                            $pBestand = $this->Pixi->getItemStock($fArtikel['EanUpc']);
                                            echo $pBestand['PhysicalStock'];
                                        } else {
                                            echo 'k. A.';
                                        }
                                    } else {
                                        echo '---';
                                    }
                                    ?>

                                    <?php
                                    ?></center></span>
                                </td>
                                <td class="alert-warning">
                                <span class="text-pixi">
                                <?php
                                echo $fArtikel['PLIheaderRef'];
                                ?>
                                    </span>
                                </td>


                                <td>
                                    <?php
                                    echo $fArtikel['ItemFehlerUser'];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $fArtikel['EscComment'];
                                    ?>
                                </td>
                                <!--
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
                                            <input type="hidden" value="<?php echo $_SESSION['name']; ?>"
                                                   name="setUser">

                                            <input type="checkbox" class="chkFehler"
                                                   name="chkFehler_<?php echo $fArtikel['ID']; ?>"
                                                   id="chkFehler_<?php echo $fArtikel['ID']; ?>" <?php echo $bChecked; ?>
                                                   value="<?php echo $fArtikel['EanUpc']; ?>">
                                        </form>
                                    </center>
                                </td>-->
                                <td class="hidden-print">
                                    <form method="post" id="frmDelete">
                                        <input type="hidden" name="itemFehlerUpdate" value="1">
                                        <input type="hidden" name="itemID" value="<?php echo $fArtikel['ID']; ?>">

                                        <button type="button"
                                                class="btn btn-danger btn-xs hidden-print btn-block btnDelError"
                                                id="btnError_<?php echo $fArtikel['ID']; ?>"
                                                data-id="<?php echo $fArtikel['ID']; ?>">
                                            <span class="glyphicon glyphicon-remove"></span> Kunde kontaktiert
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                <?php } else {
                    echo '<div class="alert alert-info">';
                    echo 'Es wurden derzeit noch keine Problemartikel gemeldet.';
                    echo '</div>';
                } ?>
            </div>
        </div>
    </div>
</div><!-- ./row-->
<div class="clearfix"></div>
<script>
    // Entfernen eines Artikelfehlers
    $(".btnDelError").on("click", function () {
        var artID = $(this).data("id");
        var itemStatus = '6';

        $.ajax({
            type: 'POST',
            url: "index.php?url=setItemStatusFehler",
            data: {"articleID": artID, "ItemStatus": itemStatus},
            success: function (data) {
                $("#rowError_" + artID).remove();
            },
            complete: function () {

            }
        })
    });
</script>
