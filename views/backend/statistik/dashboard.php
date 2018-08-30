<?php
function my_date_diff($date1, $date2)
{
    $datetime1 = new DateTime($date1);
    $datetime2 = new DateTime($date2);

    $diff = round(($datetime2->format('U') - $datetime1->format('U')));

    return $diff / 60;
}

?>
<div class="panel panel-primary">
    <div class="panel-heading">Dashboard</div>
    <div class="panel-body">
        <?php require_once('stat_pick.php'); ?>
        <?php require_once('stat_zuschnitt.php'); ?>
    </div>
</div>

<script>
    $(document).ready(function () {

    }
</script>