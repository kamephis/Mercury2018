<?php

/**
 * Funktionen für die Auftragsbearbeitung am Zuschneidetisch
 *
 * @author: Marlon Böhland
 * @access: public
 */
class Auftrag_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Prüfen ob Max Bestand
     * @param $sEanUpc
     * @return string
     */
    public function getItemFehlerMax($sEanUpc)
    {
        $sql = "SELECT ItemFehler FROM stpPicklistItems WHERE EanUpc = '{$sEanUpc}' AND ItemFehler = 'Max. Menge'";
        $result = $this->db->select($sql);
        $msg = null;

        if (sizeof($result) > 0) {
            $msg = 'REST';
        } else {
            $msg = '';
        }
        return $msg;
    }

    /**
     * Fehlerartikel erfassen
     * TODO: mit Funktion aus dem Picklist Model ersetzen
     * @param $articleID
     * @param $aFehler
     * @param $intItemFehlbestand
     * @param null $checked
     * @param null $pruefer #
     */
    public function setItemFehlerAuftrag($articleID, $aFehler, $intItemFehlbestand, $checked = null, $pruefer = null)
    {
        // Einfügen des Fehler Users, wenn Fehler vorhanden
        if (strlen($intItemFehlbestand) > 0 || sizeof($aFehler) > 0) {
            $itemFehlerUser = $_SESSION['vorname'] . " " . $_SESSION['name'];
        } else {
            $itemFehlerUser = '';
        }

        // charset fix
        if ($aFehler != Null) {
            $aFehler = utf8_decode($aFehler);
        }
        $aUpdate = array('ItemFehler' => $aFehler, 'ItemFehlbestand' => $intItemFehlbestand, 'ItemFehlerUser' => $itemFehlerUser, "geprueft" => $checked, "pruefer" => $pruefer, 'ItemStatus' => '4');
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $articleID);
    }

    /**
     * Neuer Zuschneideauftrag erstellen
     * @param $userID
     */
    public function newAuftrag($userID, $artEAN)
    {
        $this->db->insert('stpZuschneideAuftraege', array('UserID' => $userID, 'ArtEAN' => $artEAN, 'TimestampStart' => date('Y-m-d G:i:s')));
    }

    /**
     * Ausgabe der Auftrags-Daten zur Anzeige in den Panels
     * @return array
     */
    public function getAuftragsDatum()
    {
        $sql = "SELECT DISTINCT date_format(TimestampStart, '%d.%m.%Y') AS 'datum' FROM stpZuschneideAuftraege ";
        return $this->db->select($sql);
    }

    /**
     * Artikelposition in die Auftragstabelle einfügen
     * Der Status der Position kann hier gesetzt werden.
     * @param $artID
     * @param $auftragID
     * @param $menge
     *
     * wird nicht verwendet
     */
    public function insertArticle2Auftrag($artID, $auftragID, $menge)
    {
        $this->db->insert('stpArtikel2Auftrag', array('ArtID' => $artID, 'AuftragID' => $auftragID, 'Menge' => $menge));
    }

    /**
     * Auslesen der neuesten Auftragsnummer
     * @return array
     */
    public function getAuftragsnummer()
    {
        $sql = "SELECT MAX(AuftragsID) as AuftragsNr FROM stpZuschneideAuftraege";
        return $this->db->select($sql);
    }

    /**
     * Prüfen ob der Auftrag bereits existiert
     * @param $aNr
     * @return int
     */
    public function checkAugtragsnummer($aNr)
    {
        $sql = "SELECT AuftragsID FROM stpZuschneideAuftraege";
        $result = $this->db->select($sql);
        return sizeof($result);
    }

    /**
     * Auftrag abschließen
     * Existiert eigentlich nur zur Erfassung der Endzeit des Auftrags und
     * der bearbeiteten Meteranzahl des Artikels -> Statistik
     *
     * @param $auftragsID int   ID in der Artikel2Auftrag Tabelle
     * @param $kommentar String Kommentar des Anwenders zum Auftrag
     * @return array
     */
    public function finishAuftrag($auftragsID, $anzahl)
    {
        $aUpdate = array('Status' => '1', 'Anzahl' => $anzahl);
        //$aUpdate = array('Status' => '1', 'Anzahl' => $anzahl, 'TimestampEnd' => date('Y-m-d G:i:s'));
        $this->db->update('stpZuschneideAuftraege', $aUpdate, 'AuftragsID = ' . $auftragsID);
    }

    /**
     * Abschließen einer Auftragsposition
     * Status = 3 Aufträge sind fertig bearbeitet, es wurde ein Etikett für den Ship-Out gedruckt
     * Diese Aufträge tauchen nicht mehr auf anderen Aufträgen auf.
     *
     * @param $artID
     */
    public function setAuftragsPositionStatus($artID, $aNr)
    {
        $aUpdate = array('ItemStatus' => '3', 'AuftragID' => $aNr);
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $artID);
    }

    /**
     * Zusammenstellen eines Zuschneideauftrags
     * anhand der Artikelnummer des gescannten
     * Stoffs. Anzeige aller Positionen deren Status NICHT 3 (bearbeitet) ist.
     * @return mixed
     */
    public function getAuftrag($artEAN)
    {
        $sql = "SELECT pli.*, DATE_FORMAT(pli.PicklistExpiryDate,'%d.%m.%Y') as expDate,DATE_FORMAT(pli.PicklistCreateDate,'%d.%m.%Y') as createDate FROM stpPicklistItems pli WHERE EanUpc = '{$artEAN}' AND ItemStatus != '3' ORDER BY Qty DESC";
        return $this->db->select($sql);
    }

    /**
     * Setter: Artikelnummer
     * @param $artNr int
     */
    public function setArtNr($artNr)
    {
        $this->artNr = $artNr;
    }
}