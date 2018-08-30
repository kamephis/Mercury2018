<?php

/**
 * ScanArt Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class ScanArt extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        if (Session::checkAuth()) {
            require_once('models/navigation_model.php');
            $this->view->nav = new Navigation_Model();

            $this->view->title = 'Artikel-EAN Scannen';
            $this->model = new ScanArt_Model();

            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('auftrag/scanArt');
            $this->view->render('footer');
        }
    }

    /**
     * Prüfen ob es einen Artikel mit dieser EAN in der Liste
     * der importierten Artikel gibt.
     */
    function chkItem()
    {
        Session::set('eanScanned', '1');
        Session::set('artEAN', $_POST['artEAN']);

        $aCnt = $this->model->checkIfArticleOrderExists($_REQUEST['artEAN']);

        if ($aCnt[0]['anz'] == '0') {
            // Anzeige der Fehlermeldung
            header('location: ' . URL . 'scanArt?e=1');
        } else {
            header('location: ' . URL . 'auftrag?eanScanned=1');
        }
    }

    function run()
    {
        $this->model->run();
    }
}