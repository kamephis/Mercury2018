<?php

/**
 * Index Controller
 *
 * @author: Marlon Böhland
 * @date:   14.12.2016
 * @access: public
 */
class Index extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->view->title = 'Mercury';
        $this->view->render('header');
        $this->view->render('login/index');
        $this->view->render('footer');
    }

    function run()
    {
        $this->model->run();
    }
}