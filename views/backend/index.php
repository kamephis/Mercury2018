<?php
// Aktualisieren der Pickliste
if (isset($_REQUEST['updPicklist'])) {
    $this->backend->updatePicklist($_REQUEST['picklistID'], $_REQUEST['selPicker']);
}
// Update der Item Fehler
if (isset($_REQUEST['itemFehlerUpdate'])) {
    $this->mPicklist->setItemFehler($_REQUEST['itemID'], NULL, NULL);
}

// Leeren der Picklisten, Picklistenpositionen etc.
if ($_REQUEST['resetTab']) {
    if ($this->PicklistAdmin->resetTables()) {
        Controller::showMessages('MercuryTablesCleared');
    } else {
        Controller::showMessages('MercuryTablesClearFailed');
    }
}

// Löschen einer Pickliste
if ($_REQUEST['delPicklist']) {
    try {
        $this->backend->delPicklist($_REQUEST['picklistID']);
    } catch (Exception $e) {
        echo $e;
    }
    }
?>

<form method="post" id="frmOptions">

    <div class="row">
        <div class="col-sm-12">
            <a class="btn btn-default hidden-print pull-left" href="<?php echo URL . 'backend'; ?>">
                <span class="glyphicon glyphicon-refresh"></span>&nbsp;Anischt aktualisieren
            </a>
            <button type="button" class="btn btn-default btnPrint hidden-print" style="margin-left:1em;">
                <span class="glyphicon glyphicon-print"></span>&nbsp;Fehlerliste drucken
            </button>
            <input type="hidden" id="getPixiBestand" name="getPixiBestand" value="1">
            <button type="submit" class="btn btn-warning  hidden-print pull-right">
                <span class="glyphicon glyphicon-refresh"></span>&nbsp;Pixi* Bestände prüfen
            </button>

            <button type="button" data-toggle="modal" data-target="#modResetTab" class="btn btn-info hidden-print"
                    style="float:right; margin-right:1em;">
                <span class="glyphicon glyphicon-trash"></span>&nbsp;
                Tabellen leeren
            </button>

            <div class="clearfix"></div>
            <br>
        </div>
    </div>
    <!-- Reset Table Modal -->

    <div id="modResetTab" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header alert-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tabellen leeren</h4>
                </div>
                <div class="modal-body">
                    <p><b>ACHTUNG:</b> Alle <b>importierten</b> Pixi-Picklisten-Positionen, Stoff Palette-Picklisten und
                        alle erfassten Fehlerartikel werden gelöscht.</p>
                    <p><b>Dieser Vorgang kann nicht rückgängig gemacht werden.</b> Bitte stellen Sie auch sicher, dass
                        alle Fehlerartikel bearbeitet wurden.</p>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="resetTab" class="btn btn-success" value="Tabellen leeren">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>

        </div>
    </div>
</form>
<div class="countdown hidden-print">

</div>

<?php include("picklisten.inc.php"); ?>
<?php include("fehlerartikel.inc.php"); ?>
<?php // include("zuschnitt.inc.php"); ?>

