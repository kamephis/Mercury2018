<?php

class Zuschnitt extends Controller
{

    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        if (Session::checkAuth()) {
            $this->view->title = 'Auftragsbearbeitung';

            $this->view->render('header');
            $this->view->render('mobile/index');
            $this->view->render('footer');
        }
    }

    function run()
    {
        $this->model->run();
    }
}