<?php

/**
 * Picker Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class Picker extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        if (Session::checkAuth()) {
            $this->view->title = 'Picken';
            $this->model = new Picker_Model();
            require_once('models/navigation_model.php');
            $this->view->nav = new Navigation_Model();
            $this->view->masterPicklist = $this->model->getMasterPicklist(Session::get('UID'));
            $this->view->PickerModel = $this->model;

            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('mobile/picker');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}