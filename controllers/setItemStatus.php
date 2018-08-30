<?php

/**
 * Class SetItemStatus
 */
class SetItemStatus extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->model = new SetItemStatus_Model();
    }

    function run()
    {
        $this->model->run();
    }
}
