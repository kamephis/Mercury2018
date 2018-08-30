<?php

/**
 * Login Controller
 * @author: Marlon Böhland
 * @date:   01.12.2016
 * @access: public
 */
class Login extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->view->title = 'Mercury Login';
        $this->view->render('header');
        $this->view->render('login/index');
        $this->view->render('footer');
    }

    function run()
    {
        // Login
        $this->model->run();
    }
}