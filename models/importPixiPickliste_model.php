<?php

/**
 * Funktionen für dem Import der Pixi Picklisten
 * Erstellen neuer (interner) Picklisten (Digitale Pickliste)
 *
 * @author: Marlon Böhland
 * @date: 29.12.2016
 */
class ImportPixiPickliste_Model extends Model
{
    private $oProxy = null;
    private $oMySqli = null;
    private $oMysqli_oxid = null;

    /**
     * StpPixiPicklistImport constructor.
     * Erzeugen der Pixi und MySQL Verbindungen
     */
    public function __construct()
    {
        parent::__construct();

        // Pixi API-Verbindung herstellen
        $oSoapClient = new nusoap_client(PIXI_WSDL_PATH, true);
        $oSoapClient->setCredentials(PIXI_USERNAME, PIXI_PASSWORD);

        // pixi* API Objekt erzeugen
        $this->setOProxy($oSoapClient->getProxy());
    }

    /**
     * Alle Picklisten aus dem Pixi Auftrags-Pool abrufen (zur Anzeige in der Pixklistenerstellung)
     * Neue Version mit Error-Handling
     * @return mixed
     *
     * TODO: Refaktor in Pixi Klasse. Hier entfernen
     */
    public function getAllPixiPicklists()
    {
        $aAllPicklists = $this->oProxy->pixiShippingGetPicklistHeaders(array('LocID' => '001'));

        if (isset($aAllPicklists['pixiShippingGetPicklistHeadersResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'])) {
            $aAllPicklists = $this->oProxy->pixiShippingGetPicklistHeaders(array('LocID' => '001'));
            $aAllPicklists = $aAllPicklists['pixiShippingGetPicklistHeadersResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];

            return $aAllPicklists;
        }
        return false;
    }

    /**
     * Zusatzdaten aus OXID auslesen und als Array zurückgeben.
     *
     * @param $oxartnum
     * @return mixed
     *
     * PDO-Version
     */
    public function importOxidData__($oxartnum)
    {
        try {
            // Ergänzen der OXID Infos
            $sqlSelOxidData = $this->db->prepare("SELECT OXPIC1, OXPIC2, OXPIC3, OXPIC4, OXPIC5, OXTHUMB, OXID FROM oxarticles WHERE OXARTNUM = ':oxartnum' LIMIT 1");

            $sqlSelOxidData->execute(array(
                'oxartnum' => $oxartnum
            ));
            $result = $sqlSelOxidData->fetch(PDO::FETCH_ASSOC);

            $sqlSelOxidData->closeCursor();


            return $result;

        } catch (PDOException $e) {
            die($e->errorInfo);
        }
    }

    public function importOxidData($oxartnum)
    {
        // Ergänzen der OXID Infos
        $sqlSelOxidData = "SELECT OXPIC1, OXPIC2, OXPIC3, OXPIC4, OXPIC5, OXTHUMB, OXID FROM oxarticles WHERE OXARTNUM = '{$oxartnum}' LIMIT 1";
        $this->oMysqli_oxid = new mysqli();

        $this->oMysqli_oxid->real_connect(DB_HOST_OXID, DB_USER_OXID, DB_PASSWD_OXID, DB_NAME_OXID, DB_PORT_OXID);
        //$this->oMysqli_oxid->set_charset('utf8');

        $aOxid = $this->oMysqli_oxid->query($sqlSelOxidData)->fetch_array(MYSQLI_ASSOC);
        $this->oMysqli_oxid->close();
        return $aOxid;
    }


    /**
     * Pickliste aus Pixi in die interne Datenbank importieren
     * aktiv! - PDO Version
     * @param $picklist
     */
    public function importPicklist($picklist)
    {
        $sqlCheckPl = $this->db->prepare("SELECT PLIheaderRef FROM stpPicklistItems WHERE PLIheaderRef = :picklist");

        $sqlCheckPl->execute(array('picklist' => $picklist));
        $rows = $sqlCheckPl->rowCount();
        $sqlCheckPl->closeCursor();

        // Prüfen ob die Pickliste bereits existiert
        if ($rows == 0) {

            // TODO: Version aus der Pixi Klasse testen und lokale Kopie entfernen
            //$cPixi = new Pixi();
            //$aPicklistDetails =  $cPixi->getPicklistDetails($picklist);

            $aPicklistDetails = $this->getPicklistDetails($picklist);

            // Pickliste Zeile für Zeile in die stpPicklistItems Tabelle schreiben.

            // Initialisierung
            $sqlInsertItems = null;

            // Leeren der Tabelle (falls erforderlich)
            //$sqlInsertItems = "TRUNCATE TABLE stpPicklistItems;";

            // Struktur des Arrays prüfen
            if (is_array($aPicklistDetails[0])) {

                try {
                    foreach ($aPicklistDetails as $items) {
                        $aOxid = $this->importOxidData($items['ItemNrSuppl']);

                        $sqlInsertItems = $this->db->prepare("INSERT INTO 
                                  stpPicklistItems(
                                   BinItemRef,
                                   PicLinkSmall,
                                   PicLinkLarge,
                                   PLIheaderRef,
                                   BinSortNum,
                                   ItemName,
                                   BinName,
                                   BinKey,
                                   PicklistExpiryDate,
                                   PicklistCreateDate,
                                   ItemOrderDate,
                                   Qty,
                                   PicklistComment,
                                   ItemNrSuppl,
                                   OrderNrExternal,
                                   EanUpc,
                                   ItemNrInt,
                                   BinRef,
                                   BinGroup,
                                   PLIorderlineRef,
                                   Location,
                                   OtherItemsCount,
                                   PLHfromBox,
                                   PLHtoBox,
                                   PLIorderlineRef1
                                  ) VALUES (
                                   :BinItemRef,
                                   :OXTHUMB,
                                   :OXPIC1,
                                   :PLIheaderRef,
                                   :BinSortNum,
                                   :ItemName,
                                   :BinName,
                                   :BinKey,
                                   :PicklistExpiryDate,
                                   :PicklistCreateDate,
                                   :ItemOrderDate,
                                   :Qty,
                                   :PicklistComment,
                                   :ItemNrSuppl,
                                   :OrderNrExternal,
                                   :EanUpc,
                                   :ItemNrInt,
                                   :BinRef,
                                   :BinGroup,
                                   :PLIorderlineRef,
                                   :Location,
                                   :OtherItemsCount,
                                   :PLHfromBox,
                                   :PLHtoBox,
                                   :PLIorderlineRef1
                                   
                                    );");

                        $sqlInsertItems->execute(
                            array(

                                'BinItemRef' => $items['BinItemRef'],
                                'OXTHUMB' => $aOxid['OXTHUMB'],
                                'OXPIC1' => $aOxid['OXPIC1'],

                                'PLIheaderRef' => $items['PLIheaderRef'],
                                'BinSortNum' => $items['BinSortNum'],
                                'ItemName' => $items['ItemName'],
                                'BinName' => $items['BinName'],
                                'BinKey' => $items['BinKey'],

                                'PicklistExpiryDate' => $items['PicklistExpiryDate'],
                                'PicklistCreateDate' => $items['PicklistCreateDate'],
                                'ItemOrderDate' => '',

                                'Qty' => $items['Qty'],
                                'PicklistComment' => $items['PicklistComment'],
                                'ItemNrSuppl' => $items['ItemNrSuppl'],
                                'OrderNrExternal' => $items['OrderNrExternal'],
                                'EanUpc' => $items['EanUpc'],
                                'ItemNrInt' => $items['ItemNrInt'],
                                'BinRef' => $items['BinRef'],
                                'BinGroup' => $items['BinGroup'],
                                'PLIorderlineRef' => $items['PLIorderlineRef'],
                                'Location' => $items['Location'],
                                'OtherItemsCount' => $items['OtherItemsCount'],
                                'PLHfromBox' => $items['PLHfromBox'],
                                'PLHtoBox' => $items['PLHtoBox'],
                                'PLIorderlineRef1' => $items['PLIorderlineRef1']

                            )
                        );
                    }
                    Controller::showMessages('pixiImpSuccess');
                    $sqlInsertItems->closeCursor();
                } catch (PDOException $e) {
                    View::showAlert('danger', null, "Error: " . $e->getMessage() . "<br>" . $e->errorInfo);
                }

            } else {
                $aOxid = $this->importOxidData($aPicklistDetails['ItemNrSuppl']);

                try {
                    $sqlInsertItems = $this->db->prepare("INSERT INTO 
                                  stpPicklistItems(
                                   BinItemRef,
                                   PicLinkSmall,
                                   PicLinkLarge,
                                   PLIheaderRef,
                                   BinSortNum,
                                   ItemName,
                                   BinName,
                                   BinKey,
                                   PicklistExpiryDate,
                                   PicklistCreateDate,
                                   ItemOrderDate,
                                   Qty,
                                   PicklistComment,
                                   ItemNrSuppl,
                                   OrderNrExternal,
                                   EanUpc,
                                   ItemNrInt,
                                   BinRef,
                                   BinGroup,
                                   PLIorderlineRef,
                                   Location,
                                   OtherItemsCount,
                                   PLHfromBox,
                                   PLHtoBox,
                                   PLIorderlineRef1
                                  ) VALUES (
                                   :BinItemRef,
                                   :OXTHUMB,
                                   :OXPIC1,
                                   :PLIheaderRef,
                                   :BinSortNum,
                                   :ItemName,
                                   :BinName,
                                   :BinKey,
                                   :PicklistExpiryDate,
                                   :PicklistCreateDate,
                                   :ItemOrderDate,
                                   :Qty,
                                   :PicklistComment,
                                   :ItemNrSuppl,
                                   :OrderNrExternal,
                                   :EanUpc,
                                   :ItemNrInt,
                                   :BinRef,
                                   :BinGroup,
                                   :PLIorderlineRef,
                                   :Location,
                                   :OtherItemsCount,
                                   :PLHfromBox,
                                   :PLHtoBox,
                                   :PLIorderlineRef1
                                   
                                    );");

                    $sqlInsertItems->execute(
                        array(

                            'BinItemRef' => $aPicklistDetails['BinItemRef'],
                            'OXTHUMB' => $aPicklistDetails['OXTHUMB'],
                            'OXPIC1' => $aPicklistDetails['OXPIC1'],

                            'PLIheaderRef' => $aPicklistDetails['PLIheaderRef'],
                            'BinSortNum' => $aPicklistDetails['BinSortNum'],
                            'ItemName' => $aPicklistDetails['ItemName'],
                            'BinName' => $aPicklistDetails['BinName'],
                            'BinKey' => $aPicklistDetails['BinKey'],

                            'PicklistExpiryDate' => $aPicklistDetails['PicklistExpiryDate'],
                            'PicklistCreateDate' => $aPicklistDetails['PicklistCreateDate'],
                            'ItemOrderDate' => '',

                            'Qty' => $aPicklistDetails['Qty'],
                            'PicklistComment' => $aPicklistDetails['PicklistComment'],
                            'ItemNrSuppl' => $aPicklistDetails['ItemNrSuppl'],
                            'OrderNrExternal' => $aPicklistDetails['OrderNrExternal'],
                            'EanUpc' => $aPicklistDetails['EanUpc'],
                            'ItemNrInt' => $aPicklistDetails['ItemNrInt'],
                            'BinRef' => $aPicklistDetails['BinRef'],
                            'BinGroup' => $aPicklistDetails['BinGroup'],
                            'PLIorderlineRef' => $aPicklistDetails['PLIorderlineRef'],
                            'Location' => $aPicklistDetails['Location'],
                            'OtherItemsCount' => $aPicklistDetails['OtherItemsCount'],
                            'PLHfromBox' => $aPicklistDetails['PLHfromBox'],
                            'PLHtoBox' => $aPicklistDetails['PLHtoBox'],
                            'PLIorderlineRef1' => $aPicklistDetails['PLIorderlineRef1']
                        )
                    );

                    Controller::showMessages('pixiImpSuccess');
                    $sqlInsertItems->closeCursor();

                } catch (PDOException $e) {
                    View::showAlert('danger', null, "Error: " . $e->getMessage() . "<br>" . $e->errorInfo);
                }
            }
        } else {
            Controller::showMessages('pixiImpCheck');
        }
    }
    /**
     * Artikel einer Pixi Pickliste abrufen
     * @param $picklistNr
     * @return mixed
     *
     * TODO: diese Version entfernen (Aufruf über die Pixi Klasse
     */
    public function getPicklistDetails($picklistNr)
    {
        $aPicklistDetails = $this->oProxy->pixiShippingGetPicklistDetails(array('PicklistKey' => $picklistNr));
        $aPicklistDetails = $aPicklistDetails['pixiShippingGetPicklistDetailsResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];

        return $aPicklistDetails;
    }

    /**
     * Pixi Proxy setzen
     * @param null $oProxy
     */
    public function setOProxy($oProxy)
    {
        $this->oProxy = $oProxy;
    }
}