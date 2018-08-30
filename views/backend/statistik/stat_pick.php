<?php
if (isset($_REQUEST['auftragsdatum_von'])) {
    $setDate_pickFrom = $_REQUEST['auftragsdatum_von'];
} else {
    $setDate_pickFrom = date("Y-m-d");

}

if (isset($_REQUEST['auftragsdatum_bis'])) {
    $setDate_pickTo = $_REQUEST['auftragsdatum_bis'];
} else {
    $setDate_pickTo = date("Y-m-d");
}


?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            <?php
            $jsonPickedLists = $this->statistik->getPickStatistik($_POST['bearbeiter'], $_POST['auftragsdatum_von'], $_POST['auftragsdatum_bis']);
            ?>
            ['Task', 'Hours per Day'],
            <?php foreach ($jsonPickedLists as $jPick) { ?>
            ['<?php echo utf8_encode($jPick['bearbeiter']); ?>', <?php echo $jPick['menge']; ?>],
            <?php } ?>
        ]);

        var options = {
            title: 'Verhältnis Picker / Picks Gesamt'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
</script>

<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">Picker-Auswertung</div>
        <div class="panel-body">
            <div id="piechart" style="width: 800px; height: 350px;"></div>

            <form name="frmFilter" method="post">
                <?php $aPicker = $this->back->getAllPicker(); ?>
                <label>Mitarbeiter
                    <select name="bearbeiter" class="form-control">
                        <option value="">Alle</option>
                        <?php foreach ($aPicker as $sPicker) { ?>
                            <option value="<?php echo $sPicker['UID'] ?>"><?php echo $sPicker['vorname'] . ' ' . $sPicker['name']; ?></option>
                        <?php } ?>
                    </select>
                </label>

                <label>Datum von:
                    <input type="date" id="auftragsdatum_von" name="auftragsdatum_von" class="form-control"
                           value="<?php echo $setDate_pickFrom; ?>">
                </label>
                <label>Datum bis:
                    <input type="date" id="auftragsdatum_von" name="auftragsdatum_bis" class="form-control"
                           value="<?php echo $setDate_pickTo; ?>">
                </label>

                <button type="submit" class="btn btn-default">
                    Auswertung anzeigen
                </button>
            </form>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <?php if (isset($_POST['auftragsdatum_von']) && isset($_POST['auftragsdatum_bis']) || isset($_POST['bearbeiter'])) {
                        $aPickedLists = $this->statistik->getPickStatistik($_POST['bearbeiter'], $_POST['auftragsdatum_von'], $_POST['auftragsdatum_bis']);

                        ?>

                        <table class="table table-responsive table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Bearbeiter</th>
                                <th>Stück</th>
                                <th>Dauer</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($aPickedLists as $pickedList) { ?>
                                <tr>
                                    <td>
                                        <span style="font-size:0.8em;"
                                              class="glyphicon glyphicon-calendar"></span>
                                        <?php echo $pickedList['datum']; ?></td>
                                    <td><?php echo utf8_encode($pickedList['bearbeiter']); ?></td>
                                    <td><?php echo $pickedList['menge']; ?></td>
                                    <td><i class="glyphicon glyphicon-time"></i> <?php echo $pickedList['dauer']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php
                            $aPickedListsDay = $this->statistik->getPickStatistikSumme($_POST['bearbeiter'], $_POST['auftragsdatum_von'], $_POST['auftragsdatum_bis']);
                            ?>
                            <tr>
                                <td style="border-left:none!important; border-bottom:none!important; background:white!important;"></td>
                                <td class="text-right"><b>Summe</b></td>
                                <td><?php echo $aPickedListsDay[0]['menge']; ?></td>
                                <td><i class="glyphicon glyphicon-time"></i> <?php echo $aPickedListsDay[0]['dauer']; ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>