<script>
    $(document).ready(function () {

        $(".btnGetStatistikDetails").on("click", function () {
            var userID = $(this).data("UserID");
            var datum = $(this).data("auftragsDatum");

            $.ajax({
                type: 'POST',
                url: 'index.php?url=statistik/statistik_details',
                data: {"UserID": userID, "auftragsDatum": datum},
                success: function (data) {
                    alert("blubb");
                }
            })
        });

        $('.frmDel').submit(function () {
            $(this).find('button[data-class=btnDelPicklist]').prop('disabled', true).html('<span class="glyphicon fast-right-spinner glyphicon-trash glyphicon-refresh"></span> löschen...');
            $(this).submit();
        });

        $('#btnTogglePicklistArea').on('click', function () {
            $("#pnlPicklist").toggle("slow", function () {
                //icon = $(this).find("i");
                //$('#icoPicklist').toggleClass("glyphicon-plus");
            });
        });
        $('#btnToggleFehlerArea').on('click', function () {
            $("#pnlFehler").toggle("slow", function () {
            });
        });

        $('[data-toggle="tooltip"]').tooltip();
        $(".btnDelItem").on("click", function () {
            var itemID = $(this).data("id");
            var picklistID = $(this).data("picklist");

            $.ajax({
                type: 'POST',
                url: 'index.php?url=delPicklistArticleBackend',
                data: {"picklistID": picklistID, "itemID": itemID},
                success: function (data) {
                    $("#row_" + itemID).remove();
                }

            })
        });

        $('#btnGetPixiStock').on('click', function () {
            $('#frmOptions').submit();
        });

        $.fn.countdown = function (callback, duration, message) {
            // Wenn keine Nachricht übergeben...
            message = message || "";

            var container = $(this[0]).html('');

            var countdown = setInterval(function () {

                if (--duration) {
                    // 5 Minuten zuvor warnen
                    if (duration < 1140) {
                        container.addClass('alert alert-danger');

                        $('html body').animate({"backgroundColor": "#b1040f"}, 1500);
                        container.html('<b>Mercury wird in ' + (duration / 60).toFixed(2) + ' Minuten aktualisiert.</b>');
                    }
                } else {
                    container.html('<b>Bitte warten, Mercury wird aktualisiert...</b>');
                    clearInterval(countdown);
                    callback.call(container);
                }
            }, 1000);
        };

        $(".countdown").countdown(redirect, 1440, "");

        function redirect() {
            window.location = "http://mercury.stoffpalette.com/backend";
        }

        $(".btnPrint").on("click", function () {
            window.print();
        });

        // geprueft Status
        $(".chkFehler").on("click", function () {

            var artID = this.value;
            var itemStatus;
            var username = "<?php echo $_SESSION['vorname'] . ' ' . $_SESSION['name'];?>";

            if (this.checked) {
                itemStatus = 1;
            } else {
                itemStatus = 0;
            }

            $.ajax({
                type: "POST",
                url: "index.php?url=setFehlerStatusBackend",
                data: {"articleID": artID, "iStatus": itemStatus, "sUser": username, "itemFehlerUpdate": "1"},
                dataType: "json",

                success: function (data) {
                    if (data.success === true) {
                        setTimeout(function () {

                            location.reload();
                        }, 0);
                    }
                }
            });
        });

        // Entfernen eines Artikelfehlers
        $(".btnDelError").on("click", function () {
            var artID = $(this).data("id");
            var itemStatus = '2';

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

        // Eskalation
        $(".btnEscalate").on("click", function () {
            var artID = $(this).data("id");
            var escComment = $("#txtaServiceInfoText_" + artID).val();

            $.ajax({
                type: 'POST',
                url: "index.php?url=setItemStatus",
                data: {"articleID": artID, "EscComment": escComment},
                success: function (data) {
                    $("#rowError_" + artID).remove();
                    $('#modEscalateItem_' + artID).modal('toggle');
                },
                complete: function () {
                    console.log('Der Artikel ' + artID + ' wurde an den Kundenservice zur Bearbeitung gemeldet.\n');
                    console.log('Kommentar ' + escComment);
                }
            })
        });

        // Eskalation
        $(".btnSaveFehlerKommentar").on("click", function () {
            var artID = $(this).data("id");
            var sUser = "<?php echo $_SESSION['vorname'] . ' ' . $_SESSION['name'];?>";
            var escComment = $("textarea#txtInfoText_" + artID).val();

            $.ajax({
                type: 'POST',
                url: "index.php?url=setFehlerTextKus",
                data: {"articleID": artID, "EscComment": escComment, "ItemFehlerUser": sUser},
                success: function (data) {
                    $('textarea#txtaServiceInfoText_' + artID).val(escComment);
                },
                complete: function () {
                }
            })
        });
    })
</script>