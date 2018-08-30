<?php

/**
 * Picklist Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class Picklist extends Controller
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
            $this->model = new Picklist_Model();

            require_once('models/navigation_model.php');
            $this->view->nav = new Navigation_Model();

            $this->view->Picklist = $this->model;
            $this->view->AnzItems = $this->model->getPicklistItemCount($_REQUEST['picklistNr']);
            $this->view->Pixi = new Pixi();

            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('mobile/picklist');
            $this->view->render('footer');
        }
    }
    function run()
       {
           $this->model->run();
       }
}