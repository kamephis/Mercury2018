<?php

/**
 * Abfragen der Picklisteninformationen aus der Intranet Datenbank
 * Datenbasis für die Auflistung der Picklisten für den Benutzer dem
 * sie zugewiesen sind.
 *
 * @autor: Marlon Böhland
 * @access: public
 */
class ArtikelFehlerMobile_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Anzeigen aller Artikel, welche mit einem Fehler markiert wurden.
     * @return array
     */
    public function getFehlerhafteArtikel()
    {
        $sql = "SELECT items.* FROM stpPicklistItems items
            WHERE
            (/*items.ItemFehler IS NOT NULL OR
            items.ItemFehlbestand IS NOT NULL*/
             items.ItemFehler != ''
             OR items.ItemFehlbestand != '')
            ORDER BY items.BinSortNum ASC
            ";
        $result = $this->db->select($sql);
        return $result;
    }

    /**
     * Entfernen des korrgierten Fehler-Artikels (RESET)
     * @param $articleID
     */
    public function removeArtikelFromFehlerliste($articleID)
    {
        $aUpdate = array('ItemFehler' => NULL, 'ItemFehlbestand' => NULL);
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $articleID);
        // Refresh der Seite
        header('location: ' . URL . 'artikelFehlerMobile');
    }

    /**
     * Picken einer Position und zurücksetzen des Fehlers
     * @param $articleID
     */
    public function pickItem($articleID)
    {
        $aUpdate = array('ItemFehler' => NULL, 'ItemFehlbestand' => NULL, 'ItemStatus' => '2');
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $articleID);
        // Refresh der Seite
        header('location: ' . URL . 'artikelFehlerMobile');
    }
}