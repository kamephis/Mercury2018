<?php
/**
 * Class Picklist_Model
 * User: Marlon Böhland
 * Update: 28.07.2017
 */
class Picklist_Model extends Model
{
    private $aPicklist;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Picklistentyp Auslesen
     * @param $picklistNr
     * @return mixed
     */
    public function getPicklistType($picklistNr)
    {
        $sql = "SELECT picklistType FROM stpPickliste WHERE PLHkey = '{$picklistNr}' LIMIT 1";
        $result = $this->db->select($sql);
        return $result[0]['picklistType'];
    }

    /**
     * Auslesen der Picklistenpositionen
     * @param $picklistNr
     * @return array
     */
    public function getPicklistItems($picklistNr, $picklistType)
    {
        switch ($picklistType) {
            case 'gruppiert':
                $ins = "GROUP BY pitem.EanUpc";
                break;
            case 'ungruppiert':
                $ins = "";
                break;
        }

        $sql = "SELECT pitem.*, plist.PLHkey, plist.picklistType
                FROM stpPicklistItems pitem, stpArtikel2Pickliste a2p, stpPickliste plist
                WHERE
                pitem.ID = a2p.ArtikelID AND
                a2p.PicklistID = plist.PLHkey AND 
                plist.PLHkey = '{$picklistNr}' AND
                pitem.ItemStatus = 1 AND 
                LENGTH(pitem.ItemFehlerUser) = 0
                
                {$ins}

                ORDER BY pitem.BinSortNum
                ";
        $result = $this->db->select($sql);
        return $result;
    }

    /**
     * Alle Picklisteneinträge anzeigen
     * @param $picklistNr
     * @return array
     *
     * nicht verwendet
     */
    public function getAllPicklistItems($picklistNr)
    {
        $sql = "SELECT count(pitem.EanUpc) anz
                FROM stpPicklistItems pitem, stpArtikel2Pickliste a2p, stpPickliste plist

                WHERE
                pitem.ID = a2p.ArtikelID AND
                a2p.PicklistID = plist.PLHkey AND 
                plist.PLHkey = '{$picklistNr}' AND
                pitem.ItemStatus = 1
                GROUP BY pitem.EanUpc";

        $result = $this->db->select($sql);
        return $result[0]['anz'];
    }

