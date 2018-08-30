<?php

/**
 * Fehler-Artikel Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class ArtikelFehlerMobile extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        //if (Session::checkAuth()) {
            $this->view->title = 'Artikelfehler';
            require_once('models/navigation_model.php');
            $this->view->nav = new Navigation_Model();

            $this->view->artikelFehler = $this->model->getFehlerhafteArtikel();
            $this->view->artikelFehlerMobile = $this->model;

        require_once('models/picklist_model.php');
            $this->view->Picklist = new Picklist_Model();

        require_once('libs/Pixi.php');
        $this->view->Pixi = new Pixi();

            $this->view->render('header');
            $this->view->render('navigation');
        $this->view->render('mobile/artikelfehlerMobile');
            $this->view->render('footer');
        //} else {
        //    Error::getError('401');
        //}
    }

    function run()
    {
        $this->model->run();
    }
}