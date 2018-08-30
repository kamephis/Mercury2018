<?php

/**
 * PicklistAdmin Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class ImportPixiPickliste extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        if (Session::checkAuth()) {
            $this->view->title = 'Neue Pickliste erstellen';

            require_once('models/navigation_model.php');
            $this->view->nav = new Navigation_Model();

            $this->model = new ImportPixiPickliste_Model();

            $this->view->pl = $this->model;
            $this->view->Pixi = new Pixi();
            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('backend/picklistAdmin/importPixiPickliste');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}