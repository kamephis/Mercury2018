<?php
// Zwischenspeichern der Picklistennummer
$plist = $_REQUEST['picklistNr'];
$_SESSION['plist'] = $plist;

// Auslesen des Picklistentyps
$plistType = $this->Picklist->getPicklistType($_SESSION['plist']);

// Stoff gepickt - via EAN (itemPicked = Ean der Position, und BinKey (Lagerplatz))
if ($_REQUEST['itemPicked']) {
    if ($plistType == "gruppiert") {
        try {
            $this->Picklist->setItemStatus($_REQUEST['itemPicked'], $_SESSION['locationID'], null, 'gruppiert');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        try {
            $this->Picklist->setItemStatus(null, $_SESSION['locationID'], $_REQUEST['itemID'], 'ungruppiert');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    // Stasi Log - Ende
    $this->Picklist->stasi(Session::get('plist'), Session::get('UID'), '', 'end', $_REQUEST['itemPicked'], $_REQUEST['binName'], 1);

    // Aktualisieren -> nächste Position - refresh
    header('location: ' . URL . 'picklist?picklistNr=' . $_SESSION['plist'], true, 301);
}

// Fehler erfasst
if ($_REQUEST['setFehler']) {

    $aFehler = $_REQUEST['fehler'];
    $intFehlbestand = $_REQUEST['ItemFehlbestand'];

    Session::set('fehler', $aFehler);
    Session::set('sItemFehlbestand', $intFehlbestand);

    $fehlerText = '';

    // Auslesen des jeweiligen Fehlers aus dem Fehler Array
    if (sizeof($aFehler) == 1) {
        $fehlerText = $aFehler[0];
    }
    if (sizeof($aFehler) == 2) {
        $fehlerText = $aFehler[0] . ', ' . $aFehler[1];
    }
    if (sizeof($aFehler) == 3) {
        $fehlerText = $aFehler[0] . ', ' . $aFehler[1] . ', ' . $aFehler[2];
    }

    // Erweiterung LX Picklisten
    if ($plistType == "gruppiert") {
        try {
            $this->Picklist->setItemFehler($_REQUEST['EanUpc'], utf8_encode($fehlerText), $intFehlbestand, null, null, null, null);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        try {
            $this->Picklist->setItemFehler(null, utf8_encode($fehlerText), $intFehlbestand, null, null, $_REQUEST['itemID'], "ungruppiert");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    // Position als in der Zeiterfassung fehlerhaft markieren
    $this->Picklist->stasi($plist, Session::get('UID'), $_REQUEST['qty'], 'end', $_REQUEST['itemPicked'], $_REQUEST['binName'], 1);
}

// Picklisten Array - wenn das Picklisten-Array leer ist, werden die Positionen aus der DB geladen.
// andernfalls wird das lokale Array verwendet.
if (sizeof($this->Picklist->getAPicklist()) == 0) {
    $this->Picklist->setAPicklist($this->Picklist->getPicklistItems($_SESSION['plist'], $plistType));
} else {
    echo "Keine Pickliste gesetzt";
}

// Anzahl der Picklistenpositionen
$anzPositionen = sizeof($this->Picklist->getAPicklist());

/**
 * Übergabe der Picklistennummer an die getPickListItems zum
 * Abrufen der zugewiesenen Artikel
 * Falls keine Position übergeben wurde auf den Anfang zurückspringen
 */

// Falls eine andere Pickliste aufgerufen wird, dann wird die selbe POS in dieser Liste verwendet.
if ($_REQUEST['referer']) {
    unset($_SESSION['pos']);
    $_SESSION['pos'] = 0;
}
if (isset($_REQUEST['pos'])) {
    $_SESSION['pos'] = $_REQUEST['pos'];

    if ((int)$_SESSION['pos'] > $anzPositionen || (int)$_SESSION['pos'] < 0 || (int)$_SESSION['pos'] >= $anzPositionen) {
        $_SESSION['pos'] = 0;
    }
}

if (sizeof($this->Picklist->getAPicklist()) > 0) {
    $item = $this->Picklist->getAPicklist();

    // Stasi Log - Start
    $this->Picklist->stasi($plist, Session::get('UID'), $item[$_SESSION['pos']]['Qty'], 'start', $item[$_SESSION['pos']]['EanUpc'], $item[$_SESSION['pos']]['BinName']);

    // Zeiterfassung

        // Lagerbestände
        if ($_REQUEST['updPixiBestand'] == 1) {
            $lagerbestand = $this->Pixi->getItemStock($item[$_SESSION['pos']]['EanUpc']);
            $_SESSION['itemLagerbestand'] = $lagerbestand;
        }

    // Image
    $pickimage = IMG_ART_PATH . $item[$_SESSION['pos']]['PicLinkLarge'];
    ?>

        <div class="well-sm">

            <div class="row">
                <div class="col-xs-9">

                    <div class="row">
                        <div class="col-xs-12 col-md-12 small">
                            <?php
                            echo "Lagerplatz";
                            ?>
                        </div>
                        <div class="col-sm-12">

                            <h2 class="pick binColor"
                                style="background: <?php echo $this->binColors['COLOR_' . substr($item[$_SESSION['pos']]['BinName'], -2)]; ?>;">
                                <?php echo $item[$_SESSION['pos']]['BinName']; ?>
                            </h2>

                        </div>
                        <div class="clearfix"></div>

                        <div class="col-xs-12 col-md-12 small">
                            <b>Artikel</b>
                        </div>
                        <div class="col-sm-12">
                            <h3 class="pick"><?php echo utf8_encode($item[$_SESSION['pos']]['ItemName']); ?></h3>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-xs-12 col-md-12 small">
                            <b>EAN/GTIN</b>
                        </div>
                        <div class="col-sm-12 hidden-print">
                            <h2 class="pick hidden-xs"><?php echo $item[$_SESSION['pos']]['EanUpc']; ?></h2>
                            <h3 class="pick visible-xs"><?php echo $item[$_SESSION['pos']]['EanUpc']; ?></h3>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-sm-2 visible-print">
                            <img src="libs/imgEAN.php?code=<?php echo $item[$_SESSION['pos']]['EanUpc']; ?>"
                                 style="width:4cm!important;">
                            <br>
                            <br>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-xs-12 col-md-12 small">
                            <b>SHOP</b>
                        </div>
                        <div class="col-xs-12 col-md-12">
                            <?php echo substr($item[$_SESSION['pos']]['OrderNrExternal'], 0, 3); ?>
                        </div>
                        <div class="clearfix"></div>
                        <small>&nbsp;</small>
                        <div class="col-xs-12 col-md-12 small">
                            <div class="row">
                                <div class="col-xs-6 text-small hidden-print"><b>Menge</b></div>
                                <div class="col-xs-6 text-small hidden-print"><b>Lagerbestand</b></div>
                                <?php $aPickCnt = $this->Picklist->getItemPickAmount($item[$_SESSION['pos']]['EanUpc'], $_SESSION['plist']); ?>
                                <div class="col-xs-6 hidden-print">
                                    <h2 class="pick">
                                        <?php
                                        if ($plistType == "ungruppiert") {
                                            echo $item[$_SESSION['pos']]['Qty'];
                                        } else {
                                            echo $aPickCnt[0]['pSum'];

                                            if ($aPickCnt[0]['pSum'] > $item[$_SESSION['pos']]['Qty']) {
                                                echo ' <small>(';
                                                $outputString = '';
                                                foreach ($aPickCnt as $itemCnt) {
                                                    $outputString .= $itemCnt['Qty'] . ',';
                                                }
                                                echo rtrim($outputString, ',');
                                                echo ')</small>';
                                            }
                                        }
                                        ?>
                                    </h2>
                                </div>
                                <div class="col-xs-6">
                                    <?php
                                    if ($_REQUEST['updPixiBestand']) {
                                        $lagerbestand = $_SESSION['itemLagerbestand'];

                                        echo '<h2 class="pick">';
                                        echo $lagerbestand['PhysicalStock'];
                                        echo '</h2>';
                                    } else { ?>
                                        <form method="post" action="<?php echo URL; ?>picklist">
                                            <input type="hidden" name="updPixiBestand" value="1">
                                            <input type="hidden" name="pos" value="<?php echo $_SESSION['pos']; ?>">
                                            <input type="hidden" name="plist" value="<?php echo $_SESSION['plist']; ?>">
                                            <input type="hidden" name="picklistNr"
                                                   value="<?php echo $_REQUEST['picklistNr']; ?>">
                                            <button type="submit" class="btn btn-warning btn-xs hidden-print">prüfen
                                            </button>
                                        </form>
                                    <?php } ?>
                                </div>

                                <div class="col-xs-12 visible-print">
                                    <h3>Bestellungen</h3>
                                    <div class="row">
                                        <?php
                                        $aOrders = $this->Picklist->getOrders($item[$_SESSION['pos']]['EanUpc']);
                                        foreach ($aOrders as $order) {
                                            echo '<div class="col-xs-4">';
                                            echo "<h4>Bestellnr: " . $order['OrderNrExternal'] . "</h4>";
                                            echo '</div>';

                                            echo '<div class="col-xs-4">';
                                            echo '<h4>Menge: ' . $order['Qty'] . '</h4>';
                                            echo '</div>';

                                            echo '<div class="col-xs-4">';
                                            echo '<h4>Pixi PL: ' . $order['PLIheaderRef'] . '</h4>';

                                            echo '<img src="libs/Barcode_org.php?text=PIC' . $order['PLIheaderRef'] . '&size=80&orientation=horizontal&codetype=code128">';

                                            echo '</div>';

                                            echo '<div class="clearfix"></div>';
                                            echo '<br><br><br>';
                                        }
                                        ?>
                                        <div class="clearfix"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <img src="<?php echo $pickimage; ?>"
                                 width="100%" class="img img-responsive img-thumbnail hidden-print">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-3">
                <div class="fixNav">
                    <div class="row">
                        <div class="navbar navbar-right">
                            <div class="col-xs-12 fixB1">
                                <button type="submit" class="btn btn-danger btn-block btn-lg-touch pull-right"
                                        data-toggle="modal" data-target="#modFehler">
                                    <?php
                                    if (sizeof($this->Picklist->getItemFehler($item[$_SESSION['pos']]['ID'])) > 0) {
                                        echo '<h4><span class="glyphicon glyphicon-info-sign"></span></h4>';
                                    } else {
                                        echo '<span class="glyphicon glyphicon-remove text-glyphicon-lg"></span>';
                                    }
                                    ?>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                            <small>&nbsp;</small>

                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-success btn-block btn-lg-touch pull-right"

                                        data-toggle="modal" data-target="#modPicked">
                                    <span class="glyphicon glyphicon-ok text-glyphicon-lg"></span>
                                </button>
                            </div>
                            <div class="clearfix"></div>

                            <small>&nbsp;</small>
                            <div class="col-xs-12">
                                <form method="post" action="<?php echo URL; ?>picklist">
                                    <input type="hidden" name="nav" value="n">
                                    <input type="hidden" name="pos" value="<?php echo $_SESSION['pos'] + 1; ?>">
                                    <input type="hidden" name="picklistNr"
                                           value="<?php echo $_SESSION['plist']; ?>">
                                    <button type="submit" class="btn btn-default btn-block btn-lg-touch pull-right">
                                        <span class="glyphicon glyphicon-arrow-right text-glyphicon-lg"></span>
                                    </button>
                                </form>
                            </div>
                            <div class="clearfix"></div>

                            <small>&nbsp;</small>
                            <div class="col-xs-12">
                                <form method="post" action="<?php echo URL; ?>picklist">
                                    <input type="hidden" name="nav" value="p">
                                    <input type="hidden" name="pos" value="<?php echo $_SESSION['pos'] - 1; ?>">
                                    <input type="hidden" name="picklistNr"
                                           value="<?php echo $_SESSION['plist']; ?>">

                                    <button type="submit" class="btn btn-default btn-block btn-lg-touch pull-right">
                                        <?php
                                        if ($_SESSION['pos'] == 0) {
                                            ?>
                                            <span class="glyphicon glyphicon-step-backward text-glyphicon-lg"></span>
                                        <?php } else { ?>
                                            <span class="glyphicon glyphicon-arrow-left text-glyphicon-lg"></span>
                                        <?php } ?>
                                    </button>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                            <small>&nbsp;</small>
                            <div class="col-xs-12">
                                <button type="button" class="btn btn-info btn-block btn-lg-touch pull-right"
                                        id="btnPrint">
                                    <h4><span class="glyphicon glyphicon-print"></span></h4>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                            <small>&nbsp;</small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./ row -->

            <div id="modFehler" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <form method="post" id="frmFehler" action="<?php echo URL; ?>picklist" class="form-horizontal">
                        <input type="hidden" name="setFehler" value="1">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Fehler melden</h4>
                            </div>
                            <div class="modal-body">
                                <p>Bitte wählen Sie die passende Option:</p>
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="col-xs-8">
                                            <label>Größte verf. Menge
                                                <input type="tel" class="form-control" name="ItemFehlbestand"
                                                       id="ItemFehlbestand"
                                                       value="<?php echo $item[$_SESSION['pos']]['ItemFehlbestand']; ?>"
                                                >
                                            </label>
                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="col-xs-12">
                                            <label>Kommentar

                                                <select multiple name="fehler[]" class="form-control">
                                                    <option value="Max. Menge" id="optFehlbestand"
                                                        <?php
                                                        if (preg_match('/Max. Menge/', $item[$_SESSION['pos']]['ItemFehler'])) {
                                                            echo "selected";
                                                        }
                                                        ?>
                                                    >Größte verf. Menge
                                                    </option>
                                                    <option value="Farbabweichung" id="optFarbabweichung"
                                                        <?php

                                                        if (preg_match('/Farbabweichung/', $item[$_SESSION['pos']]['ItemFehler'])) {
                                                            echo "selected";
                                                        }
                                                        ?>
                                                    >Farbabweichung
                                                    </option>
                                                    <option value="Stoff beschädigt" id="optStoffBeschaedigt"
                                                        <?php
                                                        if (preg_match('/Stoff beschädigt/', $item[$_SESSION['pos']]['ItemFehler'])) {
                                                            echo "selected";
                                                        }
                                                        ?>
                                                    >Stoff beschädigt
                                                    </option>
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-block btn-lg" data-dismiss="modal">
                                    Schließen
                                </button>
                                <small>&nbsp;</small>
                                <input type="hidden" name="itemID" value="<?php echo $item[$_SESSION['pos']]['ID']; ?>">
                                <input type="hidden" name="EanUpc"
                                       value="<?php echo $item[$_SESSION['pos']]['EanUpc']; ?>">
                                <input type="hidden" name="picklistNr" value="<?php echo $_SESSION['plist']; ?>">
                                <input type="hidden" name="BinKey"
                                       value="<?php echo $item[$_SESSION['pos']]['BinKey']; ?>">
                                <button type="submit" class="btn btn-success btn-block btn-lg" id="btnFehler">
                                    Bestätigen
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modPicked" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Pick bestätigen</h4>
                        </div>
                        <div class="modal-body">
                            <h1 class="text-center">
                                <b>
                                    <?php
                                    if ($plistType == "ungruppiert") {
                                        echo $item[$_SESSION['pos']]['Qty'];
                                    } else {
                                        echo $aPickCnt[0]['pSum'];
                                    }
                                    ?> ME
                                </b></h1>
                            <h2 class="text-center"><b>gepickt?</b></h2>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-block btn-lg" data-dismiss="modal">NEIN
                            </button>
                            <small>&nbsp;</small>
                            <form method="post" action="<?php echo URL; ?>picklist">
                                <input type="hidden" name="itemPicked"
                                       value="<?php echo $item[$_SESSION['pos']]['EanUpc']; ?>">
                                <input type="hidden" name="itemID"
                                       value="<?php echo $item[$_SESSION['pos']]['ID']; ?>">

                                <input type="hidden" name="picklistNr" value="<?php echo $_SESSION['plist']; ?>">
                                <input type="hidden" name="qty" value="<?php echo $item[$_SESSION['pos']]['Qty']; ?>">
                                <input type="hidden" name="binName"
                                       value="<?php echo $item[$_SESSION['pos']]['BinName']; ?>">
                                <input type="submit" class="btn btn-success btn-block btn-lg" value="JA">
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
       <!-- ./ Pickliste -->


        </div>
    <?php /*}*/
} else {
    //$this->Picklist->setPicklistTimer($plist, "end");

    echo '<div class="alert alert-success">';
    echo '<center><span style="font-size:7em; display:block; margin-bottom:0.3em;" class="icon icon-happy"></span></center>';
    echo '<center><h3><b>Juhuu!</b> Geschafft, diese Pickliste enthält nun keine offenen Positionen mehr.</h3></center>';
    echo '<a href="' . URL . 'picker" class="btn btn-default btn-block" style="padding:1em; font-size:1.2em;">
                <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Zurück zur Übersicht
              </a>';
    echo '</div>';
    echo '<div class="clearfix"></div>';
    echo '</div>';

    // Pickliste als abgeschlossen markieren
    // Nur abschließen, wenn tatsächlich keine offenen Positionen mehr vorhanden sind.
    //if ($this->Picklist->getPicklistItemCount($_SESSION['plist']) == 0) {
        $this->Picklist->setPicklistStatus($_SESSION['plist'], '1');
    //}
}
?>
<script>
    $(document).ready(function () {
        $("#ItemFehlbestand").on('input', function () {
            $('#optFehlbestand').attr('selected', true);
        });

        $("#btnPrint").on('click', function () {
            window.print();
        });
    });
</script>