<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary hidden-print">
            <div class="panel-heading">
                <span class="hidden-print">
                <button type="button" class="btn btn-xs btn-primary pull-right" id="btnTogglePicklistArea">
                    <i id="icoPicklist" class="glyphicon glyphicon-minus"></i>
                </button>
                    </span>
    <span class="hidden-print panel-title">
        Aktive Picklisten
    </span>
                <span class="visible-print panel-title">
            Pickliste
        </span>
            </div>
            <div class="panel-body" id="pnlPicklist">
                <div class="hidden-print">
                    <?php if (sizeof($this->backend->getActivePicklists()) > 0) { ?>
                        <div class="row">
                            <div class="col-sm-1"><b>Fortschritt</b></div>
                            <div class="col-sm-1"><b>Datum</b></div>
                            <div class="col-sm-1"><b>Pickliste #</b></div>
                            <div class="col-sm-1"><b>Gesamt</b> <a href="#" data-toggle="tooltip"
                                                                   title="Artikel mit dem selben EAN werden in dieser Ansicht als eine Position dargestellt (gruppiert)."><span
                                            class="glyphicon glyphicon-question-sign"></span></a>
                            </div>
                            <div class="col-sm-1"><b>Offen</b></div>
                            <div class="col-sm-2"><b>Kommentar</b></div>
                            <div class="col-sm-2"><b>Picker</b></div>
                            <div class="col-sm-3"><b>Aktion</b></div>
                        </div>

                        <br>
                        <?php
                        $aActivePicklists = $this->backend->getActivePicklists();
                        foreach ($aActivePicklists as $picklist) {

                            ?>


                            <form name="selNewPicker" method="post">
                                <div class="row">

                                    <?php
                                    $plStatus = $this->backend->getPicklistStatusProzent($picklist['PLHkey']);
                                    ?>
                                    <div class="col-sm-1 hidden-print">
                                        <div class="progress">
                                            <?php
                                            $barClass = 0;

                                            switch ($plStatus) {
                                                case $plStatus < 33;
                                                    $barClass = 'progress-bar-danger';
                                                    break;

                                                case $plStatus > 33 && $plStatus < 100;
                                                    $barClass = 'progress-bar-warning';
                                                    break;

                                                case $plStatus = 100;
                                                    $barClass = 'progress-bar-success';
                                                    break;
                                            }
                                            ?>

                                            <div class="progress-bar <?php echo $barClass; ?>" role="progressbar"
                                                 aria-valuenow="<?php echo $plStatus; ?>"
                                                 aria-valuemin="0" aria-valuemax="100"
                                                 style="width:<?php echo $plStatus; ?>%">
                                                <?php echo $plStatus; ?>%
                                                <?php if ($plStatus == 100) echo '&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>'; ?>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <?php
                                        echo $picklist['PicklistCreateDate'];
                                        ?>
                                    </div>

                                    <div class="col-sm-1"><?php echo $picklist['PLHkey']; ?></div>

                                    <div class="col-sm-1"><?php echo $this->mPicklist->getGroessePicklist($picklist['PLHkey']); ?>
                                        Stk.
                                    </div>

                                    <div class="col-sm-1"><?php echo $this->mPicklist->getRealPicklistItemCount($picklist['PLHkey']); ?>
                                        Stk.
                                    </div>

                                    <div class="col-sm-2"><?php echo $picklist['PLcomment']; ?></div>

                                    <div class="col-sm-2">

                                        <select name="selPicker"
                                                class="form-control" <?php if ($picklist['status'] == '1') {
                                            echo "DISABLED";
                                        } ?>>
                                            <option></option>
                                            <?php
                                            $aPicker = $this->backend->getAllPicker();
                                            foreach ($aPicker as $picker) {
                                                ?>
                                                <option
                                                    <?php
                                                    if ($picker['UID'] == $picklist['picker']) echo 'SELECTED';
                                                    ?>
                                                        value="<?php echo $picker['UID']; ?>"><?php echo $picker['vorname'] . ' ' . $picker['name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>


                                    <div class="col-sm-3 hidden-print">

                                        <input type="hidden" name="updPicklist" value="1">
                                        <input type="hidden" name="pickerID" value="<?php echo $picklist['picker']; ?>">
                                        <input type="hidden" name="picklistID"
                                               value="<?php echo $picklist['PLHkey']; ?>">

                                        <button type="button" data-toggle="collapse"
                                                data-target="#pnlPicklistArticles_<?php echo $picklist['PLHkey']; ?>"
                                                class="btn btn-default btn-sm btnToggleItems">
                                            <span class="glyphicon glyphicon-eye-open btnToggleIco"></span>&nbsp;Positionen
                                            Ein-/Ausblenden
                                        </button>

                                        <?php if ($picklist['status'] == 0) { ?>
                                            <button type="submit" class="btn btn-primary btn-sm" name="updPicklist"
                                                    value="1">
                                                <span class="glyphicon glyphicon-floppy-save"></span>&nbsp;Speichern
                                            </button>
                                        <?php } ?>

                                        <?php
                                        $statusCheck = 0;
                                        if ($this->backend->getPicklistItems($picklist['PLHkey'])) {
                                            $aPicklistItemsStatusCheck = $this->backend->getPicklistItems($picklist['PLHkey']);

                                            foreach ($aPicklistItemsStatusCheck as $statusItem) {
                                                if ($statusItem['ItemStatus'] > 1) {
                                                    $statusCheck++;
                                                }
                                            }
                                        }
                                        ?>
                                        <?php if ($statusCheck == 0) { ?>
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    data-class="btnDelPicklist" name="delPicklist"
                                                    value="1" data-toggle="modal" data-target="#modDelPickItem">
                                                <span class="glyphicon glyphicon-trash"></span>&nbsp;Löschen
                                            </button>
                                        <?php } ?>
                                    </div>
                                    <br>
                                </div>
                            </form>

                            <div class="row collapse hidden-print"
                                 id="pnlPicklistArticles_<?php echo $picklist['PLHkey']; ?>" style="margin-bottom:4em;">
                                <br>

                                <table class="table table-striped table-bordered table-condensed table-responsive">
                                    <thead>
                                    <tr>
                                        <th>
                                            <center>#</center>
                                        </th>
                                        <th>
                                            <center>Status</center>
                                        </th>
                                        <th>Art.Nr</th>
                                        <th>EAN</th>
                                        <th>
                                            <center>Menge</center>
                                        </th>
                                        <th>Bezeichnung</th>
                                        <th>Standort</th>
                                        <th>Letzte Aktualisierung</th>
                                        <th>Lagerplatz</th>
                                        <th class="alert-warning"><strong>Pixi Pickliste</strong></th>
                                        <th class="alert-warning"><strong>Pixi Bestell-Nr.</strong></th>
                                        <!--<th>Pixi Bestand</th>-->
                                        <th>Aktion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $cRow = 0;
                                    $aPicklistItems = $this->backend->getPicklistItems($picklist['PLHkey']);
                                    foreach ($aPicklistItems as $pItem) {
                                        $cRow++;
                                        ?>
                                        <tr id="row_<?php echo $pItem['ID'] ?>">
                                            <td>
                                                <center><?php echo $cRow; ?></center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php
                                                    switch ($pItem['ItemStatus']) {
                                                        case '0':
                                                            echo '--';
                                                            break;
                                                        case '1':
                                                            echo '<b><span class="glyphicon glyphicon-ban-circle text-danger" style="font-size:110%;" title="offen"></span></b>';
                                                            break;
                                                        case '2':
                                                            echo '<b><span class="glyphicon glyphicon-ok-circle text-success" style="font-size:110%;" title="gepickt"></span></b>';
                                                            break;
                                                        case '3':
                                                            echo '<b><span class="glyphicon glyphicon-scissors" style="font-size:110%;" title="zugeschnitten"></span></b>';
                                                            break;
                                                        case '4':
                                                            echo '<b><span class="glyphicon glyphicon-flash" style="font-size:110%;" title="fehlerhaft"></span></b>';
                                                            break;
                                                        case '5':
                                                            echo '<b><span class="glyphicon glyphicon-bullhorn" style="font-size:110%;" title="eskaliert"></span></b>';
                                                            break;
                                                        case '6':
                                                            echo '<b><span class="glyphicon glyphicon-send" style="font-size:110%;" title="Kunde wurde benachrichtigt"></span></b>';
                                                            break;
                                                    }
                                                    ?></center>
                                            </td>
                                            <td><?php echo $pItem['ItemNrSuppl']; ?></td>
                                            <td>
                                                <a href="<?php echo URL . 'artikelinfo?searchType=ean&artikelnr=' . $pItem['EanUpc']; ?>"><?php echo $pItem['EanUpc']; ?></a>
                                                <span class="visible-print">
                                     <img src="libs/Barcode_org.php?text=<?php echo $pItem['EanUpc']; ?>&size=60&orientation=horizontal&codetype=code128">
                                </span>
                                            </td>
                                            <td>
                                                <center><?php echo $pItem['BestMenge']; ?></center>
                                            </td>
                                            <td><?php echo $pItem['ItemName']; ?></td>
                                            <td><?php echo $pItem['CurrentItemLocation']; ?></td>
                                            <!-- Aktualisierungszeitpunt -->
                                            <td>
                                                <span style="font-size:0.8em;"
                                                      class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo $pItem['UpdateDate']; ?>
                                                <span style="font-size:0.8em; margin-left:2em;"
                                                      class="glyphicon glyphicon-time"></span>&nbsp;<?php echo $pItem['UpdateTime']; ?>
                                                Uhr

                                            </td>
                                            <td><?php echo $pItem['BinName']; ?></td>
                                            <td class="alert-warning"><span
                                                        class="text-pixi"><?php echo $pItem['PLIheaderRef']; ?></span>
                                            </td>
                                            <td class="alert-warning"><span
                                                        class="text-pixi"><?php echo $pItem['OrderNrExternal']; ?></span>
                                            </td>
                                            <!--<td><?php /*
                                                if ($_REQUEST['getPixiBestand']) {
                                                    if ($this->Pixi->getItemStock($pItem['EanUpc'])) {
                                                        $pBestand = $this->Pixi->getItemStock($pItem['EanUpc']);
                                                        echo $pBestand['PhysicalStock'];
                                                    } else {
                                                        echo 'k. A. (Bundleartikel)';
                                                    }
                                                } else {
                                                    echo "--";
                                                }*/
                                                ?>
                                            </td>-->
                                            <td>
                                                <?php if ($pItem['ItemStatus'] <= 1) { ?>
                                                    <!--<form method="post">-->
                                                    <!--<input type="hidden" name="delPicklistItem"
                                                               value="<?php echo $pItem['ID'] ?>">-->
                                                    <button type="button"
                                                                name="btnDelItem_<?php echo $pItem['ID'] ?>"
                                                                data-picklist="<?php echo $pItem['PLHkey'] ?>"
                                                                data-id="<?php echo $pItem['ID'] ?>"
                                                                class="btn btn-xs btn-danger btnDelItem">
                                                            <span class="glyphicon glyphicon-trash"></span> Löschen
                                                        </button>
                                                    <!--</form>-->
                                                <?php } ?>

                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <div class="well well-sm">
                                    <span class="glyphicon glyphicon-ban-circle"></span> offen&nbsp;&nbsp;
                                    <span class="glyphicon glyphicon-ok-circle"></span> gepickt&nbsp;&nbsp;
                                    <span class="glyphicon glyphicon-flash"></span> fehlerhaft&nbsp;
                                    <span class="glyphicon glyphicon-scissors"></span> zugeschnitten
                                    <span class="glyphicon glyphicon-bullhorn"></span> An KuS gemeldet
                                    <span class="glyphicon glyphicon-send"></span> Kunde benachrichtigt
                                </div>
                            </div>
                            <!-- end picklistitems -->
                        <?php } ?>

                    <?php } else {
                        echo '<div class="alert alert-info">';
                        echo 'Derzeit sind keine Picklisten aktiv.';
                        echo '</div>';
                    } ?>
                </div><!-- ./ pnl primary -->
            </div>
        </div>
