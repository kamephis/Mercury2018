<?php

/**
 * Standard-Model
 *
 * @author: Marlon Böhland
 * @access: public
 */
class Model
{
    public $db = null;

    function __construct()
    {
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASSWD);
    }
    function run()
    {

    }
}