<?php

/**
 * UpdatePicklist Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class UpdatePicklist extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->model = new UpdatePicklist_Model();
        $this->view->pl = $this->model;
        $this->view->render('backend/picklistAdmin/updatePicklist');
    }

    function run()
    {
        $this->model->run();
    }
}