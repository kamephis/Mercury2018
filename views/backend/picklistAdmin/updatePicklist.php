<?php
$aItems = $this->pl->getInternMasterPicklistItems();
foreach ($aItems as $item) {
    if ((int)($item['Qty']) <= 6) {
        $mark = 'style="color:black;"';
    } else {
        $mark = 'style="color:blue;"';
    }
    echo '<option value="' . $item['ID'] . '" id="' . $item['ID'] . '"' . $mark . '">' . $item['BinName'] . ' |&nbsp;&nbsp;' . $item['Qty'] . ' ME | ' . $item['ItemName'] . '</option>';
}
