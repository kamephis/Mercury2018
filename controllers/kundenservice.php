<?php

/**
 * Kundenservice Controller
 *
 * @author: Marlon Böhland
 * @date:   22.09.2017
 * @access: public
 */
class Kundenservice extends Controller
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

            require_once('libs/Pixi.php');
            $this->view->Pixi = new Pixi();

            require_once('models/backend_model.php');
            $this->view->back = new Backend_Model();

            $this->view->title = 'Mercury Kundenservice';
            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('backend/kundenservice/kus_info');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}