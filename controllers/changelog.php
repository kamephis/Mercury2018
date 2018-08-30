<?php

/**
 * Version Changelog
 *
 * @author: Marlon Böhland
 * @date:   08.08.2017
 * @access: public
 */
class Changelog extends Controller
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

            $this->view->title = 'Mercury Changelog';
            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('changelog/index');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}