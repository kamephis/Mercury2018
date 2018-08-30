<?php

/**
 * Hilfe Controller
 *
 * @author: Marlon Böhland
 * @date:   25.01.2017
 * @access: public
 */
class Hilfe extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        if (Session::checkAuth()) {
            $this->view->title = 'Mercury Hilfe';
            require_once('models/navigation_model.php');
            $this->view->nav = new Navigation_Model();

            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('hilfe/index');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}