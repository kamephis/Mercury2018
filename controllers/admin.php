<?php

/**
 * Admin Controller
 *
 * @author: Marlon Böhland
 * @date:   22.09.2017
 * @access: public
 */
class admin extends Controller
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

            $this->model = new Admin_Model();
            $this->view->pla = $this->model;

            require_once('models/picklistAdmin_model.php');
            $plAdmin = new PicklistAdmin_Model();

            // Zuweisen der Methodenergebnisse
            $this->view->pickerList = $plAdmin->getPicker();
            $this->view->PicklistAdmin = $plAdmin;

            $this->view->title = 'Mercury: Benutzerverwaltung';
            $this->view->render('header');
            $this->view->render('navigation');
            $this->view->render('backend/admin/admin');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}