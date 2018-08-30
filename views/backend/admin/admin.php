<style>
    @page {
        size: a4 portrait;
        margin: 2cm;
    }

    @media screen {
        .printCard {
            width: 350px;
            height: 200px;
            padding: 20px;
            display: block;
        }
    }

    @media print {
        .printCard {
            position: absolute;
            padding-left: 1cm;
            padding-right: 1cm;
            padding-top: 0.5cm;
            left: 0cm;
            top: 0cm;
            width: 85mm;
            height: 54mm;
        }
    }
</style>
<div class="col-xs-12">
    <div class="col-xs-6 hidden-print">
        <div class="panel panel-default">
            <div class="panel-heading">
                Benutzerausweise drucken
            </div>
            <div class="panel-body">
                <div class="col-xs-6">
                    <form name="frmNewUser" method="post">
                        <input type="hidden" name="newUser" value="1">

                        <label>Mitarbeieter(in):
                            <select name="user" class="form-control">
                                <?php
                                foreach ($this->pickerList as $user) {
                                    echo '<option value="' . $user['UID'] . '">';
                                    echo mb_convert_encoding($user['vorname'], 'utf8') . ' ' . mb_convert_encoding($user['name'], 'utf-8');
                                    echo '</option>';
                                }
                                ?>
                            </select>
                        </label>

                        <label>Kennwort
                            <input type="text" name="psswd" class="form-control">
                        </label>

                        <input type="hidden" name="showCard" value="1">
                        <button type="submit" class="btn btn-primary">
                            Drucken
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-2">
        <div style="border:1px dashed grey;" class="printCard">
            <center>

                <?php
                if ($_POST['showCard'] == 1) {
                    $p = $this->PicklistAdmin->getFullPickerInfo($_POST['user']);
                    ?>
                    <img src="out/img/logo_600.jpg" width="250px"><br>
                    <img
                            src="libs/Barcode_org.php?text=<?php echo $p[0]['Username'] . '-' . $_POST['psswd']; ?>&size=90&orientation=horizontal&codetype=code128"
                            width="140px"><br>
                    <span style="margin-left:0px;"><?php echo mb_convert_encoding($p[0]['vorname'] . ' ' . $p[0]['name'], "utf8"); ?></span>
                    <?php
                }
                ?>
            </center>
            <div class="clearfix"></div>
        </div>
    </div>