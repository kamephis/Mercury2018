<?php

// Beim 1. Aufruf erzeugen - REQUEST wird nur in diesem Schritt verwendet.
// für alles andere wird der Wert aus der Session gelesen.
$eanScanned = Session::get('eanScanned');

$userID     = null; // Benutzer ID
$aNr        = null; // Init
$artCnt     = null; // Gesamtanzahl der bearbeiteten Artikel (m)

// Anzahl Positionen
$listSize = sizeof($this->auftrag->getAuftrag(Session::get('artEAN')));

if ($_GET['eanScanned'] == 1) {
    Session::set('geschnitteneMeterGesamt', 0);

    // Anlegen eines neuen Auftrags in der Datenbank
    $userID = Session::get('UID');

    // Neuer Auftrag
    $this->auftrag->newAuftrag($userID, Session::get('artEAN'));

    // Auslesen der neuen Auftragsnummer
    $aNr = $this->auftrag->getAuftragsnummer();

    // Pixi Bestand abrufen
    $aBestand = $this->Pixi->getItemStock(Session::get('artEAN'));
    $bestand = $aBestand['PhysicalStock'];

    // Aufruf des neuen Auftrags - falls Positionen dazu existieren
    if ($this->auftrag->getAuftrag(Session::get('artEAN')) && $listSize > 0) {
        // Aktualisierung der Auftragsliste
        $auftrag = $this->auftrag->getAuftrag(Session::get('artEAN'));
    } else {
        $this->message = $this->msg_keine_positionen;
    }

    // gemeldete Fehler auslesen
    $sItemFehler = $this->auftrag->getItemFehlerMax(Session::get('artEAN'));

} else {
    // Wenn keine EAN gescannt wurde, wird zur Scan-Form weitergeleitet
    echo "<script>location.replace('scanArt');</script>";
    //header('location: scanArt');
}
$anz = Session::get('geschnitteneMeterGesamt');

// Aktualisieren des ItemStatus auf 2 - fertig bearbeitet
if ($_POST['savePos']) {
    $_SESSION['geschnitteneMeterGesamt'] += $_POST['artMenge'];

    // ItemStatus -> stpPicklistItems
    //$this->auftrag->setAuftragsPositionStatus($_POST['artID'], $aNr[0]['AuftragsNr']);
    $this->auftrag->setAuftragsPositionStatus($_POST['artID'], $aNr);
    echo "<script>location.replace('auftrag');</script>";
}

// Auftrag abschließen - Setzen des Auftragsstatus auf 1
if ($_POST['finish'] || $listSize == 0) {
    try {
        //die("ANR: ".$aNr[0]['AuftragsNr']." MENGE: ".$anz);
        $this->auftrag->finishAuftrag($aNr, $anz);
        //$this->auftrag->finishAuftrag($aNr[0]['AuftragsNr'], $anz);
        echo "<script>location.replace('scanArt');</script>";
    } catch (Exception $e) {
        echo "Fehler: ";
        echo $e->getMessage();
    }
}

// Fehler speichern
if ($_POST['saveFehler']) {

    $articleID = $_POST['artID'];
    $aFehler = $_POST['saveFehler'];
    $sItemVerfMenge = $_POST['verfMenge'];

    $this->auftrag->setItemFehlerAuftrag($articleID, $aFehler, $sItemVerfMenge);
}

// Zerteilen des Item-Titels
$title = $auftrag[0]['ItemName'];

