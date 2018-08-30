<?php
/**
 * Abfragen der Picklisteninformationen aus der Intranet Datenbank
 * Datenbasis für die Auflistung der Picklisten für den Benutzer dem
 * sie zugewiesen sind.
 *
 * @autor: Marlon Böhland
 * @access: public
 */
class Picker_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Anzeige der Picklisten-Infos eines Pickers im Mobile Startbildschirm
     *
     * @param $picker
     * @return array
     */
    public function getMasterPicklist($picker)
    {
        $sql = "SELECT pl.*, DATE_FORMAT(pl.createDate,'%d.%m.%Y') as createDate, DATE_FORMAT(pl.PLHExpiryDate,'%d.%m.%Y') as expDate, PLcomment FROM stpPickliste pl WHERE pl.picker = '{$picker}' AND pl.status != '1' AND pl.status != '2'";
        return $this->db->select($sql);
    }

    /**
     * Anzahl der Items / Pickliste abrufen
     * @param $PLHkey
     * @return mixed
     */
    public function getPicklistItemCount($PLHkey)
    {
        $sql = "SELECT *, count(*) as anzahl FROM (SELECT count(*) FROM stpArtikel2Pickliste a2p 
                LEFT JOIN stpPicklistItems pitem
                ON a2p.ArtikelID = pitem.ID
                
                WHERE a2p.PicklistID = '{$PLHkey}'
                AND pitem.ItemStatus < '2'
                GROUP BY EanUpc
                ) as cnt";
        $result = $this->db->select($sql);
        return $result[0]['anzahl'];
    }

    /**
     * Ausgabe der Fehlerhaften Artikel
     * @return array
     */
    public function getFehlerhafteArtikel()
    {
        $sql = "SELECT * FROM stpPicklistItems WHERE LENGTH(ItemFehlerUser) > 0";
        $result = $this->db->select($sql);
        return $result;
    }
}