<?php

class SetItemStatusFehler extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->model = new SetItemStatusFehler_Model();
    }

    function run()
    {
        $this->model->run();
    }
}