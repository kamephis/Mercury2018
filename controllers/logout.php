<?php

/**
 * Logout Controller
 *
 * @author: Marlon Böhland
 * @date:   01.12.2016
 * @access: public
 */
class Logout extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        session_destroy();
        //$this->view->title = 'Mercury: Abgemeldet';
        //$this->view->render('header');
        //$this->view->render('login/index');
        header('Location: ' . URL . 'login?msg=logout');

        /*if (strlen($_SESSION['user']) == 0) {
            $this->view->message = $this->view->render('messages/loggedout');
        }*/
        //$this->view->render('footer');
    }

    function run()
    {
        $this->model->run();
    }
}