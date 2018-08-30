<?php

class SetItemStatus_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
        $article_id = $_POST['articleID'];
        //$esc_comment = utf8_encode($_POST['EscComment']);
        $esc_comment = $_POST['EscComment'];
        $this->_setItemStatus($article_id, '5', $esc_comment);
        $this->_moveArticleToEscalateList($article_id);
    }

    /**
     * Setzt den ItemStatus auf einen bestimmten Wert
     * 0 = importiert
     * 1 = einer Pickliste zugewiesen
     * 2 = gepickt
     * 3 = zugeschnitten
     * 4 = Fehler
     * 5 = eskaliert -> KuS
     * 6 = Eskalation bearbeitet -> KuS
     *
     * @param $articleID
     * @param $itemStatus
     * @param $escComment
     */
    private function _setItemStatus($articleID, $itemStatus, $escComment)
    {
        $aUpdate = array('ItemStatus' => $itemStatus, 'EscComment' => $escComment);
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $articleID);
    }

    /**
     * Einfügen der eskalierten Positionen die noch nicht
     * in der Liste vorhanden sind
     *
     * @param $articleID
     */
    private function _moveArticleToEscalateList($articleID)
    {
        $sql = "INSERT INTO stpEscalateList
                SELECT '' as EID, pl.*
                FROM stpPicklistItems pl
                WHERE pl.ItemStatus = '5'
                AND pl.ID = :articleID
                ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':articleID', $articleID);
        $stmt->execute();
    }
}
