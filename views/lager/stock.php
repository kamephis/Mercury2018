<?php
$ean = "";
$bin = "";
$newStock = "";
$usr = "";

if (!empty($_REQUEST['EanUpc'])) {
    $ean = $_REQUEST['EanUpc'];
}

if (!empty($_REQUEST['binName'])) {
    $bin = $_REQUEST['binName'];
}

if (!empty($_REQUEST['stock'])) {
    $newStock = $_REQUEST['stock'];
}

$usr = $_SESSION['vorname'] . " " . $_SESSION['name'];

if ($_REQUEST['setStock']) {
    $this->Pixi->setStock($ean, $bin, $newStock, $usr);
}

?>

<h1>Einlagern</h1>
<form method="post">
    <label>EAN</label>
    <input type="text" name="EanUpc" class="form-control">


    <label>Lagerplatz (Format: AA-BB-CC)</label>
    <input type="text" name="binName" class="form-control">


    <label>Bestand
        <input type="number" name="stock" class="form-control" style="width:100%;">
    </label>
    <br><br>
    <input type="submit" name="setStock" value="Einlagern" class="btn btn-lg btn-success">
</form>

