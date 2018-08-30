<?php

/**
 * EditPicklist Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class EditPicklist extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        if (Session::checkAuth()) {
            $this->view->title = 'Pickliste Bearbeiten';

            require_once('models/navigation_model.php');
            $this->view->nav = new Navigation_Model();

            $this->model = new EditPicklist_Model();

            $this->view->picklist = $this->model;
            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('backend/picklistAdmin/editPicklist');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}