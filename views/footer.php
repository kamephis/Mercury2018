</div> <!-- END:Container -->
<?php
// Individuelle JavaScripte für jede View laden
/*
if (isset($this->js)) {
    foreach ($this->js as $js) {
        echo '<script type="text/javascript" src="' . URL . 'views/' . $js . '"></script>';
    }
}*/
?>

<script>
    $(document).ready(function () {

        if (~window.location.href.indexOf("artikelinfo")) {
            $('#nav_Artikelinfo').css("color", "white");
            $('#nav_Artikelinfo').css("font-weight", "semi-bold");
        }
        if (~window.location.href.indexOf("importPixiPickliste")) {
            $('#nav_Picklistenverwaltung').css("color", "white");
            $('#nav_Picklistenverwaltung').css("font-weight", "semi-bold");
        }
        if (~window.location.href.indexOf("backend")) {
            $('#nav_Übersicht').css("color", "white");
            $('#nav_Übersicht').css("font-weight", "semi-bold");
        }
    });
</script>
</body>
</html>