<?php

/**
 * Backend-Controller
 *
 * @author: Marlon Böhland
 * @date:   25.01.2016
 * @access: public
 */
class Backend extends Controller
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

            require_once('models/picklist_model.php');
            $this->view->mPicklist = new Picklist_Model();

            require_once('models/auftrag_model.php');
            $this->view->mAuftrag = new Auftrag_Model();

            $this->view->Pixi = new Pixi();
            require_once('models/picklistAdmin_model.php');
            $this->view->PicklistAdmin = new PicklistAdmin_Model();

            $this->view->title = 'Mercury Administration';
            $this->view->tFehler = 'Fehlerhafte Artikel';
            $this->view->tPickstatus = 'Aktive Pickaufträge';

            $this->view->backend = new Backend_Model();

            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('backend/index');
            $this->view->render('footer');

            // Formularhandling - Fehlerartikelprüfung
            if ($_REQUEST['itemID']) {

            }
        } else {
            echo "Unbekannter Sessionfehler.";
        }
    }

    function run()
    {
        $this->model->run();
    }
}