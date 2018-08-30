BLUBB
<?php
$userID = $_REQUEST['UserID'];
$datum = $_REQUEST['auftragsDatum'];

$aItems = $this->statistik->getAuftragInfoUngruppiert($userID, $datum);

echo "<table>";

foreach ($aItems as $item) {
    echo "<tr>";
    echo "<td>";
    echo $item['dauer'];
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