?>
<style>
    .removeItem {
        display: none;
    }

    .txt-lg {
        font-size: 19px;
        font-weight: bold;
    }

    .txt-bin {
        font-size: 14px;
        font-family: Courier;
        font-weight: bold;
    }

    @media print {

        .print-col-2cm-l,
        .print-col-2cm-r {
            font-size: 10pt !important;
            line-break: auto;
        }

        .print-col-6cm {
            font-size: 9pt !important;
            line-break: auto;
        }

        .printHighlight {
            font-family: "Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace;
            font-weight: bold;
        }

        .imgEAN {
            margin-left: -0.7cm;
            -ms-transform: rotate(90deg); /* IE 9 */
            -webkit-transform: rotate(90deg); /* Chrome, Safari, Opera */
            transform: rotate(90deg);
        }

        .imgCode128 {
            margin-right: -2.5cm;
            /*height:auto;*/
            -ms-transform: rotate(-90deg); /* IE 9 */
            -webkit-transform: rotate(-90deg); /* Chrome, Safari, Opera */
            transform: rotate(-90deg);
        }

        .lblText_r {
            font-size: 8pt;
            -ms-transform: rotate(-90deg); /* IE 9 */
            -webkit-transform: rotate(-90deg); /* Chrome, Safari, Opera */
            transform: rotate(-90deg);
        }

        .print-col-2cm-l {
            position: absolute;
            left: 0.5cm;
            top: 0.5cm;
            width: 1.5cm;
            height: 4.5cm;
            text-align: center;
            vertical-align: middle;
        }

        .print-col-6cm {
            position: absolute;
            left: 2cm;
            top: 0.5cm;
            width: 6cm;
            height: 4.5cm;
            text-align: center;
        }

        .print-col-2cm-r {
            position: absolute;
            left: 8cm;
            top: 0.5cm;
            width: 1.5cm;
            height: 4.5cm;
            text-align: center;
            vertical-align: middle;
        }
    }
</style>

<!-- Statusmeldungen -->
<?php if (strlen($this->message) > 0) { ?>
    <div class="clearfix"></div>
    <div class="alert alert-warning">
        <h3><?php echo $this->message; ?></h3>
    </div>
    <div class="clearfix"></div>
<?php } ?>

<!-- Top Bar -->
<div class="row text-mobile-large hidden-print">
    <?php if ($listSize > 0) { ?>
        <div class="col-sm-2"><img
                    src="<?php echo IMG_ART_PATH . $auftrag[0]['PicLinkLarge']; ?>"
                    width="100%"
                    class="img img-responsive img-square img-thumbnail">
        </div>
        <div class="col-sm-4">
            <div class="row">
                <div class="col-sm-12"><b>Artikel</b></div>
                <div class="col-sm-4">Bez.:</div>
                <div class="col-sm-8"><?php echo utf8_encode($auftrag[0]['ItemName']); ?></div>

                <div class="col-sm-4">Art.Nr:</div>
                <div class="col-sm-8"><?php echo $auftrag[0]['ItemNrSuppl']; ?></div>

                <div class="col-sm-4">EAN:</div>
                <div class="col-sm-8"><?php echo $auftrag[0]['EanUpc']; ?></div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="row">
                <div class="col-sm-12"><b>Lager</b></div>
                <div class="col-sm-4">Platz:</div>
                <div class="col-sm-8"><?php echo $auftrag[0]['BinName']; ?></div>

                <div class="col-sm-4">Bestand:</div>
                <div class="col-sm-8"><?php echo $bestand; ?> m</div>

                <div class="col-sm-4">Info:</div>
                <div class="col-sm-8">
                    <?php
                    echo $sItemFehler;
                    ?>
                </div>


            </div>
        </div>

    <?php } else { ?>
        <div class="col-sm-9">&nbsp;</div>
    <?php } ?>

    <div class="col-sm-3">
        <?php if ($listSize != 0) { ?>
            <b>Auftrag #<?php echo $aNr[0]['AuftragsNr']; ?></b>
            <br>Geschnitten:
            <?php
            if (Session::get('geschnitteneMeterGesamt') > 0) {
                echo Session::get('geschnitteneMeterGesamt') . '&nbsp;m';
            } else {
                echo '0&nbsp;m';
            }
            ?>
            <?php
            if (strlen($auftrag[0]['ItemFehler']) > 0) {
                echo '<br>Fehler: ' . utf8_encode($auftrag[0]['ItemFehler']);
            }
            ?>
            <div style="margin-bottom:1em;"></div>
        <?php } ?>
        <!--
        <form method="post" role="form">
            <input type="hidden" name="getPixiInfo" value="1">
            <button type="submit" class="btn btn-lg btn-warning btn-block" style="margin-bottom:5px;">
                <span class="glyphicon glyphicon-search pull-left"></span> <span class="push-right"> pixi Bestelldaten abfragen</span>
            </button>
        </form>-->

        <form action="logout" role="form" method="post">
            <input type="hidden" name="logout" value="1">
            <?php if ($listSize != 0) { ?>
                <button type="submit" class="btn btn-default btn-lg btn-block" style="margin-bottom:5px;">
                    <span class="glyphicon glyphicon-remove pull-left"></span><span class="push-right">Abmelden / Abbrechen</span>
                </button>
            <?php } ?>
        </form>
        <form action="auftrag" role="form" method="post">
            <input type="hidden" name="finish" value="1">
            <!--data-toggle="modal" data-target="#modFinish"-->
            <button type="submit" class="btn btn-lg btn-info btn-block" style="margin-bottom:5px;">
                <span class="glyphicon glyphicon-ok pull-left"></span><span
                        class="push-right">Auftrag Abschließen</span>
            </button>
        </form>
    </div>


