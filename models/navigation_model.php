<?php

/**
 * Abfragen der Navigationseinträge aus der Intranet-Datenbank
 *
 * @author: Marlon Böhland
 * @access: public
 */
class Navigation_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Abrufen der Navigationseinträge
     * für einen User
     * @return array
     */
    public function getNavItems($accessLevel)
    {
        $sql = "SELECT * FROM stpMercuryNav WHERE (access_level & '{$accessLevel}') ORDER BY ItemOrder ASC ";
        $sql = $this->db->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}