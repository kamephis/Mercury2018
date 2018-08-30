<?php

/**
 * Update Fehler Status Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class SetFehlerStatusBackend extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->model = new SetFehlerStatusBackend_Model();
    }

    function run()
    {
        $this->model->run();
    }
}