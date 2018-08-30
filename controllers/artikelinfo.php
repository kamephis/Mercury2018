<?php

/**
 * Aufruf von Artikelinformationen aus PIXI
 *
 * @author: Marlon Böhland
 * @date:   25.01.2017
 * @access: public
 */
class Artikelinfo extends Controller
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

            $this->view->Pixi = new Pixi();
            $this->view->title = 'Mercury Artikelinfo';
            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('artikelinfo/index');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}