</div>

<?php if ($listSize > 0) { ?>
    <!-- ./ Top Bar -->
    <br><br>
    <!-- Auftragspositionen / Header -->
    <div class="row text-mobile-large hidden-print">
        <div class="col-sm-1 text-left"><b>#</b></div>
        <div class="col-sm-7 text-left"><b>Info</b></div>
        <div class="col-sm-1"><b>Anzahl</b></div>
        <div class="col-sm-3 text-left"><b></b></div>
    </div>

    <!-- Auftragspositionen -->

    <?php
    $rows = 0;
    foreach ($auftrag as $item) {
        $rows++;
        // Workaround für Bundle Artikel
        $OrderNrExternal = null;
        $OrderDate = null;
        $showPixiInfo = false;

        // Zwischenspeichern der Bestellnr in der Session. Abfruf nur beim 1. Aufruf
        if ($this->Pixi->getOrderLine($item['PLIorderlineRef'])) {
            $aOrderLine = $this->Pixi->getOrderLine($item['PLIorderlineRef']);
            $OrderNrExternal = $aOrderLine['OrderNrExternal'];
            $OrderDate = date_format(date_create($aOrderLine['OrderDate']), "d.m.Y H:i");
            $showPixiInfo = true;
        } else {
            $OrderNrExternal = 'k. A.';
            $OrderDate = 'k. A.';
        }

        ?>
        <!-- start foreach -->
        <div class="row row-table default-state hidden-print selectable">
            <div class="col-sm-1"><?php echo $rows; ?></div>

            <div class="col-sm-7">
                Pixi-Pickliste: <?php echo $item['PLIheaderRef']; ?>
                <!--| Ablaufdatum: <?php echo $item['expDate']; ?> --><br>

                <?php /* if($showPixiInfo === true){  */ ?>
                Bestell-Nr: <?php echo $OrderNrExternal; ?> | Bestellt am: <?php echo $OrderDate; ?>
                <?php /*} */ ?>
            </div>

            <div class="col-sm-1"><span style="font-size:40px;"><b><?php echo $item['Qty']; ?> m</b></span></div>
            <div class="col-sm-3">
                <button type="submit" class="btn btn-danger btn-lg-square pull-right" style="margin-left:10px;"
                        data-toggle="modal" data-target="#modPrint_<?php echo $rows; ?>"
                        id="btnFehler_<?php echo $rows; ?>">
                    <span class="glyphicon glyphicon-remove text-glyphicon-lg"></span>
                </button>

                <button type="submit" class="btn btn-success btn-lg-square pull-right" data-toggle="modal"
                        data-target="#modPrint_<?php echo $rows; ?>" id="btnOK_<?php echo $rows; ?>">
                    <span class="glyphicon glyphicon-ok text-glyphicon-lg"></span>
                </button>
            </div>
        </div>

        <!-- ./ Auftragspositionen -->

        <!-- Modals -->

        <div id="modPrint_<?php echo $rows; ?>" class="modal fade hidden-print" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="printTitle">Etikettendruck</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row fehlerText">
                            <div class="boxFehlerauswahl">
                                <label><input type="radio" name="fehlergrund" id="radNumStoff" value="Max. Menge"
                                              class="druckFehler form-control">Größte verf. Menge
                                    <input type="number" name="sItemVerfMenge" id="sItemVerfMenge" style="width:70px;"
                                           min="0">
                                </label>&nbsp;&nbsp;

                                <label><input type="radio" name="fehlergrund" value="Farbabweichung"
                                              class="druckFehler form-control">Farbabweichung&nbsp;</label>&nbsp;&nbsp;

                                <label><input type="radio" name="fehlergrund" value="Stoff beschädigt"
                                              class="druckFehler form-control">Stoff beschädigt&nbsp;</label>
                            </div>
                        </div>
                        <div class="screenLabel">
                            <div class="row" id="lblError">
                                <div class="col-sm-3">
                                    <center>
                                        <img
                                                src="libs/Barcode_org.php?text=<?php echo $auftrag[0]['EanUpc']; ?>&size=80&orientation=horizontal&codetype=code128"><br>
                                        <?php echo $auftrag[0]['EanUpc']; ?>
                                    </center>
                                </div>
                                <div class="col-sm-6">
                                    <center>
                                        <?php echo utf8_encode($auftrag[0]['ItemName']); ?><br>
                                        Art.Nr: <?php echo $item['ItemNrSuppl']; ?><br>
                                        Best.Nr: <?php echo $aOrderLine['OrderNrExternal']; ?><br>
                                        <span class="fehlerText printHighlight"><code><b><?php echo $auftrag[0]['BinName']; ?></b></code><br></span>
                                        <span class="fehlerText">Bestand: <?php echo $bestand; ?> m<br></span>
                                        <span class="fehlerText">Druck: <?php echo date('d.m.y'); ?><br></span>
                                        <b>
                                            <div class="fehlergrund fehlerText">&nbsp;</div>
                                        </b>
                                        <span class="txt-lg">Bestellt: <?php echo $item['Qty']; ?></span><br>
                                    </center>

                                </div>
                                <div class="col-sm-3">
                                    <center>
                                        <img
                                                src="libs/Barcode_org.php?text=PIC<?php echo $item['PLIheaderRef']; ?>&size=80&orientation=horizontal&codetype=code128"><br>
                                        PIC<?php echo $item['PLIheaderRef']; ?>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-block btn-lg btn-lg-modal"
                                data-dismiss="modal">
                            Schließen
                        </button>

                        <small>&nbsp;</small>

                        <form action="auftrag" method="post">
                            <input type="hidden" name="saveFehler" id="saveFehler" value="">
                            <input type="hidden" name="verfMenge" id="verfMenge" value="">
                            <input type="hidden" name="artID" id="artID" value="<?php echo $item['ID']; ?>">

                            <button type="button" class="btn btn-primary btn-block btn-lg btn-lg-modal"
                                    id="btnPrint_<?php echo $rows; ?>" onclick="window.print();">
                                <span class="glyphicon glyphicon-print"></span> Etikett Drucken
                            </button>
                        </form>

                        <form action="auftrag" method="post" class="successText">
                            <input type="hidden" name="savePos" value="1">
                            <input type="hidden" name="artMenge" value="<?php echo $item['Qty']; ?>">
                            <input type="hidden" name="artID" value="<?php echo $item['ID']; ?>">
                            <input type="hidden" name="item_<?php echo $item['ID']; ?>">
                            <small>&nbsp;</small>
                            <button type="submit" class="btn btn-success btn-block btn-lg btn-lg-modal"
                                    disabled="disabled" id="btnPosOk_<?php echo $rows; ?>">Position abschlie&szlig;en
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Formatierung des ausgedruckten Etiketts  -->
        <div id="printEtikett_<?php echo $rows; ?>" class="printLabel removeItem">
            <div class="print-col-2cm-l">
                <span style="display: block; width:4cm; margin-top:1cm; margin-left:-1cm;">
                    <img src="libs/imgEAN.php?code=<?php echo $auftrag[0]['EanUpc']; ?>" class="imgEAN">
                </span>
            </div>

            <div class="print-col-6cm">
                <center>
                    <?php echo utf8_encode($auftrag[0]['ItemName']); ?><br>
                    Art.Nr: <?php echo $item['ItemNrSuppl']; ?><br>
                    Best.Nr: <?php echo $aOrderLine['OrderNrExternal']; ?><br>
                    <span class="fehlerText">
                        <span class="txt-bin"><b><?php echo $auftrag[0]['BinName']; ?></b></span><br>
                        Pixi: <?php echo $bestand; ?> m / Druck: <?php echo date('d.m.y'); ?>
                    </span>
                    <?php echo(isset($_SESSION['kuerzel']) ? 'ID: ' . strtoupper($_SESSION['kuerzel']) : '') ?><br>
                    <b>
                        <div class="fehlergrund fehlerText">&nbsp;</div>
                    </b>
                    <span class="txt-lg">Bestellt: <?php echo $item['Qty']; ?></span><br>
                </center>
            </div>

            <div class="print-col-2cm-r">
                <span class="lblText_r">PIC<?php echo $item['PLIheaderRef']; ?><br></span>
                <img src="libs/Barcode_org.php?text=PIC<?php echo $item['PLIheaderRef']; ?>&size=40&orientation=vertical&codetype=code128">
            </div>
        </div>
        <!-- ./ label -->
        <script>
            $(document).ready(function () {
                $('#sItemVerfMenge').hide();

                $('input:radio').on("click", function () {
                    $('#sItemVerfMenge').val('');
                    $('#sItemVerfMenge').hide();
                });

                $('#radNumStoff').on("click", function () {
                    $('#sItemVerfMenge').show();
                });

                $("#btnPrint_<?php echo $rows;?>").click(function () {
                    $("#btnPosOk_<?php echo $rows; ?>").removeAttr('disabled');
                    $("#printEtikett_<?php echo $rows; ?>").addClass('removeItem');
                    $("#printEtikett_<?php echo $rows; ?>").removeClass('visible-print-block');
                    //this.form.submit();
                    if ($("#saveFehler").val() != "") {
                        $("#verfMenge").val($('#sItemVerfMenge').val());
                        this.form.submit();
                    }

                });


                $('#sItemVerfMenge').on('input', function () {
                    $(".fehlergrund").text("Fehler: Max. Verf. Menge = " + this.value);
                });

                $(".druckFehler").click(function () {
                    $(".fehlergrund").text("Fehler: " + this.value);
                    $("#saveFehler").val(this.value);
                });

                // Highlighting einer markierten Auftragsposition
                $(".selectable").click(function () {
                    $('.selectable').removeClass('tab-button');
                    $(this).toggleClass('tab-button');
                });

                $("#btnOK_<?php echo $rows;?>").click(function () {
                    $("#printEtikett_<?php echo $rows; ?>").removeClass('removeItem');
                    $("#printEtikett_<?php echo $rows; ?>").addClass('visible-print-block');
                    $("#printTitle").html('Etikett für Scan-In drucken');
                    $(".fehlerText").hide();
                    $(".successText").show();
                });
                $("#btnFehler_<?php echo $rows;?>").click(function () {
                    $("#printEtikett_<?php echo $rows; ?>").removeClass('removeItem');
                    $("#printEtikett_<?php echo $rows; ?>").addClass('visible-print-block');
                    $("#printTitle").html('Fehleretikett drucken');
                    $(".successText").hide();
                    $(".fehlerText").show();
                });

            });
        </script>
    <?php }
} ?>

<!-- Mod Auftrag abschließen / Wird derzeit nicht verwendet -->
<!--
<div id="modFinish" class="modal fade hidden-print" role="dialog">
    <form action="auftrag" method="post">
        <input type="hidden" name="finish" value="1">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Auftrag Abschließen</h4>
                </div>
                <div class="modal-body">
                    <p>Durch betätigen des JA-Button, schließen Sie diesen Auftrag ab. Offenen Positionen die nicht
                        bearbeitet werden konnten, erscheinen beim erneuten scannen der Artikel-EAN auf einem
                        Folgeauftrag.</p>

                    <div class="alert alert-info">
                        <p>Sollten Sie Anmerkungen zu diesem Auftrag haben, können Sie diese in folgendes
                            Kommentarfeld eintragen. Der Teamleiter sieht diese Nachricht dann in seiner
                            Auftragsübersicht.</p>
                    </div>

                    <label>Kommentar:<br>
                        <textarea rows="5" style="width:400px!important;" name="auftragKommentar"
                                  class="form-control"></textarea></label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-block btn-lg" data-dismiss="modal">NEIN
                    </button>
                    <button type="submit" class="btn btn-success btn-block btn-lg">JA</button>
                </div>
            </div>

        </div>
    </form>
</div>
-->


