<?php

/**
 * Error Controller
 *
 * @author: Marlon Böhland
 * @date:   14.12.2016
 * @access: public
 */
class Error extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->view->title = 'Error 401';
        $this->view->msg = 'Zugriff verweigert.';

        $this->view->render('header');
        $this->view->render('login/index');
        $this->view->render('error/401');
        $this->view->render('footer');
    }

    function denied()
    {
        $this->view->title = '401 Zugriff verweigert';
        $this->view->msg = 'Ihr Benutzername / Kennwort ist nicht korrekt.';

        $this->view->render('header');
        $this->view->render('login/index');
        $this->view->render('error/401');
        $this->view->render('footer');
    }

    function run()
    {
        $this->model->run();
    }
}