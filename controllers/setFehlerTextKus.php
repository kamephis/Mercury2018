<?php

/**
 * @author: Marlon Böhland
 * @access: public
 */
class SetFehlerTextKus extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->model = new SetFehlerTextKus_Model();
    }

    function run()
    {
        $this->model->run();
    }
}