    /**
     * Anzahl der Picklistenpositionen die nach der Gruppierung
     * für die Berechnung der Seitennavigation verwendet werden.
     * @param $PLHkey
     * @return mixed
     */
    public function getPicklistItemCount($PLHkey)
    {
        $sql = "SELECT count(*) as anzahl FROM (SELECT pitem.*, plist.PLHkey
                FROM stpPicklistItems pitem, stpArtikel2Pickliste a2p, stpPickliste plist
                WHERE
                pitem.ID = a2p.ArtikelID AND
                a2p.PicklistID = plist.PLHkey AND 
                plist.PLHkey = '{$PLHkey}' AND
                
                pitem.ItemStatus != 2 AND 
                
                LENGTH(pitem.ItemFehlerUser) = 0
                GROUP BY pitem.EanUpc
                ) as cnt";
        $result = $this->db->select($sql);
        return $result[0]['anzahl'];
    }

    /**
     * Anzahl der Picklistenpositionen die tatsächlich gepickt werden müssen
     * (immer aktuell, welche noch zu picken sind) - nicht in Verwendung!
     * @param $PLHkey
     * @return mixed
     */
    public function getRealPicklistItemCount($PLHkey)
    {
        $sql = "SELECT count(pitem.ID) as anz FROM stpArtikel2Pickliste a2p 
                RIGHT JOIN stpPicklistItems pitem
                ON a2p.ArtikelID = pitem.ID
                
                WHERE a2p.PicklistID = '{$PLHkey}'
                AND pitem.ItemStatus = 1";

        $result = $this->db->select($sql);
        return $result[0]['anz'];
    }

    /**
     * Groesse der Pickliste ausgeben
     *
     * @param $PLHkey
     * @return mixed
     */
    public function getGroessePicklist($PLHkey)
    {
        $sql = "SELECT count(*) as anz FROM stpArtikel2Pickliste a2p 
                RIGHT JOIN stpPicklistItems pitem
                ON a2p.ArtikelID = pitem.ID
                
                WHERE a2p.PicklistID = '{$PLHkey}'
                AND pitem.ItemStatus != 0";
        $result = $this->db->select($sql);
        return $result[0]['anz'];
    }

    /**
     * Picken einer einzelnen Position
     *
     * @param $articleID
     */
    public function setItemStatusArt($articleID)
    {
        $aUpdate = array('ItemStatus' => '2');
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $articleID);
    }

    /**
     * Setzt den ItemStatus auf einen bestimmten Wert
     * 0 = importiert
     * 1 = einer Pickliste zugewiesen
     * 2 = gepickt
     * 3 = zugeschnitten
     * 4 = Fehler
     * 5 = eskaliert -> KuS
     *
     * @param $status
     * @param $articleID
     */
    public function setArticleItemStatus($status, $articleID)
    {
        $aUpdate = array('ItemStatus' => $status);
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $articleID);
    }

    /**
     * Picken von Positionen (bei Aufträgen mit mehreren Längen)
     *
     * Setzen des ItemStatus bei allen Artikeln mit dieser EAN auf 2 (gepickt)
     * Zurücksetzen des Item Fehlers (da korrigiert)
     * @param $articleEan
     * @param $locationID
     * @param $ID
     * @param $picklistType
     */
    public function setItemStatus($articleEan = null, $locationID, $ID = null, $picklistType = null)
    {
        $aUpdate = array('ItemStatus' => '2', 'CurrentItemLocation' => $locationID, 'ItemFehler' => '', 'ItemFehlbestand' => '', 'ItemFehlerUser' => '');

        if ($picklistType == "gruppiert") {
            // Gruppenbearbeitung (Standard)
            try {
                $this->db->update('stpPicklistItems', $aUpdate, 'EanUpc = ' . $articleEan);
            } catch (Exception $e) {
                $e->getMessage();
            }
        } else {
            // Einzelbearbeitung (LX)
            try {
                $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $ID);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function stasi($plID, $uid, $numItems = null, $mod, $eanUpc, $binName = null, $fehlerhaft = null)
    {
        switch ($mod) {
            case 'start':

                $sql = "INSERT INTO 
                                          stpZeiterfassung(
                                           PL_ID,
                                           UID,
                                           NumItems,
                                           BinName,
                                           EanUpc
                                          ) VALUES (
                                           {$plID},
                                           {$uid},
                                           {$numItems},
                                           '{$binName}',
                                           '{$eanUpc}'
                                            )";
                $this->db->select($sql);
                break;

            case 'end':
                $sql = "UPDATE stpZeiterfassung SET TimestampEnd = NOW(), fehlerhaft = {$fehlerhaft} WHERE PL_ID = {$plID} AND EanUpc = '{$eanUpc}' AND BinName = '{$binName}' AND UID = {$uid}";
                $this->db->select($sql);
                break;
        }
    }

    /**
     * Zeiterfassung Picker
     * @param $plID
     * @param $uid
     * @param $numItems
     * @param $mod
     * @param $binName
     * @param $eanUpc
     * @param $fehlerhaft boolean
     */
    public function stasi_pdo($plID, $uid, $numItems = null, $mod, $eanUpc, $binName = null, $fehlerhaft = null)
    {
        $sqlInsertItems = null;

        switch ($mod) {
            case 'start':
                $sqlInsertItemsStart = $this->db->prepare("INSERT INTO 
                                  stpZeiterfassung(
                                   PL_ID,
                                   UID,
                                   NumItems,
                                   BinName,
                                   EanUpc
                                  ) VALUES (
                                   :PL_ID,
                                   :UID,
                                   :NumItems,
                                   :BinName,
                                   :EanUpc
                                    );");

                $sqlInsertItemsStart->execute(
                    array(

                        'PL_ID' => $plID,
                        'UID' => $uid,
                        'NumItems' => $numItems,
                        'BinName' => $binName,
                        'EanUpc' => $eanUpc
                    )
                );
                $sqlInsertItemsStart->closeCursor();
                break;

            case 'end':
                $sqlInsertItemsEnd = $this->db->prepare("UPDATE stpZeiterfassung SET TimestampEnd = NOW(), fehlerhaft = :fehlerhaft  WHERE PL_ID = :PL_ID AND EanUpc = :EanUpc AND BinName = :binName AND UID = :UID;");
                $sqlInsertItemsEnd->execute(
                    array(
                        'PL_ID' => $plID,
                        'UID' => $uid,
                        'NumItems' => $numItems,
                        'EanUpc' => $eanUpc,
                        'BinName' => $binName,
                        'fehlerhaft' => $fehlerhaft
                    )
                );
                $sqlInsertItemsEnd->closeCursor();
                break;
        }
    }

    /**
     * Picklisten Start
     *
     * @param $picklistNr
     */
    public function setPicklistTimer($picklistNr, $mode)
    {
        switch ($mode) {
            case "start":
                $aUpdateStart = array('pickStart' => date('Y-m-d G:i:s'));
                $this->db->update('stpPickliste', $aUpdateStart, 'PLHkey = ' . $picklistNr);
                break;
            case "end":
                $aUpdateStart = array('pickEnd' => date('Y-m-d G:i:s'));
                $this->db->update('stpPickliste', $aUpdateStart, 'PLHkey = ' . $picklistNr);
                break;
        }
    }

    /**
     * Picklisten End
     *
     * @param $picklistNr
     */
    public function setPicklistTimeEnd($picklistNr)
    {
        $aUpdateEnd = array('pickEnd' => date('Y-m-d G:i:s'));
        $this->db->update('stpPickliste', $aUpdateEnd, 'PLHkey = ' . $picklistNr);
    }

    /**
     * Fehlerhafte Position speichern
     *
     * Unterscheidung zwischen gruppierten und ungruppierten Fehlermeldungen
     * @param $articleID
     * @param $aFehler
     * @param $intItemFehlbestand
     * @param null $checked
     * @param null $pruefer
     * @param null $id
     * @param null $picklistType
     */
    public function setItemFehler($articleID = null, $aFehler, $intItemFehlbestand, $checked = null, $pruefer = null, $id = null, $picklistType = null)
    {
        // Einfügen des Fehler Users, wenn Fehler vorhanden
        if (strlen($intItemFehlbestand) > 0 || sizeof($aFehler) > 0) {
            $itemFehlerUser = $_SESSION['vorname'] . " " . $_SESSION['name'];
        } else {
            $itemFehlerUser = '';
        }

        // charset fix
        if ($aFehler != null) {
            $aFehler = utf8_decode($aFehler);
        }

        // Setzen der Fehlereinträge
        $aUpdate = array('ItemFehler' => $aFehler, 'ItemFehlbestand' => $intItemFehlbestand, 'ItemFehlerUser' => $itemFehlerUser, "geprueft" => $checked, "pruefer" => $pruefer, 'ItemStatus' => '4');

        // Aktivieren, wenn jede Position einzeln als Fehler bestätigt werden soll
        if ($picklistType == 'ungruppiert') {
            // Wenn eine ID übergeben wurde (ungruppiertes Picken)
            $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $id);
        } else {
            // Wenn eine EAN übergeben wurde (gruppiertes Picken)
            $this->db->update('stpPicklistItems', $aUpdate, 'EanUpc = ' . $articleID);
        }
    }


    /**
     * @param $plistNr
     * @param $status
     */
    public function setPicklistStatus($plistNr, $status)
    {
        $aUpdate = array('status' => $status);
        $this->db->update('stpPickliste', $aUpdate, 'PLHKEY = ' . $plistNr);
    }

    /**
     * @param $articleEAN
     * @param $PLHkey
     * @return array
     */
    public function getItemPickAmount($articleEAN, $PLHkey)
    {
        $sql = "SELECT Qty,(SELECT sum(Qty) FROM stpArtikel2Pickliste a2p 
                RIGHT JOIN stpPicklistItems pitem
                ON a2p.ArtikelID = pitem.ID
                
                WHERE a2p.PicklistID = '{$PLHkey}'
                AND pitem.EanUpc = '{$articleEAN}') as pSum
                
                FROM stpArtikel2Pickliste a2p 
                RIGHT JOIN stpPicklistItems pitem
                ON a2p.ArtikelID = pitem.ID
                
                WHERE a2p.PicklistID = '{$PLHkey}'
                AND pitem.EanUpc = '{$articleEAN}'";
        $result = $this->db->select($sql);
        return $result;
    }

    /**
     * Abfrage der Bestellungen / Artikel
     * @param $articleEAN
     * @return array
     */
    public function getOrders($articleEAN)
    {
        $sql = "SELECT OrderNrExternal, Qty, PLIheaderRef FROM stpPicklistItems WHERE EanUpc = '{$articleEAN}'";
        $result = $this->db->select($sql);
        return $result;
    }

    /**
     * Anzeigen des/der Artikelfehler/s
     * @param $artID
     * @return array
     */
    public function getItemFehler($artID)
    {
        $sql = "SELECT ItemFehler,ItemFehlbestand, ItemFehlerKommentar FROM stpPicklistItems WHERE ItemFehler <> '' AND ID = '{$artID}'";
        $result = $this->db->select($sql);
        return $result;
    }

    /**
     * @return mixed
     */
    public function getAPicklist()
    {
        return $this->aPicklist;
    }

    /**
     * @param mixed $aPicklist
     */
    public function setAPicklist($aPicklist)
    {
        $this->aPicklist = $aPicklist;
    }
}