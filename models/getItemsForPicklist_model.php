<?php

class GetItemsForPicklist_Model extends Model
{
    private $oMySqli = null;

    public function __construct()
    {
        parent::__construct();
        // MySQL Objekt erzeugen
        $this->oMySqli = new mysqli();
        if (isset($_REQUEST['fnct'])) {
            switch ($_REQUEST['fnct']) {
                case 'get_items':
                    // TODO: Weshalb denn Qty oO?
                    $this->getInternMasterPicklistItems($_REQUEST['Qty']);
                    break;
            }
        }
    }

    /**
     * Alle Picklistenpositionen (Artikel) aus der intern
     * Datenbank abrufen.
     * @return array
     * ItemStatus = Null Standard (nicht bearbeitet)
     * ItemStatus = 1 in Bearbeitung
     * ItemStatus = 2 gepickt
     */
    public function getInternMasterPicklistItems()
    {
        // init
        $searchString = '';
        $minQty = '';
        $maxQty = '';
        $searchString = '';
        $warengruppe = '';
        $halle = '';
        $zuschnitt = '';

        if (isset($_GET['searchString'])) $searchString = $_GET['searchString'];
        if (isset($_GET['minQty'])) $minQty = $_GET['minQty'];
        if (isset($_GET['maxQty'])) $maxQty = $_GET['maxQty'];
        if (isset($_GET['warengruppe'])) $warengruppe = $_GET['warengruppe'];
        if (isset($_GET['halle'])) $halle = $_GET['halle'];
        if (isset($_GET['zuschnitt'])) $zuschnitt = $_GET['zuschnitt'];

        $filter = "";

        if (strlen($searchString) > 0) {
            $filter .= " AND a3.ItemName LIKE '%" . $searchString . "%'";
            $filter .= " OR a3.BinName LIKE '%" . $searchString . "%'";
        }

        if (strlen($minQty) > 0 && strlen($maxQty) > 0) {
            $filter .= " AND a3.Qty BETWEEN " . $minQty . " AND " . $maxQty;
        }

        if (strlen($warengruppe) > 0) {
            $filter .= " AND a3.BinGroup = " . $warengruppe;
        }

        if (strlen($zuschnitt) > 0) {
            switch ($zuschnitt) {
                case 'TI':
                    $filter .= " AND a3.Maschine = 'TI'";
                    break;
                case 'RM':
                    $filter .= " AND a3.Maschine = 'RM'";
                    break;
            }
        }

        if (strlen($halle) > 0) {
            switch ($halle) {
                case 'H1':
                    $filter .= " AND (a3.BinName LIKE 'H1%'";
                    $filter .= " OR a3.BinName LIKE 'RK%'";
                    $filter .= " OR a3.BinName LIKE 'GK%'";
                    $filter .= " OR a3.BinName LIKE 'LX%'";
                    $filter .= " OR a3.BinName LIKE 'LPR%'";
                    $filter .= " OR a3.BinName LIKE 'RG%'";
                    $filter .= " OR a3.BinName LIKE 'PANO%'";
                    $filter .= " OR a3.BinName LIKE 'OB%'";
                    $filter .= " OR a3.BinName LIKE 'NS%'";
                    $filter .= " OR a3.BinName LIKE 'WB-Naeh%'";
                    $filter .= " OR a3.BinName LIKE 'Ultra%'";
                    $filter .= " OR a3.BinName LIKE 'COU%')";
                    break;

                case 'H2':
                    $filter .= " AND (a3.BinName LIKE 'H2%'";
                    $filter .= " OR a3.BinName LIKE 'H2RG%')";
                    break;

                case 'ZG':
                    $filter .= " AND (a3.BinName LIKE 'ZG%'";
                    $filter .= " OR a3.BinName LIKE 'KW%'";
                    $filter .= " OR a3.BinName LIKE 'MX%')";
                    break;
            }
        }

        $aAllItems = array();
        $sqlMasterPicklistItems_org = "SELECT * FROM stpPicklistItems WHERE ItemStatus = 0 {$filter} ORDER BY BinGroup, BinSortNum ASC";
        $sqlMasterPicklistItems = "
                                SELECT * FROM (
                                    SELECT DISTINCT 
                                a.*,
                                CASE 
                                WHEN (SELECT count(*) 
                                        FROM stpPicklistItems a2 
                                        WHERE a2.EanUpc = a.EanUpc 
                                        AND a2.Qty > 6
                                        /*OR a2.ItemName LIKE '%ackfolie%'*/
                                        ) >= 1 
                                THEN 'RM'
                                ELSE 'TI' 
                                END AS Maschine
                                
                                FROM stpPicklistItems a
                                
                                ) a3
                                WHERE 
                                a3.ItemStatus = 0
                                {$filter}
                                ORDER BY a3.BinGroup, a3.BinSortNum ASC
                                ";

        //echo '<textarea>';
        //echo $sqlMasterPicklistItems;
        //echo '</textarea>';

        $this->oMySqli = new mysqli();
        $this->oMySqli->real_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME, DB_PORT);
        $this->oMySqli->set_charset('utf8');
        $query = $this->oMySqli->query($sqlMasterPicklistItems);

        while ($row = mysqli_fetch_assoc($query)) {
            $aAllItems[] = array('Maschine' => $row['Maschine'], 'ItemName' => $row['ItemName'], 'ArtNr' => $row['ItemNrInt'], 'BinName' => $row['BinName'], 'ID' => $row['ID'], 'Qty' => $row['Qty']);
        }

        $this->oMySqli->close();
        return $aAllItems;
    }

    /**
     * Liefert alle Positionen für die Rollenmaschine zurück.
     * Außerdem werden kürzere Stoffe, zu denen es Stoffe > 6m gibt
     * auch mit dem Flag "Rollenmaschine" versehen. Alle anderen werden mit "Tisch" versehen.
     * @return array
     */
    public function getRollen()
    {
        $aAllItems = array();

        $sql = "SELECT DISTINCT 
                a.EanUpc, a.ItemName, a.Qty,
                CASE 
                WHEN (SELECT count(*) 
                        FROM stpPicklistItems a2 
                        WHERE a2.EanUpc = a.EanUpc 
                        AND a2.Qty > 6) >= 1 
                THEN 'rollenmaschine'
                ELSE 'tisch' 
                END AS Machine
            FROM stpPicklistItems a
            ORDER BY a.EanUpc DESC";

        $this->oMySqli = new mysqli();

        $this->oMySqli->real_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME, DB_PORT);
        $this->oMySqli->set_charset('utf8');
        $query = $this->oMySqli->query($sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $aAllItems[] = array('ItemName' => $row['ItemName'], 'ArtNr' => $row['ItemNrInt'], 'BinName' => $row['BinName'], 'ID' => $row['ID'], 'Qty' => $row['Qty']);
        }

        $this->oMySqli->close();
        return $aAllItems;
    }
}
