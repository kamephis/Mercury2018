<?php

/**
 * Statistik Details Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class Statistik_Details extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->model = new Statistik_Model();
        $this->view->statistik = $this->model;
        $this->view->render('backend/statistik/loadAuftragDetails');
    }

    function run()
    {
        $this->model->run();
    }
}