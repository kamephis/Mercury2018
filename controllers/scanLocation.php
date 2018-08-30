<?php

/**
 * ScanLocation Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class ScanLocation extends Controller
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

            $this->view->title = 'Pickwagen anmelden';

            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('mobile/scanLocation');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}