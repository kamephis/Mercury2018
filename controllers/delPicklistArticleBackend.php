<?php

/**
 * Del Picklist Item Controller
 *
 * @author: Marlon Böhland
 * @access: public
 */
class DelPicklistArticleBackend extends Controller
{
    function __construct()
    {
        parent::__construct();
        Session::init();
    }

    function index()
    {
        $this->model = new DelPicklistArticle_Model();
    }

    function run()
    {
        $this->model->run();
    }
}