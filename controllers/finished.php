<?php

/**
 * Controller für die Auftrags-Abschlussseite
 *
 * @author: Marlon Böhland
 * @date:   14.12.2016
 */
class Finished extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        if (Session::checkAuth()) {
            $this->view->title = 'Auftrag abgeschlossen';

            $this->view->message = $this->view->render('messages/finished');

            $this->view->render('header');
            $this->view->render('login/index');
            $this->view->render('auftrag/finish');
            $this->view->render('footer');
        }
    }
}