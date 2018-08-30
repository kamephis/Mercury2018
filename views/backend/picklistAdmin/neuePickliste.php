<?php
// Picker aus dem System auslesen
$aPicker = $this->pl->getPicker();

// Neue Pickliste erstellen
if (isset($_POST['createPl']) && strlen($_POST['createPl']) > 0) {
    $this->pl->newPicklist();
}
?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span class="panel-title">Schritt 2 &ndash; Neue interne Pickliste erstellen</span>
                <a href="importPixiPickliste" class="btn btn-warning pull-right">
                    <span class="glyphicon glyphicon-cloud-download"></span>
                    &nbsp;Zum pixi* Picklisten-Import
                </a>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <form method="post" role="form" action="neuePickliste" id="frmNewPicklist">
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="col-sm-4" style="padding-left:0!important;">
                                <div class="well">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="txtSearch">Suchbegriff </label>
                                            <input type="search" class="searchParameter form-control calcSelEntries"
                                                   name="txtSearch" id="txtSearch" placeholder="Text im Titel...">
                                        </div>

                                        <div class="col-sm-3">
                                            <label for="minQty">Menge (min.)</label>
                                            <input type="number" min="1" class="searchParameter form-control"
                                                   name="minQty" id="minQty" placeholder="Min" value="1">
                                        </div>

                                        <div class="col-sm-3">
                                            <label for="maxQty">Menge (max.)</label>
                                            <input type="number" min="1" class="searchParameter form-control"
                                                   name="maxQty" id="maxQty" placeholder="Max">
                                        </div>
                                    </div>
                                    <br>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="selWarengruppe">Lagergruppe</label>
                                            <select name="selWarengruppe"
                                                    class="addComment searchParameter form-control" id="selWarengruppe">
                                                <option value="">Kein Filter</option>
                                                <option value="030">030 - LX-Artikel</option>
                                                <option value="150">150 - Organisation</option>
                                                <option value="090">090 - Paletten</option>
                                                <option value="010">010 - Stückware</option>
                                                <option value="050">050 - Amazon</option>
                                                <option value="130">130 - Coupon</option>
                                                <option value="070">070 - Meterware Ballen</option>
                                                <option value="120">120 - Paternoster 01</option>
                                                <option value="020">020 - Muster</option>
                                                <option value="170">170 - Lagerpakete</option>
                                                <option value="040">040 - L Rollen</option>
                                                <option value="160">160 - Zugaben</option>
                                                <option value="100">100 - XL Rollen</option>
                                                <option value="060">060 - Standard</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-3">
                                            <label for="selHalle">Halle</label>
                                            <select name="selHalle" class="searchParameter form-control addComment"
                                                    id="selHalle">
                                                <option value="">Kein Filter</option>
                                                <option value="H1">H1</option>
                                                <option value="H2">H2</option>
                                                <option value="ZG">ZG</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-3">
                                            <label for="selZuschnitt">Zuschnitt</label>
                                            <select name="selZuschnitt" class="searchParameter form-control addComment"
                                                    id="selZuschnitt">
                                                <option value="">Kein Filter</option>
                                                <option value="TI">Tisch</option>
                                                <option value="RM">Rollenmaschine</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-12">
                                            <br>
                                            <button type="button" id="btnResetFilter" class="btn btn-default">
                                                <span class="glyphicon glyphicon-remove"></span>
                                                &nbsp;Alle Filter entfernen
                                            </button>

                                            <button type="button" id="btnSortList" class="btn btn-default">
                                                <span class="glyphicon glyphicon-sort-by-attributes"></span>
                                                &nbsp;Liste neu sortieren
                                            </button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-sm-offset-1">
                                <div id="msgUpdateMode" class="hidden alert alert-info">
                                    <b>UPDATE MODUS</b> aktiv!
                                    <p>
                                        In diesem Modus können neue Positionen einer Pickliste hinzugefügt, Picker
                                        zugewiesen und der Picklistenkommentar geändert werden.<br>
                                        Es werden nur geänderte Felder aktualisiert. Soll z. B. nur der <strong>Kommentar</strong>
                                        geändert werden, dann reicht es, den Text
                                        in diesem Feld zu aktualiseren.
                                    </p>
                                    <br>
                                    <b>
                                        <a href="<?php echo URL . 'neuePickliste' ?>" class="btn btn-primary">
                                            Update Modus Aussschalten
                                        </a></b>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4">
                            <label>Artikel für neue Pickliste auswählen <span class="label label-primary"
                                                                              id="anzPositionenInListe"></span><br><br>
                                <select id="picklistItems" class="form-control dataTable" name="picklistItems[]"
                                        multiple
                                        style="height: 350px!important; width:600px!important;">
                                </select>
                            </label>
                        </div>
                        <div class="col-xs-12 col-sm-1">
                            <br>
                            <br>
                            <center>
                                <button type="button" class="btn btn-default btn-block" id="add2Picklist">
                                    Einzeln <span class="glyphicon glyphicon-step-forward pull-right"></span>
                                </button>
                                <br>

                                <button type="button" class="btn btn-default btn-block" id="addAll2Picklist">
                                    Alle <span class="glyphicon glyphicon-fast-forward pull-right"></span>
                                </button>
                                <br>

                                <button type="button" class="btn btn-default btn-block" id="removeFromPicklist">
                                    Einzeln <span class="glyphicon glyphicon-step-backward pull-left"></span>
                                </button>
                                <br>

                                <button type="button" class="btn btn-default btn-block" id="RemoveAllFromPicklist">
                                    Alle <span class="glyphicon glyphicon-fast-backward pull-left"></span>
                                </button>
                            </center>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <label>Inhalte neue Pickliste <span class="label label-primary"
                                                                id="anzPositionenNeuePickliste"></span><br><br>
                                <select class="form-control dataTable" required name="newPicklist[]" id="newPicklist"
                                        multiple style=" width:600px!important; height:350px!important;">
                                </select>
                            </label>
                            <button type="button" class="btn btn-default btn-block" id="btnSelAll">
                                Alle Positionen auswählen <span class="glyphicon glyphicon-align-justify"></span>
                            </button>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <label>Picklisten Nr:
                                <input type="text" name="plnr" class="form-control"
                                       value="<?php echo $this->pl->getNewPicklistNr(); ?>"
                                       style="width:250px!important;">
                            </label>
                            <div class="clearfix"></div>

                            <label>Typ:</label>
                            Standard <input type="radio" name="picklistType" value="gruppiert" checked="checked">
                            LX <input type="radio" name="picklistType" value="ungruppiert">
                            <div class="clearfix"></div>


                            <!-- Update Flag setzen -->
                            <input type="hidden" name="updatePicklist" value="1">

                            <label>Picker zuweisen<br>
                                <select name="picker" class="form-control" style="width:250px!important;">
                                    <option></option>
                                    <?php foreach ($aPicker as $picker) { ?>
                                        <option
                                                value="<?php echo $picker['UID']; ?>"
                                                style="width:250px!important;"><?php echo $picker['vorname'] . ' ' . $picker['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                            <br>
                            <label>Kommentar:
                                <br><code>Tipp: Doppelklick in das Textfeld<br> um das Kommentarfeld schnell zu leeren.</code><br>
                                <textarea name="plKommentar" id="plKommentar" class="form-control"
                                          style="width:250px!important; margin-top:5px!important;" required></textarea>
                            </label>

                            <input type="hidden" name="createPl" value="1">
                            <input type="hidden" name="creator"
                                   value="<?php echo $_SESSION['vorname'] . '&nbsp;' . $_SESSION['name']; ?>">
                            <div class="clearfix"></div>
                            <br>
                            <button type="submit" class="btn btn-success" id="btnPicklisteErstellen">
                                <span class="glyphicon glyphicon-plus"></span> Pickliste erstellen
                            </button>
                        </div>

                    </div>
            </div>
        </div>
    </div>
</div><!-- end panel-->

<script>
    $(document).ready(function () {

        // Button deaktivieren und Statusmeldung anzeigen
        $('#frmNewPicklist').submit(function () {
            $(this).find('button[type=submit]').prop('disabled', true).html('<span class="glyphicon glyphicon-repeat fast-right-spinner glyphicon-refresh"></span> Pickliste wird erstellt...');
        });

        /**
         * Laden der Positionen wenn die Seite geladen wurde.
         */

        function getPicklistItems() {
            $.ajax({
                type: "GET",
                url: "index.php?url=getItemsForPicklist",
                dataType: "html",
                success: function (response) {
                    $("#picklistItems").html(response);
                    $('#anzPositionenInListe').html($('#picklistItems option').length);
                    $('#anzPositionenNeuePickliste').html($('#newPicklist option').length);
                }
            });
        }

        getPicklistItems();

        $('input[name="plnr"]').on('keypress', function () {
            $('#msgUpdateMode').removeClass("hidden").fadeIn();
            $('input[name="updatePicklist"]').val("update");
        });

        /**
         * Filtern der Positionen
         */
        $(".searchParameter").on('input', function () {
            $.ajax({
                type: "GET",
                url: "index.php?url=getItemsForPicklist&searchString=" + $('#txtSearch').val() + "&minQty=" + $('#minQty').val() + "&maxQty=" + $('#maxQty').val() + "&warengruppe=" + $('#selWarengruppe').val() + "&halle=" + $('#selHalle').val() + "&zuschnitt=" + $('#selZuschnitt').val(),
                dataType: "html",
                success: function (response) {
                    $("#picklistItems").html(response);
                    $('select#newPicklist option').each(function () {
                        $('select#picklistItems').find("#" + this.value).remove();
                    });
                }
            });
        });

        $('.addComment').on('click', function () {
            if ($('#selHalle option:selected').val() != '') {
                var halle1 = $('#selHalle option:selected').text();
            } else {
                var halle1 = '';
            }

            if ($('#selWarengruppe option:selected').val() != '') {
                var warengruppe1 = $('#selWarengruppe option:selected').text();
            } else {
                var warengruppe1 = '';
            }
            if ($('#selZuschnitt option:selected').val() != '') {
                var zuschnitt = $('#selZuschnitt option:selected').text();
            } else {
                var zuschnitt = '';
            }

            $('#plKommentar').empty();
            $('#plKommentar').append(halle1);
            $('#plKommentar').append(' ');
            $('#plKommentar').append(warengruppe1);
            $('#plKommentar').append(' ');
            $('#plKommentar').append(zuschnitt);
        });

        // Löschen aller Filter
        $('#btnResetFilter').on('click', function () {
            $('.searchParameter').val('');
            getPicklistItems();
            $('#anzPositionenInListe').html($('#picklistItems option').length);
            $('#anzPositionenNeuePickliste').html($('#newPicklist option').length);
            $('#plKommentar').empty();
        });

        // Schnelles Löschen des Kommentarfelds
        $('#plKommentar').on('dblclick', function () {
            $('#plKommentar').empty();
        });
        $('#btnSortList').on('click', function () {
            getPicklistItems();
            $('#anzPositionenInListe').html($('#picklistItems option').length);
            $('#anzPositionenNeuePickliste').html($('#newPicklist option').length);
        });

        $('#add2Picklist').on('click', function () {
            var options = $('select#picklistItems option:selected').clone();
            $('select#newPicklist').append(options);
            $('select#picklistItems option:selected').remove();
            $('select#newPicklist option').prop('selected', true);
            $('#anzPositionenInListe').html($('#picklistItems option').length);
            $('#anzPositionenNeuePickliste').html($('#newPicklist option').length);
        });
        $('#addAll2Picklist').on('click', function () {
            var options = $('select#picklistItems option').clone();
            $('select#newPicklist').append(options);
            $('select#picklistItems option').remove();
            $('select#newPicklist option').prop('selected', true);
            $('#anzPositionenInListe').html($('#picklistItems option').length);
            $('#anzPositionenNeuePickliste').html($('#newPicklist option').length);
        });

        $('#removeFromPicklist').on('click', function () {
            var options = $('select#newPicklist option:selected').clone();
            $('select#picklistItems').append(options);
            $('select#newPicklist option:selected').remove();
            $('#anzPositionenInListe').html($('#picklistItems option').length);
            $('#anzPositionenNeuePickliste').html($('#newPicklist option').length);
        });
        $('#RemoveAllFromPicklist').on('click', function () {
            var options = $('select#newPicklist option').clone();
            $('select#picklistItems').append(options);
            $('select#newPicklist option').remove();
            $('#anzPositionenInListe').html($('#picklistItems option').length);
            $('#anzPositionenNeuePickliste').html($('#newPicklist option').length);
        });

        $('#btnSelAll').on('click', function () {
            $('select#newPicklist option').prop('selected', true);
        });

        $('#btnPicklisteErstellen').on('click', function () {
            $('select#newPicklist option').prop('selected', true);
        });
    });
</script>



