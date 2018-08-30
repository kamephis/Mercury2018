<?php

class SetItemStatusFehler_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_setItemStatusFehler($_POST['articleID'], $_POST['ItemStatus']);
    }

    /**
     * @param $articleID
     * @param $itemStatus
     */
    public function _setItemStatusFehler($articleID, $itemStatus)
    {
        $aUpdate = array('ItemStatus' => $itemStatus, 'ItemFehler' => '', 'ItemFehlbestand' => '', 'ItemFehlerKommentar' => '', 'ItemFehlerUser' => '');
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $articleID);

        if ($itemStatus == 5 || $itemStatus == 6) $this->_setItemStatusKus($articleID, $itemStatus);
    }

    /**
     * Setzten des ItemStatus in der stpEscalateList + in der stpPicklistItems
     * @param $articleID
     * @param $itemStatus
     */
    public function _setItemStatusKus($articleID, $itemStatus)
    {
        $aUpdate = array('ItemStatus' => $itemStatus, 'ItemFehler' => '', 'ItemFehlbestand' => '', 'ItemFehlerKommentar' => '', 'ItemFehlerUser' => '');
        $this->db->update('stpEscalateList', $aUpdate, 'ID = ' . $articleID);
    }


}
