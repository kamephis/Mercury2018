<?php

/**
 * @author: Marlon Böhland
 * @date: 20.03.2017
 */
class EditPicklist_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Abrufen der erstellten Picklisten
     * @return array
     */
    public function getAllPicklists()
    {
        $sql = "SELECT * From stpPickliste";

        $result = $this->db->select($sql);
        return $result[0];
    }

    /**
     * Bearbeiten einer Pickliste
     * @param $picklistID
     * @return array
     */
    public function editPicklist($picklistID)
    {
        $sql = "SELECT * From stpPickliste WHERE ID = '{$picklistID}'";
        return $this->db->select($sql);
    }
}