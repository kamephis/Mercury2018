<?php
if (!isset($_SESSION['sNavItems'])) {
    $aNavItems = $this->nav->getNavItems($_SESSION['access_level']);
    $_SESSION['sNavItems'] = $aNavItems;
} else {
    $aNavItems = $_SESSION['sNavItems'];
}
?>
<nav class="navbar navbar-inverse navbar-fixed-top hidden-print" id="mainNav">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Navigation ein/ausblenden</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/changelog" style="margin-right:4em;"><span
                    class="glyphicon glyphicon-leaf small"></span>&nbsp;Mercury
            <small style="color:red;"><?php echo MERCURY_VERSION; ?></small>
        </a>
    </div>
    <!-- ./ Header -->
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <?php foreach ($aNavItems as $item) { ?>
                <li>
                    <a href="<?php echo $item['url']; ?>" id="nav_<?php echo $item['text']; ?>">
                        <?php echo $item['text']; ?>&nbsp;<span
                                class="<?php echo $item['css_class']; ?> pull-right text-sm visible-xs"></span>
                    </a>
                </li>
            <?php } ?>

            <?php
            if ($_SESSION['appType'] == 'zuschnitt') { ?>
                <li>
                    <a onclick="toggleFullScreen()">
                        Vollbild&nbsp;<span class="glyphicon glyphicon-fullscreen"></span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <!--/.nav-collapse -->
</nav>
<br class="hidden-print">
<br class="hidden-print">
<br class="hidden-print">