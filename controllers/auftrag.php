<?php

/**
 * Auftrags-Controller
 *
 * @author: Marlon Böhland
 * @date:   14.12.2016
 * @access: public
 */
class Auftrag extends Controller
{
    private $size = 1;
    private $auftragAktiv = false;

    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    public function testMe()
    {
        echo "Blubb";
    }

    function index()
    {
        if (Session::checkAuth()) {
            $this->view->size = $this->getSize();
            // Nachrichten
            $this->view->msg_positionen_bearbeitet = 'Alle Auftragspositionen bearbeitet. Bitte schließen Sie den Auftrag ab.';
            $this->view->msg_keine_positionen = 'Für diesen Auftrag existieren keine Artikel.';

            $this->view->title = 'Auftragsbearbeitung';

            require_once('models/navigation_model.php');
            $this->view->nav = new Navigation_Model();

            $this->view->auftrag = new Auftrag_Model();

            require_once('models/picklist_model.php');
            $this->view->picklist = new Picklist_Model();
            $this->view->Pixi = new Pixi();

            $this->view->message = "";

            // EAN registrieren
            $this->view->artEAN = $_POST['artEAN'];
            if (isset($_POST['artEAN'])) {
                if (!isset($_SESSION['artEAN'])) {
                    $_SESSION['artEAN'] = $_POST['artEAN'];
                }
            }

            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('auftrag/index');
            //$this->view->render('auftrag/auftragsbearbeitung');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }

    /*
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}

