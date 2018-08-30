<div class="row hidden-print">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span class="panel-title">Stoff-Zuschnitt</span>
            </div>
            <form method="post">
            <div class="panel-body">
                <?php
                if (isset($_REQUEST['showAuftraege'])){
                ?>

                    <div class="row">
                        <div class="col-sm-6">
                            <label>
                                Bearbeiter
                                <?php $aPicker = $this->backend->getAllPicker(); ?>
                                <select name="bearbeiter" class="form-control">
                                    <option value="">Alle</option>
                                    <?php foreach ($aPicker as $sPicker) { ?>
                                        <option value="<?php echo $sPicker['UID'] ?>"><?php echo utf8_encode($sPicker['vorname'] . ' ' . $sPicker['name']); ?></option>
                                    <?php } ?>
                                </select>
                            </label>

                            <label>Auftragsdatum
                                <input type="date" id="auftragsdatum" name="auftragsdatum" class="form-control"
                                       placeholder="T.M.JJJJ">
                            </label>

                            <button type="submit" class="btn btn-default">
                                Zuschneideaufträge Anzeigen
                            </button>
                        </div>
            </form>
            <div class="clearfix"></div>
            <br>
            <div class="row">
                <div class="col-sm-6">
                    <?php /* if (isset($_REQUEST['auftragsdatum']) || isset($_REQUEST['bearbeiter'])) {*/
                    $zuschneideAuftraege = $this->mAuftrag->getAuftragsInfos($_REQUEST['bearbeiter'], $_REQUEST['auftragsdatum']); ?>

                    <table class="table table-responsive table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Bearbeiter</th>
                            <th>EAN</th>
                            <th>Anzahl (m)</th>
                            <th>Dauer (Minuten)</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($zuschneideAuftraege as $zAuftrag) { ?>
                            <tr>
                                <td>
                                        <span style="font-size:0.8em;"
                                              class="glyphicon glyphicon-calendar"></span>
                                    <?php echo $zAuftrag['datum']; ?></td>
                                <td><?php echo utf8_encode($zAuftrag['uname']); ?></td>
                                <td><a
                                            href="<?php echo URL . 'artikelinfo?searchType=ean&artikelnr=' . $zAuftrag['ArtEAN']; ?>"><?php echo $zAuftrag['ArtEAN']; ?></a>
                                </td>
                                <td><?php echo $zAuftrag['Anzahl']; ?></td>
                                <td><?php echo number_format(($zAuftrag['dauer'] / 60), 2, ',', ' '); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php /*} */ ?>
                </div>

            </div>
            <?php } else {
                echo '<div class="alert alert-info">Zum Anzeigen der Aufträge den Anzeigen Button betätigen</div>';
                echo '<form method="post">';
                echo '<input type="hidden" name="showAuftraege" value="1">';
                echo '<input type="submit" class="btn btn-default" value="Aufträge anzeigen">';
                echo '</form>';
            } ?>

        </div>
    </div>
            </div>
        </div>
    </div>
<script>
    $(function () {
        $("#auftragsdatum1").datepicker({
            dateFormat: "dd.mm.yy"
        });
        $("#format").on("change", function () {
            $("#auftragsdatum1").datepicker("option", "dateFormat", $(this).val());
        });
    });
</script>
