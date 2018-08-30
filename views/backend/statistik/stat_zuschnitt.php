<?php
if (isset($_REQUEST['auftragsdatum_zus_von'])) {
    $setDate_zus_von = $_REQUEST['auftragsdatum_zus_von'];
} else {
    $setDate_zus_von = date("Y-m-d");
}

if (isset($_REQUEST['auftragsdatum_zus_bis'])) {
    $setDate_zus_bis = $_REQUEST['auftragsdatum_zus_bis'];
} else {
    $setDate_zus_bis = date("Y-m-d");
}
?>
<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">Zuschnitt-Auswertung</div>
        <div class="panel-body">
            <form name="frmFilter" method="post">
                <?php $aPicker = $this->back->getAllPicker(); ?>
                <label>Mitarbeiter
                    <select name="bearbeiter_zus" class="form-control">
                        <option value="">Alle</option>
                        <?php foreach ($aPicker as $sPicker) { ?>
                            <option value="<?php echo $sPicker['UID'] ?>"><?php echo $sPicker['vorname'] . ' ' . $sPicker['name']; ?></option>
                        <?php } ?>
                    </select>
                </label>

                <label>Aufträge von
                    <input type="date" id="auftragsdatum_zus" name="auftragsdatum_zus_von" class="form-control"
                           value="<?php echo $setDate_zus_von; ?>">
                </label>
                <label>Aufträge bis
                    <input type="date" id="auftragsdatum_zus" name="auftragsdatum_zus_bis" class="form-control"
                           value="<?php echo $setDate_zus_bis; ?>">
                </label>
                <!--
                                <label>EAN
                                    <input type="text" name="artEan" class="form-control">
                                </label>
                -->
                <button type="submit" class="btn btn-default">
                    Auswertung anzeigen
                </button>
            </form>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php if (isset($_POST['auftragsdatum_zus_von']) || isset($_POST['bearbeiter_zus']) && !isset($_POST['artEan'])) {
                    $zuschneideAuftraege = $this->statistik->getAuftragsInfos($_POST['bearbeiter_zus'], $_POST['auftragsdatum_zus_von'], $_POST['auftragsdatum_zus_bis'], null); ?>

                    <table class="table table-responsive table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Bearbeiter</th>
                            <!--<th>EAN</th>-->
                            <th>Anzahl (m)</th>
                            <th>Dauer</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($zuschneideAuftraege as $zAuftrag) { ?>
                            <tr>
                                <td>
                                        <span style="font-size:0.8em;"
                                              class="glyphicon glyphicon-calendar"></span>
                                    <?php echo $zAuftrag['datum']; ?>
                                </td>
                                <td>
                                    <span title="<?php echo $zAuftrag['UserID'] ?>"><?php echo utf8_encode($zAuftrag['uname']); ?></span>
                                </td>
                                <!--<td><a
                                        href="<?php echo URL . 'artikelinfo?searchType=ean&artikelnr=' . $zAuftrag['ArtEAN']; ?>"><?php echo $zAuftrag['ArtEAN']; ?></a>
                            </td>-->
                                <td><?php echo $zAuftrag['Menge']; ?></td>
                                <td><i class="glyphicon glyphicon-time"></i> <?php echo $zAuftrag['dauer']; ?></td>
                                <td>
                                    <center>
                                        <button data-toggle="collapse" class="btnGetStatistikDetails"
                                                data-id="<?php echo $zAuftrag['UserID']; ?>"
                                                data-auftragsDatum="<?php echo $zAuftrag['datum']; ?>"
                                                data-target="#det_<?php echo $zAuftrag['UserID']; ?>" type="button"
                                                name="btnDetails_<?php echo $zAuftrag['UserID']; ?>">
                                            <i class="glyphicon glyphicon-plus-sign"></i>
                                        </button>
                                    </center>
                                </td>
                            </tr>

                            <tr id="det_<?php echo $zAuftrag['UserID']; ?>" class="collapse">
                                <td colspan="5">
                                    <table class="table table-bordered table-striped table-responsive">
                                        <tr>
                                            <?php if ($_POST['getPixiDetails']) { ?>
                                                <th>Art.</th>
                                            <?php } ?>
                                            <th>EAN</th>
                                            <th>Menge</th>
                                            <th>Dauer</th>
                                            <!--<th>Pixi</th>-->
                                        </tr>

                                        <?php
                                        $auftragDetails = $this->statistik->getAuftragInfoUngruppiert($zAuftrag['UserID'], $_POST['auftragsdatum_zus']);

                                        foreach ($auftragDetails as $detail) {
                                            echo "<tr>";
                                            if ($_POST['getPixiDetails']) {
                                                $pixi = $this->Pixi->getItemInfo($detail['ArtEAN']);

                                                echo "<td>";
                                                echo $pixi['ItemName'];
                                                echo "</td>";
                                            }

                                            echo "<td>";
                                            echo '<a target="_blank" href=/artikelinfo?searchType=ean&artikelnr=' . $detail['ArtEAN'] . '>' . $detail['ArtEAN'] . '</a>';
                                            echo "</td>";

                                            echo "<td>";
                                            echo $detail['Menge'];
                                            echo "</td>";

                                            echo "<td>";
                                            echo $detail['dauer'];
                                            echo "</td>";
                                            echo "</tr>";
                                            ?>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>

                        <?php }
                        $zuschneideAuftraegeSumme = $this->statistik->getAuftragsInfosSumme($_POST['bearbeiter_zus'], $_POST['auftragsdatum_zus_von'], $_POST['auftragsdatum_zus_bis'], null);


                        ?>
                        <tr>
                            <td style="border-left:none!important; border-bottom:none!important; background:white!important;"></td>
                            <td class="text-right"><b>Summe</b></td>
                            <td><?php echo $zuschneideAuftraegeSumme[0]['menge']; ?></td>
                            <td>
                                <i class="glyphicon glyphicon-time"></i> <?php echo $zuschneideAuftraegeSumme[0]['dauer']; ?>
                            </td>
                            <td style="border-right:none!important; border-bottom:none!important; background:white!important;"></td>
                        </tr>
                        </tbody>
                    </table>
                <?php } elseif (isset($_POST['artEan'])) {
                    $aSearchresult = $this->statistik->getAuftragsInfos($_POST['UserID'], $_POST['auftragsdatum_zus'], $_POST['artEan']);
                    ?>
                    <table class="table table-bordered table-striped table-responsive">
                        <tr>
                            <th>Bearbeiter</th>
                            <th>EAN</th>
                            <th>Dauer</th>
                        </tr>

                        <?php foreach ($aSearchresult as $res) { ?>
                            <tr>
                                <td><?php echo $res['uname']; ?></td>
                                <td><?php echo $res['ArtEAN']; ?></td>
                                <td><?php echo $res['dauer']; ?></td>
                            </tr>
                        <?php } ?>

                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>