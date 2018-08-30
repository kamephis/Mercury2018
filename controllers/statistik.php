<?php

/**
 * Lager Controller
 *
 * @author: Marlon Böhland
 * @date:   11.01.2018
 * @access: public
 */
class statistik extends Controller
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

            $this->view->statistik = $this->model;

            // Backend
            require_once('models/backend_model.php');
            $this->view->back = new Backend_Model();
            $aPicker = $this->view->back->getAllPicker();

            // Auftrag
            require_once('models/auftrag_model.php');
            $this->view->mAuftrag = new Auftrag_Model();

            // Pixi
            require_once('libs/Pixi.php');
            $this->view->Pixi = new Pixi();

            $this->view->title = 'Mercury Statistik';
            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('backend/statistik/dashboard');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}